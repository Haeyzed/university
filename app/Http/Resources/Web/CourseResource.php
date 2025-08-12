<?php

namespace App\Http\Resources\Web;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Storage;

/**
 * Class CourseResource
 *
 * @property int $id The unique identifier for the course.
 * @property string $title The title of the course.
 * @property string $slug The URL-friendly slug for the course.
 * @property string|null $faculty The faculty associated with the course.
 * @property string|null $semesters The number of semesters in the course.
 * @property string|null $credits The number of credits for the course.
 * @property string|null $courses The number of individual courses/subjects within this program.
 * @property string|null $duration The duration of the course.
 * @property float|null $fee The fee for the course.
 * @property string|null $description The description of the course.
 * @property string|null $attach The filename of the attached image.
 * @property string|null $image_url The full URL to the image.
 * @property bool $status The status of the course (true for active, false for inactive).
 * @property int|null $created_by The ID of the user who created the record.
 * @property string|null $created_by_name The name of the user who created the record.
 * @property int|null $updated_by The ID of the user who last updated the record.
 * @property string|null $updated_by_name The name of the user who last updated the record.
 * @property string $created_at The timestamp when the record was created.
 * @property string $updated_at The timestamp when the record was last updated.
 * @property string $deleted_at The timestamp when the record was last deleted.
 */
class CourseResource extends JsonResource
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
             * The unique identifier for the course.
             * @var int $id
             * @example 1
             */
            'id' => $this->id,

            /**
             * The title of the course.
             * @var string $title
             * @example "Web Development Fundamentals"
             */
            'title' => $this->title,

            /**
             * The slug for the course URL.
             * @var string $slug
             * @example "web-development-fundamentals"
             */
            'slug' => $this->slug,

            /**
             * The faculty associated with the course.
             * @var string|null $faculty
             * @example "Computer Science"
             */
            'faculty' => $this->faculty,

            /**
             * The number of semesters in the course.
             * @var string|null $semesters
             * @example "8"
             */
            'semesters' => $this->semesters,

            /**
             * The number of credits for the course.
             * @var string|null $credits
             * @example "120"
             */
            'credits' => $this->credits,

            /**
             * The number of individual courses/subjects within this program.
             * @var string|null $courses
             * @example "40"
             */
            'courses' => $this->courses,

            /**
             * The duration of the course.
             * @var string|null $duration
             * @example "4 Years"
             */
            'duration' => $this->duration,

            /**
             * The fee for the course.
             * @var float|null $fee
             * @example 15000.00
             */
            'fee' => (float)$this->fee,

            /**
             * The description of the course.
             * @var string|null $description
             * @example "This course covers the core concepts of web development, including responsive design, DOM manipulation, and basic server-side scripting."
             */
            'description' => $this->description,

            /**
             * The filename of the attached image.
             * @var string|null $attach
             * @example "web_dev.jpg"
             */
            'attach' => $this->attach,

            /**
             * The full URL to the attached image.
             * @var string|null $image_url
             * @example "https://your-bucket.s3.amazonaws.com/course/web_dev.jpg"
             */
            'image_url' => $this->image_url,

            /**
             * The status of the course (true for active, false for inactive).
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
