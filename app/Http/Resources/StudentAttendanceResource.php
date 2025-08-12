<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Carbon;

class StudentAttendanceResource extends JsonResource
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
             * The unique identifier for the student attendance record.
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
             * The ID of the associated program.
             * @var int $program_id
             * @example 1
             */
            'program_id' => $this->program_id,

            /**
             * The title of the associated program.
             * @var string|null $program_title
             * @example "Computer Science"
             */
            'program_title' => $this->whenLoaded('program', fn() => $this->program->title),

            /**
             * The ID of the associated session.
             * @var int $session_id
             * @example 2024
             */
            'session_id' => $this->session_id,

            /**
             * The title of the associated session.
             * @var string|null $session_title
             * @example "2024-2025"
             */
            'session_title' => $this->whenLoaded('session', fn() => $this->session->title),

            /**
             * The ID of the associated semester.
             * @var int $semester_id
             * @example 1
             */
            'semester_id' => $this->semester_id,

            /**
             * The title of the associated semester.
             * @var string|null $semester_title
             * @example "Fall Semester"
             */
            'semester_title' => $this->whenLoaded('semester', fn() => $this->semester->title),

            /**
             * The ID of the associated section.
             * @var int $section_id
             * @example 1
             */
            'section_id' => $this->section_id,

            /**
             * The title of the associated section.
             * @var string|null $section_title
             * @example "Section A"
             */
            'section_title' => $this->whenLoaded('section', fn() => $this->section->title),

            /**
             * The date of the attendance.
             * @var string $date
             * @example "2024-07-19"
             */
            'date' => $this->date,

            /**
             * The attendance status (e.g., 1 for present, 0 for absent).
             * @var int $attendance
             * @example 1
             */
            'attendance' => $this->attendance,

            /**
             * The status of the student attendance record (true for active, false for inactive).
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
