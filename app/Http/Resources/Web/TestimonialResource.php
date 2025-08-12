<?php

namespace App\Http\Resources\Web;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Carbon;

/**
 * Class TestimonialResource
 *
 * @property int $id The unique identifier for the testimonial.
 * @property string $name The name of the person giving the testimonial.
 * @property string|null $designation The designation or title of the person.
 * @property string $description The content of the testimonial.
 * @property float $rating The rating given by the person (1-5).
 * @property string|null $attach The filename of the attached image.
 * @property string|null $attach_image_url The full URL to the attached image.
 * @property bool $status The status of the testimonial (true for active, false for inactive).
 * @property int|null $created_by The ID of the user who created the record.
 * @property string|null $created_by_name The name of the user who created the record.
 * @property int|null $updated_by The ID of the user who last updated the record.
 * @property string|null $updated_by_name The name of the user who last updated the record.
 * @property string $created_at The timestamp when the record was created.
 * @property string $updated_at The timestamp when the record was last updated.
 * @property string|null $deleted_at The timestamp when the record was soft deleted.
 */
class TestimonialResource extends JsonResource
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
             * The unique identifier for the testimonial.
             * @var int $id
             * @example 1
             */
            'id' => $this->id,

            /**
             * The name of the person giving the testimonial.
             * @var string $name
             * @example "Jane Doe"
             */
            'name' => $this->name,

            /**
             * The designation or title of the person.
             * @var string|null $designation
             * @example "Alumna, Class of 2020"
             */
            'designation' => $this->designation,

            /**
             * The content of the testimonial.
             * @var string $description
             * @example "The university provided me with an excellent education and prepared me for a successful career."
             */
            'description' => $this->description,

            /**
             * The rating given by the person (1-5).
             * @var float $rating
             * @example 5.0
             */
            'rating' => (float)$this->rating,

            /**
             * The filename of the attached image.
             * @var string|null $attach
             * @example "testimonial_jane_doe.jpg"
             */
            'attach' => $this->attach,

            /**
             * The full URL to the attached image.
             * @var string|null $attach_image_url
             * @example "https://your-bucket.s3.amazonaws.com/testimonials/testimonial_jane_doe.jpg"
             */
            'attach_image_url' => $this->attach_image_url,

            /**
             * The status of the testimonial (true for active, false for inactive).
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
             * The timestamp when the record was soft deleted.
             * @var string|null $deleted_at
             * @example "2024-07-19 12:30:00"
             */
            'deleted_at' => $this->deleted_at ? Carbon::parse($this->deleted_at)->format('Y-m-d H:i:s') : null,
        ];
    }
}
