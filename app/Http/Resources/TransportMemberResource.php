<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Carbon;

class TransportMemberResource extends JsonResource
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
             * The unique identifier for the transport member.
             * @var int $id
             * @example 1
             */
            'id' => $this->id,

            /**
             * The ID of the associated user (student or staff).
             * @var int $user_id
             * @example 101
             */
            'user_id' => $this->user_id,

            /**
             * The name of the associated user.
             * @var string|null $user_name
             * @example "Student Name"
             */
            'user_name' => $this->whenLoaded('user', fn() => $this->user->name),

            /**
             * The ID of the associated transport route.
             * @var int $transport_route_id
             * @example 1
             */
            'transport_route_id' => $this->transport_route_id,

            /**
             * The title of the associated transport route.
             * @var string|null $transport_route_title
             * @example "Route A"
             */
            'transport_route_title' => $this->whenLoaded('transportRoute', fn() => $this->transportRoute->title),

            /**
             * The ID of the associated transport vehicle.
             * @var int $transport_vehicle_id
             * @example 1
             */
            'transport_vehicle_id' => $this->transport_vehicle_id,

            /**
             * The name of the associated transport vehicle.
             * @var string|null $transport_vehicle_name
             * @example "Bus 1"
             */
            'transport_vehicle_name' => $this->whenLoaded('transportVehicle', fn() => $this->transportVehicle->name),

            /**
             * The join date for transport service.
             * @var string $join_date
             * @example "2024-09-01"
             */
            'join_date' => $this->join_date,

            /**
             * The status of the transport member (true for active, false for inactive).
             * @var bool $status
             * @example true
             */
            'status' => (bool)$this->status,

            /**
             * The ID of the user who created the record.
             * @var int|null $created_by
             * @example 1
             */
            'created_by' => $this->created_by,

            /**
             * The name of the user who created the record.
             * @var string|null $created_by_name
             * @example "Admin User"
             */
            'created_by_name' => $this->whenLoaded('createdBy', fn() => $this->createdBy->name),

            /**
             * The ID of the user who last updated the record.
             * @var int|null $updated_by
             * @example 1
             */
            'updated_by' => $this->updated_by,

            /**
             * The name of the user who last updated the record.
             * @var string|null $updated_by_name
             * @example "Admin User"
             */
            'updated_by_name' => $this->whenLoaded('updatedBy', fn() => $this->updatedBy->name),

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
