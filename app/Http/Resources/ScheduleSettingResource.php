<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Carbon;

class ScheduleSettingResource extends JsonResource
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
             * The unique identifier for the schedule setting.
             * @var int $id
             * @example 1
             */
            'id' => $this->id,

            /**
             * The title of the schedule setting.
             * @var string $title
             * @example "Default Class Schedule"
             */
            'title' => $this->title,

            /**
             * The start time of the academic day.
             * @var string $start_time
             * @example "08:00:00"
             */
            'start_time' => $this->start_time,

            /**
             * The end time of the academic day.
             * @var string $end_time
             * @example "17:00:00"
             */
            'end_time' => $this->end_time,

            /**
             * The duration of each class in minutes.
             * @var int $class_duration
             * @example 60
             */
            'class_duration' => $this->class_duration,

            /**
             * The break duration between classes in minutes.
             * @var int $break_duration
             * @example 10
             */
            'break_duration' => $this->break_duration,

            /**
             * The status of the schedule setting (true for active, false for inactive).
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
