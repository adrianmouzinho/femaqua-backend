<?php

namespace Tests\Feature;

use App\Models\Tool;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Testing\Fluent\AssertableJson;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class ToolControllerTest extends TestCase
{
    use RefreshDatabase;

    private function authenticateUser()
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user);
        return $user;
    }

    /**
     * Test if an authenticated user can get the list of tools.
     */
    public function test_user_can_list_tools(): void
    {
        $user = $this->authenticateUser();

        $tools = Tool::factory(3)->create(['user_id' => $user->id]);

        $response = $this->getJson('/api/v1/tools');

        $response->assertStatus(200);
        $response->assertJsonCount(3);

        $response->assertJson(function (AssertableJson $json) use ($tools) {
            $json->hasAll(['0.id', '0.title', '0.link', '0.description', '0.tags']);

            $json->whereAllType([
                '0.id' => 'integer',
                '0.title' => 'string',
                '0.link' => 'string',
                '0.description' => 'string',
                '0.tags' => 'array',
            ]);

            $tool = $tools->first();

            $json->whereAll([
                '0.id' => $tool->id,
                '0.title' => $tool->title,
                '0.link' => $tool->link,
                '0.description' => $tool->description,
                '0.tags' => $tool->tags,
            ]);
        });
    }

    /**
     * Test if a non-authenticated user cannot access the list of tools.
     */
    public function test_non_authenticated_user_cannot_list_tools(): void
    {
        $response = $this->getJson('/api/v1/tools');

        $response->assertStatus(401);
    }

    /**
     * Test if an authenticated user can create a tool.
     */
    public function test_user_can_create_a_tool(): void
    {
        $this->authenticateUser();

        $tool = Tool::factory(1)->makeOne()->toArray();

        $response = $this->postJson('/api/v1/tools', $tool);

        $response->assertStatus(201);

        $response->assertJson(function (AssertableJson $json) use ($tool) {
            $json->hasAll(['id', 'title', 'link', 'description', 'tags']);

            $json->whereAllType([
                'id' => 'integer',
                'title' => 'string',
                'link' => 'string',
                'description' => 'string',
                'tags' => 'array',
            ]);

            $json->whereAll([
                'title' => $tool['title'],
                'link' => $tool['link'],
                'description' => $tool['description'],
                'tags' => $tool['tags'],
            ])->etc();
        });
    }

    /**
     * Test if an authenticated user cannot create a tool with invalidated data.
     */
    public function test_user_cannot_create_a_tool_with_invalidated_data(): void
    {
        $this->authenticateUser();

        $response = $this->postJson('/api/v1/tools', []);

        $response->assertStatus(422);

        $response->assertJson(function (AssertableJson $json) {
            $json->hasAll(['success', 'message', 'data']);

            $json->whereAll([
                'message' => 'Validation errors',
            ])->etc();
        });
    }

    /**
     * Test if a non-authenticated user cannot create a tool.
     */
    public function test_non_authenticated_user_cannot_create_a_tool(): void
    {
        $tool = Tool::factory(1)->makeOne()->toArray();

        $response = $this->postJson('/api/v1/tools', $tool);

        $response->assertStatus(401);
    }

    /**
     * Test if an authenticated user can update a tool.
     */
    public function test_user_can_update_a_tool(): void
    {
        $user = $this->authenticateUser();

        $tool = Tool::factory(1)->createOne(['user_id' => $user->id]);

        $data = [
            'title' => 'Notion',
            'link' => 'https://notion.so',
            'description' => 'All in one tool to organize teams and ideas. Write, plan, collaborate, and get organized.',
        ];

        $response = $this->putJson('/api/v1/tools/' . $tool->id, $data);

        $response->assertStatus(200);

        $response->assertJson(function (AssertableJson $json) use ($tool, $data) {
            $json->hasAll(['id', 'title', 'link', 'description', 'tags']);

            $json->whereAllType([
                'id' => 'integer',
                'title' => 'string',
                'link' => 'string',
                'description' => 'string',
                'tags' => 'array',
            ]);

            $json->whereAll([
                'id' => $tool['id'],
                'title' => $data['title'],
                'link' => $data['link'],
                'description' => $data['description'],
                'tags' => $tool['tags'],
            ]);
        });
    }

    /**
     * Test if an authenticated user cannot update a tool with invalidated data.
     */
    public function test_user_cannot_update_a_tool_with_invalidated_data(): void
    {
        $this->authenticateUser();

        $tool = Tool::factory(1)->createOne();

        $response = $this->putJson('/api/v1/tools/' . $tool->id, []);

        $response->assertStatus(422);

        $response->assertJson(function (AssertableJson $json) {
            $json->hasAll(['success', 'message', 'data']);

            $json->whereAll([
                'message' => 'Validation errors',
            ])->etc();
        });
    }

    /**
     * Test if an authenticated user cannot update a tool that is not theirs.
     */
    public function test_user_cannot_update_a_tool_that_is_not_theirs(): void
    {
        $this->authenticateUser();
        $user = User::factory()->create();

        $tool = Tool::factory(1)->createOne(['user_id' => $user->id]);

        $data = [
            'title' => 'Notion',
            'link' => 'https://notion.so',
            'description' => 'All in one tool to organize teams and ideas. Write, plan, collaborate, and get organized.',
        ];

        $response = $this->putJson('/api/v1/tools/' . $tool->id, $data);

        $response->assertStatus(403);

        $response->assertJson(function (AssertableJson $json) {
            $json->hasAll(['message']);

            $json->whereAll([
                'message' => 'You do not have permission to delete this tool.',
            ]);
        });
    }

    /**
     * Test if a non-authenticated user cannot update a tool.
     */
    public function test_non_authenticated_user_cannot_update_a_tool(): void
    {
        $data = [
            'title' => 'Notion',
            'link' => 'https://notion.so',
            'description' => 'All in one tool to organize teams and ideas. Write, plan, collaborate, and get organized.',
        ];

        $response = $this->putJson('/api/v1/tools/1', $data);

        $response->assertStatus(401);
    }

    /**
     * Test if an authenticated user can delete a tool.
     */
    public function test_user_can_delete_a_tool(): void
    {
        $user = $this->authenticateUser();

        $tool = Tool::factory(1)->createOne(['user_id' => $user->id]);

        $response = $this->deleteJson('/api/v1/tools/' . $tool->id);

        $response->assertStatus(200);

        $response->assertJson(function (AssertableJson $json) {
            $json->hasAll(['message']);

            $json->whereAll([
                'message' => 'Tool deleted.',
            ]);
        });
    }

    /**
     * Test if an authenticated user cannot delete a tool that is not theirs.
     */
    public function test_user_cannot_delete_a_tool_that_is_not_theirs(): void
    {
        $this->authenticateUser();
        $user = User::factory()->create();

        $tool = Tool::factory(1)->createOne(['user_id' => $user->id]);

        $response = $this->deleteJson('/api/v1/tools/' . $tool->id);

        $response->assertStatus(403);

        $response->assertJson(function (AssertableJson $json) {
            $json->hasAll(['message']);

            $json->whereAll([
                'message' => 'You do not have permission to delete this tool.',
            ]);
        });
    }

    /**
     * Test if a non-authenticated user cannot delete a tool.
     */
    public function test_non_authenticated_user_cannot_delete_a_tool(): void
    {
        $response = $this->deleteJson('/api/v1/tools/1');

        $response->assertStatus(401);
    }
}
