<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Carbon;

class HostelRoomResource extends JsonResource
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
             * The unique identifier for the hostel room.
             * @var int $id
             * @example 1
             */
            'id' => $this->id,

            /**
             * The ID of the associated hostel.
             * @var int $hostel_id
             * @example 1
             */
            'hostel_id' => $this->hostel_id,

            /**
             * The title of the associated hostel.
             * @var string|null $hostel_title
             * @example "University Dorm A"
             */
            'hostel_title' => $this->whenLoaded('hostel', fn() => $this->hostel->title),

            /**
             * The ID of the hostel room type.
             * @var int $hostel_room_type_id
             * @example 1
             */
            'hostel_room_type_id' => $this->hostel_room_type_id,

            /**
             * The title of the hostel room type.
             * @var string|null $hostel_room_type_title
             * @example "Single Room"
             */
            'hostel_room_type_title' => $this->whenLoaded('hostelRoomType', fn() => $this->hostelRoomType->title),

            /**
             * The room number or title.
             * @var string $title
             * @example "Room 205"
             */
            'title' => $this->title,

            /**
             * The number of beds in the room.
             * @var int|null $no_of_bed
             * @example 1
             */
            'no_of_bed' => $this->no_of_bed,

            /**
             * The cost per bed.
             * @var float|null $cost_per_bed
             * @example 300.00
             */
            'cost_per_bed' => $this->cost_per_bed,

            /**
             * The status of the hostel room (true for active, false for inactive).
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
