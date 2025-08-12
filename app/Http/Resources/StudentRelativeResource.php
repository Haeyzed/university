<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Carbon;

class StudentRelativeResource extends JsonResource
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
             * The unique identifier for the student relative.
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
             * The name of the relative.
             * @var string $name
             * @example "Robert Smith"
             */
            'name' => $this->name,

            /**
             * The relation to the student (e.g., "Father", "Mother").
             * @var string $relation
             * @example "Father"
             */
            'relation' => $this->relation,

            /**
             * The occupation of the relative.
             * @var string|null $occupation
             * @example "Engineer"
             */
            'occupation' => $this->occupation,

            /**
             * The phone number of the relative.
             * @var string|null $phone
             * @example "+14161112222"
             */
            'phone' => $this->phone,

            /**
             * The email address of the relative.
             * @var string|null $email
             * @example "robert.smith@example.com"
             */
            'email' => $this->email,

            /**
             * The address of the relative.
             * @var string|null $address
             * @example "456 Elm St, Unit 10, Toronto"
             */
            'address' => $this->address,

            /**
             * The path to the relative's photo.
             * @var string|null $photo
             * @example "/uploads/relatives/robert_smith.jpg"
             */
            'photo' => $this->photo,

            /**
             * The status of the student relative record (true for active, false for inactive).
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
