<?php

namespace App\Http\Resources\System;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Carbon;

/**
 * Class PermissionResource
 *
 * @property int $id The unique identifier for the permission.
 * @property string $name The name of the permission.
 * @property string $group The group the permission belongs to.
 * @property string $title The display title of the permission.
 * @property string $guard_name The guard name for the permission.
 * @property string $created_at The timestamp when the record was created.
 * @property string $updated_at The timestamp when the record was last updated.
 */
class PermissionResource extends JsonResource
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
             * The unique identifier for the permission.
             * @var int $id
             * @example 1
             */
            'id' => $this->id,

            /**
             * The name of the permission.
             * @var string $name
             * @example "view-users"
             */
            'name' => $this->name,

            /**
             * The group the permission belongs to.
             * @var string $group
             * @example "User Management"
             */
            'group' => $this->group,

            /**
             * The display title of the permission.
             * @var string $title
             * @example "View Users"
             */
            'title' => $this->title,

            /**
             * The guard name for the permission.
             * @var string $guard_name
             * @example "web"
             */
            'guard_name' => $this->guard_name,

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
