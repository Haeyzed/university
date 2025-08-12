<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Carbon;

class LibraryMemberResource extends JsonResource
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
             * The unique identifier for the library member.
             * @var int $id
             * @example 1
             */
            'id' => $this->id,

            /**
             * The ID of the associated user (student or staff).
             * @var int $user_id
             * @example 101
             */
            'user_id' => $this->user_id,

            /**
             * The name of the associated user.
             * @var string|null $user_name
             * @example "Student Name"
             */
            'user_name' => $this->whenLoaded('user', fn() => $this->user->name),

            /**
             * The library card number.
             * @var string $library_card_no
             * @example "LIB-001-2024"
             */
            'library_card_no' => $this->library_card_no,

            /**
             * The issue date of the library card.
             * @var string $issue_date
             * @example "2024-09-01"
             */
            'issue_date' => $this->issue_date,

            /**
             * The expiry date of the library card.
             * @var string|null $expiry_date
             * @example "2025-08-31"
             */
            'expiry_date' => $this->expiry_date,

            /**
             * The status of the library member (true for active, false for inactive).
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
