<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Carbon;

class BookRequestResource extends JsonResource
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
             * The unique identifier for the book request.
             * @var int $id
             * @example 1
             */
            'id' => $this->id,

            /**
             * The ID of the associated book.
             * @var int $book_id
             * @example 1
             */
            'book_id' => $this->book_id,

            /**
             * The title of the associated book.
             * @var string|null $book_title
             * @example "The Lord of the Rings"
             */
            'book_title' => $this->whenLoaded('book', fn() => $this->book->title),

            /**
             * The ID of the user who requested the book.
             * @var int $user_id
             * @example 101
             */
            'user_id' => $this->user_id,

            /**
             * The name of the user who requested the book.
             * @var string|null $user_name
             * @example "Student Name"
             */
            'user_name' => $this->whenLoaded('user', fn() => $this->user->name),

            /**
             * The date the book was requested.
             * @var string $request_date
             * @example "2024-07-19"
             */
            'request_date' => $this->request_date,

            /**
             * The due date for returning the book.
             * @var string|null $due_date
             * @example "2024-08-02"
             */
            'due_date' => $this->due_date,

            /**
             * The status of the book request (e.g., 0 for pending, 1 for approved, 2 for rejected).
             * @var int $status
             * @example 0
             */
            'status' => $this->status,

            /**
             * The ID of the user who created the book request.
             * @var int|null $created_by
             * @example 1
             */
            'created_by' => $this->created_by,

            /**
             * The name of the user who created the book request.
             * @var string|null $created_by_name
             * @example "Admin User"
             */
            'created_by_name' => $this->whenLoaded('createdBy', fn() => $this->createdBy->name),

            /**
             * The ID of the user who last updated the book request.
             * @var int|null $updated_by
             * @example 1
             */
            'updated_by' => $this->updated_by,

            /**
             * The name of the user who last updated the book request.
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
