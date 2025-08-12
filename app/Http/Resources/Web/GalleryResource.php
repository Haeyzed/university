<?php

namespace App\Http\Resources\Web;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Carbon;

/**
 * Class GalleryResource
 *
 * @property int $id The unique identifier for the gallery entry.
 * @property string|null $title The title of the gallery item.
 * @property string|null $description The description of the gallery item.
 * @property string|null $attach The filename of the attached image.
 * @property string|null $image_url The full URL to the image.
 * @property bool $status The status of the gallery entry (true for active, false for inactive).
 * @property int|null $created_by The ID of the user who created the record.
 * @property string|null $created_by_name The name of the user who created the record.
 * @property int|null $updated_by The ID of the user who last updated the record.
 * @property string|null $updated_by_name The name of the user who last updated the record.
 * @property string $created_at The timestamp when the record was created.
 * @property string $updated_at The timestamp when the record was last updated.
 * @property string $deleted_at The timestamp when the record was last deleted.
 */
class GalleryResource extends JsonResource
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
             * The unique identifier for the gallery entry.
             * @var int $id
             * @example 1
             */
            'id' => $this->id,

            /**
             * The title of the gallery item.
             * @var string|null $title
             * @example "Campus Life"
             */
            'title' => $this->title,

            /**
             * The description of the gallery item.
             * @var string|null $description
             * @example "Photos capturing the vibrant student life on campus."
             */
            'description' => $this->description,

            /**
             * The filename of the attached image.
             * @var string|null $attach
             * @example "campus_life_01.jpg"
             */
            'attach' => $this->attach,

            /**
             * The full URL to the attached image.
             * @var string|null $image_url
             * @example "https://your-bucket.s3.amazonaws.com/gallery/campus_life_01.jpg"
             */
            'image_url' => $this->image_url,

            /**
             * The status of the gallery entry (true for active, false for inactive).
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

            /**
             * The timestamp when the record was last deleted.
             * @var string|null $deleted_at
             * @example "2024-07-19 12:30:00"
             */
            'deleted_at' => $this->deleted_at ? Carbon::parse($this->deleted_at)->format('Y-m-d H:i:s') : null,
        ];
    }
}
