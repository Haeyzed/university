<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Carbon;

class FeesMasterResource extends JsonResource
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
             * The unique identifier for the fees master record.
             * @var int $id
             * @example 1
             */
            'id' => $this->id,

            /**
             * The ID of the associated fees category.
             * @var int $fees_category_id
             * @example 1
             */
            'fees_category_id' => $this->fees_category_id,

            /**
             * The title of the associated fees category.
             * @var string|null $fees_category_title
             * @example "Tuition Fees"
             */
            'fees_category_title' => $this->whenLoaded('feesCategory', fn() => $this->feesCategory->title),

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
             * The amount of the fee.
             * @var float $amount
             * @example 1500.00
             */
            'amount' => $this->amount,

            /**
             * The due date for the fee.
             * @var string $due_date
             * @example "2024-09-01"
             */
            'due_date' => $this->due_date,

            /**
             * The status of the fees master record (true for active, false for inactive).
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
