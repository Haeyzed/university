<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Carbon;

class SubjectMarkingResource extends JsonResource
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
             * The unique identifier for the subject marking.
             * @var int $id
             * @example 1
             */
            'id' => $this->id,

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
             * The ID of the associated exam type.
             * @var int $exam_type_id
             * @example 1
             */
            'exam_type_id' => $this->exam_type_id,

            /**
             * The title of the associated exam type.
             * @var string|null $exam_type_title
             * @example "Midterm Exam"
             */
            'exam_type_title' => $this->whenLoaded('examType', fn() => $this->examType->title),

            /**
             * The total marks for this subject marking.
             * @var float $total_marks
             * @example 100.0
             */
            'total_marks' => $this->total_marks,

            /**
             * The pass marks for this subject marking.
             * @var float $pass_marks
             * @example 50.0
             */
            'pass_marks' => $this->pass_marks,

            /**
             * The status of the subject marking (true for active, false for inactive).
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
