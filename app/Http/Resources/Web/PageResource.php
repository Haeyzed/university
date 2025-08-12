<?php

namespace App\Http\Resources\Web;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Carbon;

/**
 * Class PageResource
 *
 * @property int $id The unique identifier for the page.
 * @property string $title The title of the page.
 * @property string $slug The URL-friendly slug for the page.
 * @property string|null $description The content of the page.
 * @property string|null $meta_title The SEO meta title for the page.
 * @property string|null $meta_description The SEO meta description for the page.
 * @property string|null $attach The filename of the attached image.
 * @property string|null $image_url The full URL to the image.
 * @property bool $status The status of the page (true for active, false for inactive).
 * @property int|null $created_by The ID of the user who created the record.
 * @property string|null $created_by_name The name of the user who created the record.
 * @property int|null $updated_by The ID of the user who last updated the record.
 * @property string|null $updated_by_name The name of the user who last updated the record.
 * @property string $created_at The timestamp when the record was created.
 * @property string $updated_at The timestamp when the record was last updated.
 * @property string $deleted_at The timestamp when the record was last deleted.
 */
class PageResource extends JsonResource
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
             * The unique identifier for the page.
             * @var int $id
             * @example 1
             */
            'id' => $this->id,

            /**
             * The title of the page.
             * @var string $title
             * @example "Admissions"
             */
            'title' => $this->title,

            /**
             * The slug for the page URL.
             * @var string $slug
             * @example "admissions"
             */
            'slug' => $this->slug,

            /**
             * The content of the page.
             * @var string|null $description
             * @example "Information about the admission process, requirements, and deadlines."
             */
            'description' => $this->description,

            /**
             * The SEO meta title for the page.
             * @var string|null $meta_title
             * @example "Admissions | University Pages"
             */
            'meta_title' => $this->meta_title,

            /**
             * The SEO meta description for the page.
             * @var string|null $meta_description
             * @example "Find out how to apply to our university, including requirements and deadlines."
             */
            'meta_description' => $this->meta_description,

            /**
             * The filename of the attached image.
             * @var string|null $attach
             * @example "admissions_banner.jpg"
             */
            'attach' => $this->attach,

            /**
             * The full URL to the attached image.
             * @var string|null $image_url
             * @example "https://your-bucket.s3.amazonaws.com/page/admissions_banner.jpg"
             */
            'image_url' => $this->image_url,

            /**
             * The status of the page (true for active, false for inactive).
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
