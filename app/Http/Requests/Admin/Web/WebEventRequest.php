<?php

namespace App\Http\Requests\Admin\Web;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

/**
 * @class WebEventRequest
 * @brief Request for managing website events.
 *
 * This model represents the 'web_events' form request
 * for public display on the website.
 *
 * @property string $title The title of the web event.
 * @property string|null $slug The URL-friendly slug for the web event.
 * @property string $date The publication date of the web event.
 * @property string|null $time The time of the web event.
 * @property string|null $address The address or location of the web event.
 * @property string|null $description The description of the web event.
 * @property UploadedFile|null $attach The attached image file for the web event.
 * @property bool|null $status The publication status of the web event.
 */
class WebEventRequest extends FormRequest
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
        $webEventId = $this->route('web_event') ? $this->route('web_event')->id : null;

        $rules = [
            /**
             * The title of the web event.
             * Must be unique across all web events.
             * @var string $title
             * @example "Annual Sports Day"
             */
            'title' => [
                'required',
                'string',
                'max:255',
                Rule::unique('web_events', 'title')->ignore($webEventId),
            ],

            /**
             * The URL-friendly slug for the web event.
             * Must be unique across all web events.
             * @var string $slug
             * @example "annual-sports-day"
             */
            'slug' => [
                'sometimes',
                'string',
                'max:255',
                'regex:/^[a-z0-9]+(?:-[a-z0-9]+)*$/',
                Rule::unique('web_events', 'slug')->ignore($webEventId),
            ],

            /**
             * The date of the web event.
             * @var string $date
             * @example "2025-08-15"
             */
            'date' => 'required|date',

            /**
             * The time of the web event.
             * @var string|null $time
             * @example "10:00 AM"
             */
            'time' => 'nullable|string|max:255',

            /**
             * The address or location of the web event.
             * @var string|null $address
             * @example "University Auditorium"
             */
            'address' => 'nullable|string|max:255',

            /**
             * The description of the web event.
             * @var string|null $description
             * @example "Join us for a day of fun and games."
             */
            'description' => 'nullable|string',

            /**
             * The status of the web event (true for active, false for inactive).
             * @var bool $status
             * @example true
             */
            'status' => 'sometimes|boolean',
        ];

        if ($this->isMethod('post')) {
            $rules['date'] .= '|after_or_equal:today';
            // For store method - image is required
            /**
             * The attached image file for the web event.
             * Required for new web events, must be a valid image file.
             * @var UploadedFile $attach
             * @example "event_banner.jpg"
             */
            $rules['attach'] = 'required|image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048';
        } elseif ($this->isMethod('put') || $this->isMethod('patch')) {
            // For update method - image is optional
            /**
             * The attached image file for the web event.
             * Optional for updates, must be a valid image file if provided.
             * @var UploadedFile|null $attach
             * @example "updated_event_banner.jpg"
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
            'title.required' => 'The web event title is required.',
            'title.max' => 'The web event title may not be greater than 255 characters.',
            'title.unique' => 'This web event title already exists. Please choose a different one.',
            'slug.unique' => 'This slug is already taken. Please choose a different one.',
            'slug.regex' => 'The slug format is invalid. Use only lowercase letters, numbers, and hyphens.',
            'date.required' => 'The event date is required.',
            'date.date' => 'The event date must be a valid date format.',
            'date.after_or_equal' => 'The event date must be today or a future date.',
            'time.max' => 'The event time may not be greater than 255 characters.',
            'address.max' => 'The event address may not be greater than 255 characters.',
            'attach.required' => 'An image is required for the web event.',
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
            'title' => 'web event title',
            'slug' => 'URL slug',
            'date' => 'event date',
            'time' => 'event time',
            'address' => 'event address',
            'description' => 'event description',
            'attach' => 'event image',
            'status' => 'publication status',
        ];
    }
}
