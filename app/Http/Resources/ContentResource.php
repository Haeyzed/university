<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Carbon;

class ContentResource extends JsonResource
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
             * The unique identifier for the content.
             * @var int $id
             * @example 1
             */
            'id' => $this->id,

            /**
             * The ID of the content type.
             * @var int $content_type_id
             * @example 1
             */
            'content_type_id' => $this->content_type_id,

            /**
             * The title of the content type.
             * @var string|null $content_type_title
             * @example "Article"
             */
            'content_type_title' => $this->whenLoaded('contentType', fn() => $this->contentType->title),

            /**
             * The title of the content.
             * @var string $title
             * @example "Introduction to Laravel"
             */
            'title' => $this->title,

            /**
             * The description or body of the content.
             * @var string|null $description
             * @example "This article provides a basic introduction to the Laravel framework..."
             */
            'description' => $this->description,

            /**
             * The path to the image associated with the content.
             * @var string|null $image
             * @example "/uploads/content/laravel_intro.jpg"
             */
            'image' => $this->image,

            /**
             * The status of the content (true for active, false for inactive).
             * @var bool $status
             * @example true
             */
            'status' => (bool)$this->status,

            /**
             * The ID of the user who created the content.
             * @var int|null $created_by
             * @example 1
             */
            'created_by' => $this->created_by,

            /**
             * The name of the user who created the content.
             * @var string|null $created_by_name
             * @example "Admin User"
             */
            'created_by_name' => $this->whenLoaded('createdBy', fn() => $this->createdBy->name),

            /**
             * The ID of the user who last updated the content.
             * @var int|null $updated_by
             * @example 1
             */
            'updated_by' => $this->updated_by,

            /**
             * The name of the user who last updated the content.
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
