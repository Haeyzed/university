<?php

namespace App\Http\Resources\Web;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Carbon;

/**
 * Class CallToActionResource
 *
 * @property int $id The unique identifier for the Call to Action.
 * @property string $title The title of the Call to Action.
 * @property string|null $sub_title The subtitle or description of the Call to Action.
 * @property string|null $button_text The text displayed on the Call to Action button.
 * @property string|null $button_link The URL or link for the Call to Action button.
 * @property string|null $image The filename of the main image.
 * @property string|null $image_url The full URL to the main image.
 * @property string|null $bg_image The filename of the background image.
 * @property string|null $bg_image_url The full URL to the background image.
 * @property string|null $video_id The YouTube video ID to embed.
 * @property string|null $youtube_embed_url The YouTube embed URL.
 * @property string|null $youtube_thumbnail_url The YouTube thumbnail URL (high quality).
 * @property bool $status The status of the Call to Action (true for active, false for inactive).
 * @property int|null $created_by The ID of the user who created the record.
 * @property string|null $created_by_name The name of the user who created the record.
 * @property int|null $updated_by The ID of the user who last updated the record.
 * @property string|null $updated_by_name The name of the user who last updated the record.
 * @property string $created_at The timestamp when the record was created.
 * @property string $updated_at The timestamp when the record was last updated.
 * @property string|null $deleted_at The timestamp when the record was deleted, if applicable.
 */
class CallToActionResource extends JsonResource
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
             * The unique identifier for the Call to Action.
             * @var int $id
             * @example 1
             */
            'id' => $this->id,

            /**
             * The title of the Call to Action.
             * @var string $title
             * @example "Enroll Now!"
             */
            'title' => $this->title,

            /**
             * The description or subtitle of the Call to Action.
             * @var string|null $sub_title
             * @example "Limited seats available for the upcoming academic year."
             */
            'sub_title' => $this->sub_title,

            /**
             * The text displayed on the Call to Action button.
             * @var string|null $button_text
             * @example "Apply Today"
             */
            'button_text' => $this->button_text,

            /**
             * The URL or link for the Call to Action button.
             * @var string|null $button_link
             * @example "/admissions/apply"
             */
            'button_link' => $this->button_link,

            /**
             * The filename of the main image.
             * @var string|null $image
             * @example "enroll_banner.jpg"
             */
            'image' => $this->image,

            /**
             * The full URL to the main image.
             * @var string|null $image_url
             * @example "https://your-bucket.s3.amazonaws.com/call-to-action/enroll_banner.jpg"
             */
            'image_url' => $this->image_url,

            /**
             * The filename of the background image.
             * @var string|null $bg_image
             * @example "cta_bg.jpg"
             */
            'bg_image' => $this->bg_image,

            /**
             * The full URL to the background image.
             * @var string|null $bg_image_url
             * @example "https://your-bucket.s3.amazonaws.com/call-to-action/cta_bg.jpg"
             */
            'bg_image_url' => $this->bg_image_url,

            /**
             * The YouTube video ID to embed.
             * @var string|null $video_id
             * @example "dQw4w9WgXcQ"
             */
            'video_id' => $this->video_id,

            /**
             * The YouTube embed URL.
             * @var string|null $youtube_embed_url
             * @example "https://www.youtube.com/embed/dQw4w9WgXcQ"
             */
            'youtube_embed_url' => $this->youtube_embed_url,

            /**
             * The YouTube thumbnail URL (high quality).
             * @var string|null $youtube_thumbnail_url
             * @example "https://img.youtube.com/vi/dQw4w9WgXcQ/hqdefault.jpg"
             */
            'youtube_thumbnail_url' => $this->video_id ? $this->getYoutubeThumbnailUrl('hqdefault') : null,

            /**
             * The status of the Call to Action (true for active, false for inactive).
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
