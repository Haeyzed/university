<?php

namespace App\Http\Resources\Web;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Storage;

/**
 * Class WebEventResource
 *
 * @property int $id The unique identifier for the web event.
 * @property string $title The title of the web event.
 * @property string $slug The URL-friendly slug for the web event.
 * @property string $date The date of the web event.
 * @property string|null $time The time of the web event.
 * @property string|null $address The address or location of the web event.
 * @property string|null $description The description of the web event.
 * @property string|null $attach The filename of the attached image.
 * @property string|null $image_url The full URL to the image.
 * @property bool $status The status of the web event (true for active, false for inactive).
 * @property int|null $created_by The ID of the user who created the record.
 * @property string|null $created_by_name The name of the user who created the record.
 * @property int|null $updated_by The ID of the user who last updated the record.
 * @property string|null $updated_by_name The name of the user who last updated the record.
 * @property string $created_at The timestamp when the record was created.
 * @property string $updated_at The timestamp when the record was last updated.
 * @property string $deleted_at The timestamp when the record was last deleted.
 */
class WebEventResource extends JsonResource
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
             * The unique identifier for the web event.
             * @var int $id
             * @example 1
             */
            'id' => $this->id,

            /**
             * The title of the web event.
             * @var string $title
             * @example "Open House 2024"
             */
            'title' => $this->title,

            /**
             * The slug for the web event URL.
             * @var string $slug
             * @example "open-house-2024"
             */
            'slug' => $this->slug,

            /**
             * The date of the web event.
             * @var string $date
             * @example "2024-11-10"
             */
            'date' => $this->date,

            /**
             * The time of the web event.
             * @var string|null $time
             * @example "10:00 AM"
             */
            'time' => $this->time,

            /**
             * The address or location of the web event.
             * @var string|null $address
             * @example "University Auditorium"
             */
            'address' => $this->address,

            /**
             * The description of the web event.
             * @var string|null $description
             * @example "Join us for a day of interactive sessions, campus tours, and meet our faculty and students. Learn about our academic offerings, student life, and admission process."
             */
            'description' => $this->description,

            /**
             * The filename of the attached image.
             * @var string|null $attach
             * @example "open_house.jpg"
             */
            'attach' => $this->attach,

            /**
             * The full URL to the attached image.
             * @var string|null $image_url
             * @example "https://your-bucket.s3.amazonaws.com/web-event/open_house.jpg"
             */
            'image_url' => $this->image_url,

            /**
             * The status of the web event (true for active, false for inactive).
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
