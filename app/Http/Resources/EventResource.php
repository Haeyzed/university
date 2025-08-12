<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Carbon;

class EventResource extends JsonResource
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
             * The unique identifier for the event.
             * @var int $id
             * @example 1
             */
            'id' => $this->id,

            /**
             * The title of the event.
             * @var string $title
             * @example "Annual Sports Day"
             */
            'title' => $this->title,

            /**
             * The description of the event.
             * @var string|null $description
             * @example "Join us for a day of athletic competitions and fun activities."
             */
            'description' => $this->description,

            /**
             * The start date of the event.
             * @var string $start_date
             * @example "2024-10-20"
             */
            'start_date' => $this->start_date,

            /**
             * The end date of the event.
             * @var string $end_date
             * @example "2024-10-20"
             */
            'end_date' => $this->end_date,

            /**
             * The start time of the event.
             * @var string|null $start_time
             * @example "09:00:00"
             */
            'start_time' => $this->start_time,

            /**
             * The end time of the event.
             * @var string|null $end_time
             * @example "17:00:00"
             */
            'end_time' => $this->end_time,

            /**
             * The location of the event.
             * @var string|null $location
             * @example "University Stadium"
             */
            'location' => $this->location,

            /**
             * The path to the image associated with the event.
             * @var string|null $image
             * @example "/uploads/events/sports_day.jpg"
             */
            'image' => $this->image,

            /**
             * The status of the event (true for active, false for inactive).
             * @var bool $status
             * @example true
             */
            'status' => (bool)$this->status,

            /**
             * The ID of the user who created the event.
             * @var int|null $created_by
             * @example 1
             */
            'created_by' => $this->created_by,

            /**
             * The name of the user who created the event.
             * @var string|null $created_by_name
             * @example "Admin User"
             */
            'created_by_name' => $this->whenLoaded('createdBy', fn() => $this->createdBy->name),

            /**
             * The ID of the user who last updated the event.
             * @var int|null $updated_by
             * @example 1
             */
            'updated_by' => $this->updated_by,

            /**
             * The name of the user who last updated the event.
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
