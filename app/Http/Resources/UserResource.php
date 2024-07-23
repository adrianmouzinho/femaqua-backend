<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * @OA\Schema(
     *     schema="UserResource",
     *     type="object",
     *     @OA\Property(
     *         property="id",
     *         type="integer",
     *         description="Id of the user"
     *     ),
     * *   @OA\Property(
     *         property="name",
     *         type="string",
     *         description="Name of the user"
     *     ),
     * *   @OA\Property(
     *         property="email",
     *         type="string",
     *         description="Email of the user"
     *     ),
     * *   @OA\Property(
     *         property="created_at",
     *         type="date",
     *         description="Creation date of the user"
     *     ),
     * *   @OA\Property(
     *         property="updated_at",
     *         type="date",
     *         description="Updating date of the user"
     *     )
     * )
     */
    public function toArray(Request $request): array
    {
        return parent::toArray($request);
    }
}
