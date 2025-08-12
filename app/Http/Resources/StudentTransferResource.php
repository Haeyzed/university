<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Carbon;

class StudentTransferResource extends JsonResource
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
             * The unique identifier for the student transfer.
             * @var int $id
             * @example 1
             */
            'id' => $this->id,

            /**
             * The ID of the associated student.
             * @var int $student_id
             * @example 101
             */
            'student_id' => $this->student_id,

            /**
             * The name of the associated student.
             * @var string|null $student_name
             * @example "Alice Smith"
             */
            'student_name' => $this->whenLoaded('student', fn() => $this->student->user->name),

            /**
             * The ID of the source program.
             * @var int $from_program_id
             * @example 1
             */
            'from_program_id' => $this->from_program_id,

            /**
             * The title of the source program.
             * @var string|null $from_program_title
             * @example "Computer Science"
             */
            'from_program_title' => $this->whenLoaded('fromProgram', fn() => $this->fromProgram->title),

            /**
             * The ID of the destination program.
             * @var int $to_program_id
             * @example 2
             */
            'to_program_id' => $this->to_program_id,

            /**
             * The title of the destination program.
             * @var string|null $to_program_title
             * @example "Software Engineering"
             */
            'to_program_title' => $this->whenLoaded('toProgram', fn() => $this->toProgram->title),

            /**
             * The ID of the source session.
             * @var int $from_session_id
             * @example 2024
             */
            'from_session_id' => $this->from_session_id,

            /**
             * The title of the source session.
             * @var string|null $from_session_title
             * @example "2024-2025"
             */
            'from_session_title' => $this->whenLoaded('fromSession', fn() => $this->fromSession->title),

            /**
             * The ID of the destination session.
             * @var int $to_session_id
             * @example 2025
             */
            'to_session_id' => $this->to_session_id,

            /**
             * The title of the destination session.
             * @var string|null $to_session_title
             * @example "2025-2026"
             */
            'to_session_title' => $this->whenLoaded('toSession', fn() => $this->toSession->title),

            /**
             * The ID of the source semester.
             * @var int $from_semester_id
             * @example 1
             */
            'from_semester_id' => $this->from_semester_id,

            /**
             * The title of the source semester.
             * @var string|null $from_semester_title
             * @example "Fall Semester"
             */
            'from_semester_title' => $this->whenLoaded('fromSemester', fn() => $this->fromSemester->title),

            /**
             * The ID of the destination semester.
             * @var int $to_semester_id
             * @example 2
             */
            'to_semester_id' => $this->to_semester_id,

            /**
             * The title of the destination semester.
             * @var string|null $to_semester_title
             * @example "Spring Semester"
             */
            'to_semester_title' => $this->whenLoaded('toSemester', fn() => $this->toSemester->title),

            /**
             * The ID of the source section.
             * @var int $from_section_id
             * @example 1
             */
            'from_section_id' => $this->from_section_id,

            /**
             * The title of the source section.
             * @var string|null $from_section_title
             * @example "Section A"
             */
            'from_section_title' => $this->whenLoaded('fromSection', fn() => $this->fromSection->title),

            /**
             * The ID of the destination section.
             * @var int $to_section_id
             * @example 2
             */
            'to_section_id' => $this->to_section_id,

            /**
             * The title of the destination section.
             * @var string|null $to_section_title
             * @example "Section B"
             */
            'to_section_title' => $this->whenLoaded('toSection', fn() => $this->toSection->title),

            /**
             * The transfer date.
             * @var string $transfer_date
             * @example "2024-09-15"
             */
            'transfer_date' => $this->transfer_date,

            /**
             * The reason for the transfer.
             * @var string|null $reason
             * @example "Change of specialization interest."
             */
            'reason' => $this->reason,

            /**
             * The status of the student transfer (true for active, false for inactive).
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
