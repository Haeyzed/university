<?php

namespace App\Http\Requests\Admin\Web;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

/**
 * @class CourseRequest
 * @brief Request for managing website courses.
 *
 * This model represents the 'course' form request
 * for public display on the website.
 *
 * @property string $title The title of the course.
 * @property string|null $slug The URL-friendly slug for the course.
 * @property string|null $faculty The faculty associated with the course.
 * @property string|null $semesters The number of semesters in the course.
 * @property string|null $credits The number of credits for the course.
 * @property string|null $courses The number of individual courses/subjects within this program.
 * @property string|null $duration The duration of the course (e.g., "4 Years").
 * @property float|null $fee The fee for the course.
 * @property string|null $description The description of the course.
 * @property UploadedFile|null $attach The attached image file for the course.
 * @property bool|null $status The publication status of the course.
 */
class CourseRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        $courseId = $this->route('course') ? $this->route('course') : null;

        $rules = [
            /**
             * The title of the course.
             * Must be unique across all courses.
             * @var string $title
             * @example "Computer Science"
             */
            'title' => [
                'required',
                'string',
                'max:255',
                Rule::unique('courses', 'title')->ignore($courseId),
            ],

            /**
             * The URL-friendly slug for the course.
             * Must be unique across all courses.
             * @var string $slug
             * @example "computer-science"
             */
            'slug' => [
                'sometimes',
                'string',
                'max:255',
                'regex:/^[a-z0-9]+(?:-[a-z0-9]+)*$/',
                Rule::unique('courses', 'slug')->ignore($courseId),
            ],

            /**
             * The faculty associated with the course.
             * @var string|null $faculty
             * @example "Engineering"
             */
            'faculty' => 'nullable|string|max:255',

            /**
             * The number of semesters in the course.
             * @var string|null $semesters
             * @example "8"
             */
            'semesters' => 'nullable|string|max:255', // Changed to string based on migration

            /**
             * The number of credits for the course.
             * @var string|null $credits
             * @example "120"
             */
            'credits' => 'nullable|string|max:255', // Changed to string based on migration

            /**
             * The number of individual courses/subjects within this program.
             * @var string|null $courses
             * @example "40"
             */
            'courses' => 'nullable|string|max:255', // Changed to string based on migration

            /**
             * The duration of the course (e.g., "4 Years").
             * @var string|null $duration
             * @example "4 Years"
             */
            'duration' => 'nullable|string|max:255',

            /**
             * The fee for the course.
             * @var float|null $fee
             * @example 15000.00
             */
            'fee' => 'nullable|numeric|min:0',

            /**
             * The description of the course.
             * @var string|null $description
             * @example "This course provides a comprehensive understanding..."
             */
            'description' => 'nullable|string',

            /**
             * The publication status of the course.
             * @var bool $status
             * @example true
             */
            'status' => 'sometimes|boolean',
        ];

        if ($this->isMethod('post')) {
            // For store method - image is required
            /**
             * The attached image file for the course.
             * Required for new courses, must be a valid image file.
             * @var UploadedFile $attach
             * @example "course_banner.jpg"
             */
            $rules['attach'] = 'required|image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048';
        } elseif ($this->isMethod('put') || $this->isMethod('patch')) {
            // For update method - image is optional
            /**
             * The attached image file for the course.
             * Optional for updates, must be a valid image file if provided.
             * @var UploadedFile|null $attach
             * @example "updated_course_banner.jpg"
             */
            $rules['attach'] = 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048';
        }

        return $rules;
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'title.required' => 'The course title is required.',
            'title.max' => 'The course title may not be greater than 255 characters.',
            'title.unique' => 'This course title already exists. Please choose a different one.',
            'slug.unique' => 'This slug is already taken. Please choose a different one.',
            'slug.regex' => 'The slug format is invalid. Use only lowercase letters, numbers, and hyphens.',
            'faculty.max' => 'The faculty name may not be greater than 255 characters.',
            'semesters.max' => 'The semesters field may not be greater than 255 characters.',
            'credits.max' => 'The credits field may not be greater than 255 characters.',
            'courses.max' => 'The courses field may not be greater than 255 characters.',
            'duration.max' => 'The duration field may not be greater than 255 characters.',
            'fee.numeric' => 'The fee must be a number.',
            'fee.min' => 'The fee must be a positive number.',
            'attach.required' => 'An image is required for the course.',
            'attach.image' => 'The attached file must be an image.',
            'attach.mimes' => 'The image must be a file of type: jpeg, png, jpg, gif, svg, webp.',
            'attach.max' => 'The image may not be greater than 2MB.',
        ];
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        // Generate slug from title if not provided
        if (!$this->has('slug') && $this->has('title')) {
            $this->merge([
                'slug' => Str::slug($this->title, '-'),
            ]);
        }

        // Set default status if not provided
        if (!$this->has('status')) {
            $this->merge([
                'status' => true,
            ]);
        }
    }

    /**
     * Get custom attributes for validator errors.
     *
     * @return array<string, string>
     */
    public function attributes(): array
    {
        return [
            'title' => 'course title',
            'slug' => 'URL slug',
            'faculty' => 'faculty',
            'semesters' => 'semesters',
            'credits' => 'credits',
            'courses' => 'courses count',
            'duration' => 'duration',
            'fee' => 'fee',
            'description' => 'course description',
            'attach' => 'course image',
            'status' => 'publication status',
        ];
    }
}
