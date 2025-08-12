<?php

namespace App\Http\Resources\Web;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Carbon;

/**
 * Class NewsResource
 *
 * @property int $id The unique identifier for the news article.
 * @property string $title The title of the news article.
 * @property string $slug The slug of the news article.
 * @property string|null $short_description The short description or summary of the news.
 * @property string|null $long_description The long description or full content of the news.
 * @property string|null $attach The filename of the attached image.
 * @property string|null $image_url The full URL to the image.
 * @property string $meta_title The meta title of the news article.
 * @property string|null $meta_description The meta description or summary of the news.
 * @property string $date The date the news was published.
 * @property bool $status The status of the news article (true for active, false for inactive).
 * @property int|null $created_by The ID of the user who created the record.
 * @property string|null $created_by_name The name of the user who created the record.
 * @property int|null $updated_by The ID of the user who last updated the record.
 * @property string|null $updated_by_name The name of the user who last updated the record.
 * @property string $created_at The timestamp when the record was created.
 * @property string $updated_at The timestamp when the record was last updated.
 * @property string $deleted_at The timestamp when the record was last deleted.
 */
class NewsResource extends JsonResource
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
             * The unique identifier for the news article.
             * @var int $id
             * @example 1
             */
            'id' => $this->id,

            /**
             * The title of the news article.
             * @var string $title
             * @example "University Researchers Discover New Planet"
             */
            'title' => $this->title,

            /**
             * The URL-friendly slug for the news article.
             * @var string $slug
             * @example "university-researchers-discover-new-planet"
             */
            'slug' => $this->slug,

            /**
             * The short description or summary of the news.
             * @var string|null $short_description
             * @example "A team of astronomers at our university has identified a new exoplanet in the Kepler system."
             */
            'short_description' => $this->short_description,

            /**
             * The long description or full content of the news.
             * @var string|null $long_description
             * @example "In a groundbreaking discovery, Professor Anya Sharma and her team at the Department of Astrophysics have confirmed the existence of a previously unknown planet..."
             */
            'long_description' => $this->long_description,

            /**
             * The filename of the attached image.
             * @var string|null $attach
             * @example "new_planet_1642567890.jpg"
             */
            'attach' => $this->attach,

            /**
             * The full URL to the attached image.
             * @var string|null $image_url
             * @example "https://your-bucket.s3.amazonaws.com/news/new_planet_1642567890.jpg"
             */
            'image_url' => $this->image_url,

            /**
             * The SEO meta title for the news article.
             * @var string|null $meta_title
             * @example "New Planet Discovery | University News"
             */
            'meta_title' => $this->meta_title,

            /**
             * The SEO meta description for the news article.
             * @var string|null $meta_description
             * @example "University researchers discover a new exoplanet in the Kepler system..."
             */
            'meta_description' => $this->meta_description,

            /**
             * The date the news was published.
             * @var string $date
             * @example "2024-07-18"
             */
            'date' => Carbon::parse($this->date)->format('Y-m-d'),

            /**
             * The status of the news article (true for active, false for inactive).
             * @var bool $status
             * @example true
             */
            'status' => $this->status,

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
