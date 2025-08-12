<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Carbon;

class TransferCreditResource extends JsonResource
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
             * The unique identifier for the transfer credit.
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
             * @example "John Doe"
             */
            'student_name' => $this->whenLoaded('student', fn() => $this->student->user->name),

            /**
             * The ID of the associated subject.
             * @var int $subject_id
             * @example 5
             */
            'subject_id' => $this->subject_id,

            /**
             * The title of the associated subject.
             * @var string|null $subject_title
             * @example "Introduction to Psychology"
             */
            'subject_title' => $this->whenLoaded('subject', fn() => $this->subject->title),

            /**
             * The credit hours transferred.
             * @var float $credit_hour
             * @example 3.0
             */
            'credit_hour' => $this->credit_hour,

            /**
             * The institution from which the credit is transferred.
             * @var string|null $from_institution
             * @example "Community College A"
             */
            'from_institution' => $this->from_institution,

            /**
             * The date the credit was transferred.
             * @var string $transfer_date
             * @example "2023-09-01"
             */
            'transfer_date' => $this->transfer_date,

            /**
             * The status of the transfer credit (true for active, false for inactive).
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
