<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Carbon;

class MeetingScheduleResource extends JsonResource
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
             * The unique identifier for the meeting schedule.
             * @var int $id
             * @example 1
             */
            'id' => $this->id,

            /**
             * The ID of the meeting type.
             * @var int $meeting_type_id
             * @example 1
             */
            'meeting_type_id' => $this->meeting_type_id,

            /**
             * The title of the meeting type.
             * @var string|null $meeting_type_title
             * @example "Department Meeting"
             */
            'meeting_type_title' => $this->whenLoaded('meetingType', fn() => $this->meetingType->title),

            /**
             * The title of the meeting.
             * @var string $title
             * @example "Quarterly Faculty Meeting"
             */
            'title' => $this->title,

            /**
             * The description or agenda of the meeting.
             * @var string|null $description
             * @example "Discussion on curriculum updates and student performance."
             */
            'description' => $this->description,

            /**
             * The date of the meeting.
             * @var string $date
             * @example "2024-08-01"
             */
            'date' => $this->date,

            /**
             * The start time of the meeting.
             * @var string $start_time
             * @example "10:00:00"
             */
            'start_time' => $this->start_time,

            /**
             * The end time of the meeting.
             * @var string $end_time
             * @example "11:30:00"
             */
            'end_time' => $this->end_time,

            /**
             * The location of the meeting.
             * @var string|null $location
             * @example "Conference Room A"
             */
            'location' => $this->location,

            /**
             * The status of the meeting schedule (true for active, false for inactive).
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
