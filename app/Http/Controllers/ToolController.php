<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreToolRequest;
use App\Http\Requests\UpdateToolRequest;
use App\Http\Resources\ToolResource;
use App\Interfaces\ToolRepositoryInterface;
use App\Models\Tool;
use Illuminate\Http\Request;

/**
 * @OA\Info(
 *      title="FEMAQUA",
 *      version="1.0",
 *      description="Especificações da API para o back-end da aplicação FEMAQUA."
 * )
 *
 * @OA\Server(url="http://localhost")
 */

class ToolController extends Controller
{
    private ToolRepositoryInterface $toolRepository;

    public function __construct(ToolRepositoryInterface $toolRepository)
    {
        $this->toolRepository = $toolRepository;
    }

    /**
     * @OA\Get(
     *     path="/api/v1/tools",
     *     tags={"Tools"},
     *     summary="Get list of tools",
     *     description="Return list of tools",
     *     @OA\Parameter(
     *         name="tag",
     *         in="query",
     *         description="Tag to filter tools",
     *         required=false,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Succesful operation",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(ref="#/components/schemas/ToolResource")
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Unauthenticated."),
     *         )
     *     )
     * )
     */
    public function index(Request $request)
    {
        $tag = $request->tag;

        $tools = $this->toolRepository->index($tag);

        return response()->json(ToolResource::collection($tools));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * @OA\Post(
     *     path="/api/v1/tools",
     *     tags={"Tools"},
     *     summary="Create new tool",
     *     description="Create a new tool record",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"title", "link", "description", "tags"},
     *             @OA\Property(property="title", type="string", example="New Tool"),
     *             @OA\Property(property="link", type="string", example="http://example.com"),
     *             @OA\Property(property="description", type="string", example="Description of the tool"),
     *             @OA\Property(property="tags", type="array", @OA\Items(type="string", example="tag"))
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Record created successfully",
     *         @OA\JsonContent(ref="#/components/schemas/ToolResource")
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Unauthenticated."),
     *         )
     *     )
     * )
     */
    public function store(StoreToolRequest $request)
    {
        $data = [
            'title' => $request->title,
            'link' => $request->link,
            'description' => $request->description,
            'tags' => $request->tags,
        ];

        $tool = $this->toolRepository->store($data);

        return response()->json(new ToolResource($tool), 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Tool $tool)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Tool $tool)
    {
        //
    }

    /**
     * @OA\Put(
     *     path="/api/v1/tools/{id}",
     *     tags={"Tools"},
     *     summary="Update tool information",
     *     description="Update tool record by ID",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"title", "link", "description"},
     *             @OA\Property(property="title", type="string", example="Updated Tool"),
     *             @OA\Property(property="link", type="string", example="http://example.com"),
     *             @OA\Property(property="description", type="string", example="Updated description of the tool")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Record updated successfully",
     *         @OA\JsonContent(ref="#/components/schemas/ToolResource")
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Unauthenticated."),
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Toll not found",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Toll not found."),
     *         )
     *     )
     * )
     */
    public function update(UpdateToolRequest $request, string $toolId)
    {
        $data = [
            'title' => $request->title,
            'link' => $request->link,
            'description' => $request->description,
        ];

        $tool = $this->toolRepository->update($data, $toolId);

        if (!$tool) {
            return response()->json(['message' => 'Tool not found.'], 404);
        }

        return response()->json(new ToolResource($tool));
    }

    /**
     * @OA\Delete(
     *     path="/api/v1/tools/{id}",
     *     tags={"Tools"},
     *     summary="Delete tool record",
     *     description="Delete tool by ID",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Record deleted successfully"
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Unauthenticated."),
     *         )
     *     )
     * )
     */
    public function destroy(string $toolId)
    {
        $this->toolRepository->delete($toolId);

        return response()->json([], 204);
    }
}
