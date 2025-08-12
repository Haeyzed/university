<?php

namespace App\Http\Resources\Web;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Carbon;

/**
 * Class AboutUsResource
 *
 * @property int $id The unique identifier for the About Us entry.
 * @property string|null $label The label for the About Us section.
 * @property string|null $title The main title for the About Us section.
 * @property string|null $short_desc A short description for the About Us section.
 * @property string|null $description The full description for the About Us section.
 * @property array|null $features JSON encoded array of features.
 * @property string|null $attach The filename of the main attached image.
 * @property string|null $attach_image_url The full URL to the main image.
 * @property string|null $video_id The YouTube video ID to embed.
 * @property string|null $youtube_embed_url The YouTube embed URL.
 * @property string|null $button_text The text for the call-to-action button.
 * @property string|null $mission_title The title for the Mission section.
 * @property string|null $mission_desc The description for the Mission section.
 * @property string|null $mission_icon The icon for the Mission section.
 * @property string|null $mission_image The filename of the Mission image.
 * @property string|null $mission_image_url The full URL to the Mission image.
 * @property string|null $vision_title The title for the Vision section.
 * @property string|null $vision_desc The description for the Vision section.
 * @property string|null $vision_icon The icon for the Vision section.
 * @property string|null $vision_image The filename of the Vision image.
 * @property string|null $vision_image_url The full URL to the Vision image.
 * @property bool $status The status of the About Us entry (true for active, false for inactive).
 * @property int|null $created_by The ID of the user who created the record.
 * @property string|null $created_by_name The name of the user who created the record.
 * @property int|null $updated_by The ID of the user who last updated the record.
 * @property string|null $updated_by_name The name of the user who last updated the record.
 * @property string $created_at The timestamp when the record was created.
 * @property string $updated_at The timestamp when the record was last updated.
 * @property string $deleted_at The timestamp when the record was last deleted.
 */
class AboutUsResource extends JsonResource
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
             * The unique identifier for the About Us entry.
             * @var int $id
             * @example 1
             */
            'id' => $this->id,

            /**
             * The label for the About Us section.
             * @var string|null $label
             * @example "Our Story"
             */
            'label' => $this->label,

            /**
             * The main title for the About Us section.
             * @var string|null $title
             * @example "About Our University"
             */
            'title' => $this->title,

            /**
             * A short description for the About Us section.
             * @var string|null $short_desc
             * @example "Discover our journey and values that shape our institution."
             */
            'short_desc' => $this->short_desc,

            /**
             * The full description for the About Us section.
             * @var string|null $description
             * @example "Our university has a rich history spanning over decades of academic excellence..."
             */
            'description' => $this->description,

            /**
             * Array of features to highlight.
             * @var array|null $features
             * @example ["Experienced Faculty", "Modern Facilities", "Research Excellence"]
             */
            'features' => $this->features,

            /**
             * The filename of the main attached image.
             * @var string|null $attach
             * @example "about_us_main_1642567890.jpg"
             */
            'attach' => $this->attach,

            /**
             * The full URL to the main attached image.
             * @var string|null $attach_image_url
             * @example "https://your-bucket.s3.amazonaws.com/about-us/about_us_main_1642567890.jpg"
             */
            'attach_image_url' => $this->attach_image_url,

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
             * The text for the call-to-action button.
             * @var string|null $button_text
             * @example "Learn More About Us"
             */
            'button_text' => $this->button_text,

            /**
             * Mission section data.
             * @var array|null $mission
             */
            'mission' => $this->when(
                $this->mission_title || $this->mission_desc || $this->mission_image || $this->mission_icon,
                [
                    /**
                     * The title for the Mission section.
                     * @var string|null $title
                     * @example "Our Mission"
                     */
                    'title' => $this->mission_title,

                    /**
                     * The description for the Mission section.
                     * @var string|null $description
                     * @example "To provide quality education and foster innovation in our community."
                     */
                    'description' => $this->mission_desc,

                    /**
                     * The icon for the Mission section.
                     * @var string|null $icon
                     * @example "fas fa-bullseye"
                     */
                    'icon' => $this->mission_icon,

                    /**
                     * The filename of the Mission image.
                     * @var string|null $image
                     * @example "mission_image_1642567890.jpg"
                     */
                    'image' => $this->mission_image,

                    /**
                     * The full URL to the Mission image.
                     * @var string|null $image_url
                     * @example "https://your-bucket.s3.amazonaws.com/about-us/mission/mission_image_1642567890.jpg"
                     */
                    'image_url' => $this->mission_image_url,
                ]
            ),

            /**
             * Vision section data.
             * @var array|null $vision
             */
            'vision' => $this->when(
                $this->vision_title || $this->vision_desc || $this->vision_image || $this->vision_icon,
                [
                    /**
                     * The title for the Vision section.
                     * @var string|null $title
                     * @example "Our Vision"
                     */
                    'title' => $this->vision_title,

                    /**
                     * The description for the Vision section.
                     * @var string|null $description
                     * @example "To be a leading institution in higher education and research excellence."
                     */
                    'description' => $this->vision_desc,

                    /**
                     * The icon for the Vision section.
                     * @var string|null $icon
                     * @example "fas fa-eye"
                     */
                    'icon' => $this->vision_icon,

                    /**
                     * The filename of the Vision image.
                     * @var string|null $image
                     * @example "vision_image_1642567890.jpg"
                     */
                    'image' => $this->vision_image,

                    /**
                     * The full URL to the Vision image.
                     * @var string|null $image_url
                     * @example "https://your-bucket.s3.amazonaws.com/about-us/vision/vision_image_1642567890.jpg"
                     */
                    'image_url' => $this->vision_image_url,
                ]
            ),

            /**
             * The status of the About Us entry (true for active, false for inactive).
             * @var bool $status
             * @example true
             */
            'status' => (bool)$this->status,

            /**
             * Metadata about the record.
             * @var array $meta
             */
//            'meta' => [
//                /**
//                 * Whether the content has features.
//                 * @var bool $has_features
//                 * @example true
//                 */
//                'has_features' => !empty($this->features) && is_array($this->features),
//
//                /**
//                 * The number of features.
//                 * @var int $features_count
//                 * @example 3
//                 */
//                'features_count' => is_array($this->features) ? count($this->features) : 0,
//
//                /**
//                 * Whether the content has a video.
//                 * @var bool $has_video
//                 * @example true
//                 */
//                'has_video' => !empty($this->video_id),
//
//                /**
//                 * Whether the content has a mission section.
//                 * @var bool $has_mission
//                 * @example true
//                 */
//                'has_mission' => !empty($this->mission_title) || !empty($this->mission_desc),
//
//                /**
//                 * Whether the content has a vision section.
//                 * @var bool $has_vision
//                 * @example true
//                 */
//                'has_vision' => !empty($this->vision_title) || !empty($this->vision_desc),
//
//                /**
//                 * Whether the content has images.
//                 * @var bool $has_images
//                 * @example true
//                 */
//                'has_images' => !empty($this->attach) || !empty($this->mission_image) || !empty($this->vision_image),
//            ],

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
