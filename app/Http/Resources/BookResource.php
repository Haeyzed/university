<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Carbon;

class BookResource extends JsonResource
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
             * The unique identifier for the book.
             * @var int $id
             * @example 1
             */
            'id' => $this->id,

            /**
             * The ID of the book category.
             * @var int $book_category_id
             * @example 1
             */
            'book_category_id' => $this->book_category_id,

            /**
             * The title of the book category.
             * @var string|null $book_category_title
             * @example "Fiction"
             */
            'book_category_title' => $this->whenLoaded('bookCategory', fn() => $this->bookCategory->title),

            /**
             * The title of the book.
             * @var string $title
             * @example "The Great Gatsby"
             */
            'title' => $this->title,

            /**
             * The author of the book.
             * @var string $author
             * @example "F. Scott Fitzgerald"
             */
            'author' => $this->author,

            /**
             * The publisher of the book.
             * @var string|null $publisher
             * @example "Scribner"
             */
            'publisher' => $this->publisher,

            /**
             * The ISBN number of the book.
             * @var string|null $isbn
             * @example "978-0743273565"
             */
            'isbn' => $this->isbn,

            /**
             * The price of the book.
             * @var float|null $price
             * @example 15.99
             */
            'price' => $this->price,

            /**
             * The quantity of the book available.
             * @var int|null $quantity
             * @example 10
             */
            'quantity' => $this->quantity,

            /**
             * The path to the book cover image.
             * @var string|null $cover_image
             * @example "/uploads/books/gatsby_cover.jpg"
             */
            'cover_image' => $this->cover_image,

            /**
             * The status of the book (true for available, false for unavailable).
             * @var bool $status
             * @example true
             */
            'status' => (bool)$this->status,

            /**
             * The ID of the user who created the book record.
             * @var int|null $created_by
             * @example 1
             */
            'created_by' => $this->created_by,

            /**
             * The name of the user who created the book record.
             * @var string|null $created_by_name
             * @example "Admin User"
             */
            'created_by_name' => $this->whenLoaded('createdBy', fn() => $this->createdBy->name),

            /**
             * The ID of the user who last updated the book record.
             * @var int|null $updated_by
             * @example 1
             */
            'updated_by' => $this->updated_by,

            /**
             * The name of the user who last updated the book record.
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
