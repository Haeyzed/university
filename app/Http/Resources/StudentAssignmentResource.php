<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Carbon;

class StudentAssignmentResource extends JsonResource
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
             * The unique identifier for the student assignment.
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
             * The ID of the associated assignment.
             * @var int $assignment_id
             * @example 1
             */
            'assignment_id' => $this->assignment_id,

            /**
             * The title of the associated assignment.
             * @var string|null $assignment_title
             * @example "Midterm Project"
             */
            'assignment_title' => $this->whenLoaded('assignment', fn() => $this->assignment->title),

            /**
             * The path to the submitted assignment file.
             * @var string|null $file
             * @example "/uploads/student_assignments/alice_project.pdf"
             */
            'file' => $this->file,

            /**
             * The submission date of the assignment.
             * @var string $submission_date
             * @example "2024-08-14"
             */
            'submission_date' => $this->submission_date,

            /**
             * The marks obtained for the assignment.
             * @var float|null $marks
             * @example 85.5
             */
            'marks' => $this->marks,

            /**
             * The status of the student assignment (e.g., 0 for pending, 1 for submitted, 2 for graded).
             * @var int $status
             * @example 1
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
