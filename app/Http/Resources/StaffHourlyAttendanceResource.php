<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Carbon;

class StaffHourlyAttendanceResource extends JsonResource
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
             * The unique identifier for the staff hourly attendance record.
             * @var int $id
             * @example 1
             */
            'id' => $this->id,

            /**
             * The ID of the associated user (staff member).
             * @var int $user_id
             * @example 201
             */
            'user_id' => $this->user_id,

            /**
             * The name of the associated user (staff member).
             * @var string|null $user_name
             * @example "Professor Jane"
             */
            'user_name' => $this->whenLoaded('user', fn() => $this->user->name),

            /**
             * The date of the attendance.
             * @var string $date
             * @example "2024-07-19"
             */
            'date' => $this->date,

            /**
             * The check-in time.
             * @var string|null $check_in
             * @example "08:55:00"
             */
            'check_in' => $this->check_in,

            /**
             * The check-out time.
             * @var string|null $check_out
             * @example "17:05:00"
             */
            'check_out' => $this->check_out,

            /**
             * The status of the staff hourly attendance record (true for active, false for inactive).
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
