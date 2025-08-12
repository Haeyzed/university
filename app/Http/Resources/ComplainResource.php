<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Carbon;

class ComplainResource extends JsonResource
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
             * The unique identifier for the complain.
             * @var int $id
             * @example 1
             */
            'id' => $this->id,

            /**
             * The ID of the user who filed the complain.
             * @var int $user_id
             * @example 101
             */
            'user_id' => $this->user_id,

            /**
             * The name of the user who filed the complain.
             * @var string|null $user_name
             * @example "Student Name"
             */
            'user_name' => $this->whenLoaded('user', fn() => $this->user->name),

            /**
             * The ID of the complain type.
             * @var int $complain_type_id
             * @example 1
             */
            'complain_type_id' => $this->complain_type_id,

            /**
             * The title of the complain type.
             * @var string|null $complain_type_title
             * @example "Academic Issue"
             */
            'complain_type_title' => $this->whenLoaded('complainType', fn() => $this->complainType->title),

            /**
             * The ID of the complain source.
             * @var int $complain_source_id
             * @example 1
             */
            'complain_source_id' => $this->complain_source_id,

            /**
             * The title of the complain source.
             * @var string|null $complain_source_title
             * @example "Online Form"
             */
            'complain_source_title' => $this->whenLoaded('complainSource', fn() => $this->complainSource->title),

            /**
             * The subject of the complain.
             * @var string $subject
             * @example "Issue with grading"
             */
            'subject' => $this->subject,

            /**
             * The description of the complain.
             * @var string $description
             * @example "My grade for the last assignment is incorrect. I believe there was a calculation error."
             */
            'description' => $this->description,

            /**
             * The date the complain was filed.
             * @var string $date
             * @example "2024-07-18"
             */
            'date' => $this->date,

            /**
             * The status of the complain (e.g., 0 for pending, 1 for resolved).
             * @var int $status
             * @example 0
             */
            'status' => $this->status,

            /**
             * The ID of the user who created the complain.
             * @var int|null $created_by
             * @example 1
             */
            'created_by' => $this->created_by,

            /**
             * The name of the user who created the complain.
             * @var string|null $created_by_name
             * @example "Admin User"
             */
            'created_by_name' => $this->whenLoaded('createdBy', fn() => $this->createdBy->name),

            /**
             * The ID of the user who last updated the complain.
             * @var int|null $updated_by
             * @example 1
             */
            'updated_by' => $this->updated_by,

            /**
             * The name of the user who last updated the complain.
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
