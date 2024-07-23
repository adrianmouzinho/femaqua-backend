<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ToolResource extends JsonResource
{
    /**
     * @OA\Schema(
     *     schema="ToolResource",
     *     type="object",
     *     @OA\Property(
     *         property="id",
     *         type="integer",
     *         description="Id of the tool"
     *     ),
     * *   @OA\Property(
     *         property="title",
     *         type="string",
     *         description="Title of the tool"
     *     ),
     * *   @OA\Property(
     *         property="link",
     *         type="string",
     *         description="Link of the tool"
     *     ),
     * *   @OA\Property(
     *         property="description",
     *         type="string",
     *         description="Description of the tool"
     *     ),
     * *   @OA\Property(
     *         property="tags",
     *         type="array",
     *         description="Tags of the tool",
     *         @OA\Items(
     *             type="string"
     *         )
     *     )
     * )
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'link' => $this->link,
            'description' => $this->description,
            'tags' => $this->tags,
        ];
    }
}
