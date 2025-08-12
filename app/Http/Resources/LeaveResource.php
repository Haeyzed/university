<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Carbon;

class LeaveResource extends JsonResource
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
             * The unique identifier for the leave request.
             * @var int $id
             * @example 1
             */
            'id' => $this->id,

            /**
             * The ID of the user requesting leave.
             * @var int $user_id
             * @example 101
             */
            'user_id' => $this->user_id,

            /**
             * The name of the user requesting leave.
             * @var string|null $user_name
             * @example "John Doe"
             */
            'user_name' => $this->whenLoaded('user', fn() => $this->user->name),

            /**
             * The ID of the leave type.
             * @var int $leave_type_id
             * @example 1
             */
            'leave_type_id' => $this->leave_type_id,

            /**
             * The title of the leave type.
             * @var string|null $leave_type_title
             * @example "Sick Leave"
             */
            'leave_type_title' => $this->whenLoaded('leaveType', fn() => $this->leaveType->title),

            /**
             * The start date of the leave.
             * @var string $start_date
             * @example "2024-07-20"
             */
            'start_date' => $this->start_date,

            /**
             * The end date of the leave.
             * @var string $end_date
             * @example "2024-07-22"
             */
            'end_date' => $this->end_date,

            /**
             * The reason for the leave.
             * @var string $reason
             * @example "Feeling unwell, need to rest."
             */
            'reason' => $this->reason,

            /**
             * The status of the leave request (e.g., 0 for pending, 1 for approved, 2 for rejected).
             * @var int $status
             * @example 0
             */
            'status' => $this->status,

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
