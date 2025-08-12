<?php

namespace App\Http\Resources\System;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Carbon;

/**
 * Class RoleResource
 *
 * @property int $id The unique identifier for the role.
 * @property string $name The name of the role.
 * @property string $slug The URL-friendly slug for the role.
 * @property string $guard_name The guard name for the role.
 * @property string $created_at The timestamp when the record was created.
 * @property string $updated_at The timestamp when the record was last updated.
 * @property-read \Illuminate\Database\Eloquent\Collection|\Spatie\Permission\Models\Permission[] $permissions The permissions assigned to this role.
 */
class RoleResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            /**
             * The unique identifier for the role.
             * @var int $id
             * @example 1
             */
            'id' => $this->id,

            /**
             * The name of the role.
             * @var string $name
             * @example "admin"
             */
            'name' => $this->name,

            /**
             * The URL-friendly slug for the role.
             * @var string $slug
             * @example "admin-role"
             */
            'slug' => $this->slug,

            /**
             * The guard name for the role.
             * @var string $guard_name
             * @example "web"
             */
            'guard_name' => $this->guard_name,

            /**
             * The permissions assigned to this role.
             * @var array<PermissionResource> $permissions
             */
            'permissions' => PermissionResource::collection($this->whenLoaded('permissions')),

            /**
             * The timestamp when the record was created.
             * @var string $created_at
             * @example "2024-07-19 12:00:00"
             */
            'created_at' => Carbon::parse($this->created_at)->format('Y-m-d H:i:s'),

            /**
             * The timestamp when the record was last updated.
             * @var string $updated_at
             * @example "2024-07-19 12:30:00"
             */
            'updated_at' => Carbon::parse($this->updated_at)->format('Y-m-d H:i:s'),
        ];
    }
}
