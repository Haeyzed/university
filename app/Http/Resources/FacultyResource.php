<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Carbon;

class FacultyResource extends JsonResource
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
             * The unique identifier for the faculty member.
             * @var int $id
             * @example 1
             */
            'id' => $this->id,

            /**
             * The name of the faculty member.
             * @var string $name
             * @example "Dr. Emily White"
             */
            'name' => $this->name,

            /**
             * The designation of the faculty member.
             * @var string|null $designation
             * @example "Associate Professor"
             */
            'designation' => $this->designation,

            /**
             * The department of the faculty member.
             * @var string|null $department
             * @example "Computer Science"
             */
            'department' => $this->department,

            /**
             * The email address of the faculty member.
             * @var string|null $email
             * @example "emily.white@example.com"
             */
            'email' => $this->email,

            /**
             * The phone number of the faculty member.
             * @var string|null $phone
             * @example "+1122334455"
             */
            'phone' => $this->phone,

            /**
             * The path to the faculty member's photo.
             * @var string|null $photo
             * @example "/uploads/faculty/emily_white.jpg"
             */
            'photo' => $this->photo,

            /**
             * The status of the faculty member (true for active, false for inactive).
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
