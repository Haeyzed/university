<?php

namespace App\Http\Resources\Web;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Storage;

/**
 * Class SliderResource
 *
 * @property int $id The unique identifier for the slider.
 * @property string $title The title of the slider.
 * @property string|null $sub_title The subtitle of the slider.
 * @property string|null $button_text The text displayed on the slider button.
 * @property string|null $button_link The URL or link for the slider button.
 * @property string|null $attach The filename of the attached image.
 * @property string|null $image_url The full URL to the image.
 * @property bool $status The status of the slider (true for active, false for inactive).
 * @property int|null $created_by The ID of the user who created the record.
 * @property string|null $created_by_name The name of the user who created the record.
 * @property int|null $updated_by The ID of the user who last updated the record.
 * @property string|null $updated_by_name The name of the user who last updated the record.
 * @property string $created_at The timestamp when the record was created.
 * @property string $updated_at The timestamp when the record was last updated.
 * @property string $deleted_at The timestamp when the record was last deleted.
 */
class SliderResource extends JsonResource
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
             * The unique identifier for the slider.
             * @var int $id
             * @example 1
             */
            'id' => $this->id,

            /**
             * The title of the slider.
             * @var string $title
             * @example "Welcome to Our Campus"
             */
            'title' => $this->title,

            /**
             * The subtitle of the slider.
             * @var string|null $sub_title
             * @example "Explore our vibrant academic environment and state-of-the-art facilities."
             */
            'sub_title' => $this->sub_title,

            /**
             * The text displayed on the slider button.
             * @var string|null $button_text
             * @example "Learn More"
             */
            'button_text' => $this->button_text,

            /**
             * The URL or link for the slider button.
             * @var string|null $button_link
             * @example "/about"
             */
            'button_link' => $this->button_link,

            /**
             * The filename of the attached image.
             * @var string|null $attach
             * @example "campus_view.jpg"
             */
            'attach' => $this->attach,

            /**
             * The full URL to the attached image.
             * @var string|null $image_url
             * @example "https://your-bucket.s3.amazonaws.com/slider/campus_view.jpg"
             */
            'image_url' => $this->image_url,

            /**
             * The status of the slider (true for active, false for inactive).
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
