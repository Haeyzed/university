<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Carbon;

class EnrollSubjectResource extends JsonResource
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
             * The unique identifier for the enrolled subject.
             * @var int $id
             * @example 1
             */
            'id' => $this->id,

            /**
             * The ID of the associated student enrollment.
             * @var int $student_enroll_id
             * @example 1
             */
            'student_enroll_id' => $this->student_enroll_id,

            /**
             * The ID of the associated subject.
             * @var int $subject_id
             * @example 1
             */
            'subject_id' => $this->subject_id,

            /**
             * The title of the associated subject.
             * @var string|null $subject_title
             * @example "Calculus I"
             */
            'subject_title' => $this->whenLoaded('subject', fn() => $this->subject->title),

            /**
             * The ID of the associated teacher (user).
             * @var int $teacher_id
             * @example 201
             */
            'teacher_id' => $this->teacher_id,

            /**
             * The name of the associated teacher.
             * @var string|null $teacher_name
             * @example "Dr. John Smith"
             */
            'teacher_name' => $this->whenLoaded('teacher', fn() => $this->teacher->name),

            /**
             * The status of the enrolled subject (true for active, false for inactive).
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
