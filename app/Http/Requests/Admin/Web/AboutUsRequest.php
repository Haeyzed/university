<?php

namespace App\Http\Requests\Admin\Web;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\UploadedFile;

/**
 * @class AboutUsRequest
 * @brief Request for managing website About Us content.
 *
 * This model represents the 'about_us' form request
 * for public display on the website.
 *
 * @property string $label The label for the About Us section.
 * @property string $title The main title for the About Us section.
 * @property string|null $short_desc A short description for the About Us section.
 * @property string $description The full description for the About Us section.
 * @property string|null $button_text The text for the call-to-action button.
 * @property string|null $video_id The YouTube video ID to embed.
 * @property array|null $features JSON encoded array of features.
 * @property UploadedFile|null $attach The main image for the About Us section.
 * @property string|null $vision_title The title for the Vision section.
 * @property string|null $vision_desc The description for the Vision section.
 * @property string|null $vision_icon The icon for the Vision section.
 * @property UploadedFile|null $vision_image The image for the Vision section.
 * @property string|null $mission_title The title for the Mission section.
 * @property string|null $mission_desc The description for the Mission section.
 * @property string|null $mission_icon The icon for the Mission section.
 * @property UploadedFile|null $mission_image The image for the Mission section.
 * @property bool|null $status The publication status of the About Us content.
 */
class AboutUsRequest extends FormRequest
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
     * @return array<string, ValidationRule|array|string>
     */
    public function rules(): array
    {
        $rules = [
            /**
             * The label for the About Us section.
             * @var string $label
             * @example "Our Story"
             */
            'label' => 'required|string|max:255',

            /**
             * The main title for the About Us section.
             * @var string $title
             * @example "About Our University"
             */
            'title' => 'required|string|max:255',

            /**
             * A short description for the About Us section.
             * @var string|null $short_desc
             * @example "Discover our journey and values that shape our institution."
             */
            'short_desc' => 'nullable|string|max:500',

            /**
             * The full description for the About Us section.
             * @var string $description
             * @example "Our university has a rich history spanning over decades..."
             */
            'description' => 'required|string',

            /**
             * The text for the call-to-action button.
             * @var string|null $button_text
             * @example "Learn More About Us"
             */
            'button_text' => 'nullable|string|max:100',

            /**
             * The YouTube video ID to embed.
             * @var string|null $video_id
             * @example "dQw4w9WgXcQ"
             */
            'video_id' => 'nullable|string|max:255|regex:/^[a-zA-Z0-9_-]{11}$/',

            /**
             * Array of features to highlight.
             * @var array|null $features
             * @example ["Experienced Faculty", "Modern Facilities", "Research Excellence"]
             */
            'features' => 'nullable|array|max:10',
            'features.*' => 'string|max:255',

            /**
             * The title for the Vision section.
             * @var string|null $vision_title
             * @example "Our Vision"
             */
            'vision_title' => 'nullable|string|max:255',

            /**
             * The description for the Vision section.
             * @var string|null $vision_desc
             * @example "To be a leading institution in higher education and research excellence."
             */
            'vision_desc' => 'nullable|string',

            /**
             * The icon class for the Vision section.
             * @var string|null $vision_icon
             * @example "fas fa-eye"
             */
            'vision_icon' => 'nullable|string|max:100|regex:/^[a-zA-Z0-9\s\-_]+$/',

            /**
             * The title for the Mission section.
             * @var string|null $mission_title
             * @example "Our Mission"
             */
            'mission_title' => 'nullable|string|max:255',

            /**
             * The description for the Mission section.
             * @var string|null $mission_desc
             * @example "To provide quality education and foster innovation in our community."
             */
            'mission_desc' => 'nullable|string',

            /**
             * The icon class for the Mission section.
             * @var string|null $mission_icon
             * @example "fas fa-bullseye"
             */
            'mission_icon' => 'nullable|string|max:100|regex:/^[a-zA-Z0-9\s\-_]+$/',

            /**
             * The publication status of the About Us content.
             * @var bool $status
             * @example true
             */
            'status' => 'sometimes|boolean',
        ];

        // Handle image validation based on HTTP method
        if ($this->isMethod('post')) {
            // For store method - main image is required
            /**
             * The main image for the About Us section.
             * Required for new content, must be a valid image file.
             * @var UploadedFile $attach
             * @example "about_us_main.jpg"
             */
            $rules['attach'] = 'required|image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048';
        } elseif ($this->isMethod('put') || $this->isMethod('patch')) {
            // For update method - images are optional
            /**
             * The main image for the About Us section.
             * Optional for updates, must be a valid image file if provided.
             * @var UploadedFile|null $attach
             * @example "updated_about_us.jpg"
             */
            $rules['attach'] = 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048';
        }

        // Vision and Mission images are always optional
        /**
         * The image for the Vision section.
         * @var UploadedFile|null $vision_image
         * @example "vision_image.jpg"
         */
        $rules['vision_image'] = 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048';

        /**
         * The image for the Mission section.
         * @var UploadedFile|null $mission_image
         * @example "mission_image.jpg"
         */
        $rules['mission_image'] = 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048';

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
            'label.required' => 'The section label is required.',
            'label.max' => 'The section label may not be greater than 255 characters.',
            'title.required' => 'The About Us title is required.',
            'title.max' => 'The About Us title may not be greater than 255 characters.',
            'short_desc.max' => 'The short description may not be greater than 500 characters.',
            'description.required' => 'The main description is required.',
            'button_text.max' => 'The button text may not be greater than 100 characters.',
            'video_id.regex' => 'The video ID must be a valid YouTube video ID format.',
            'features.max' => 'You may not add more than 10 features.',
            'features.*.max' => 'Each feature may not be greater than 255 characters.',
            'vision_title.max' => 'The vision title may not be greater than 255 characters.',
            'vision_icon.regex' => 'The vision icon must contain only valid CSS class characters.',
            'mission_title.max' => 'The mission title may not be greater than 255 characters.',
            'mission_icon.regex' => 'The mission icon must contain only valid CSS class characters.',
            'attach.required' => 'A main image is required for the About Us section.',
            'attach.image' => 'The main attachment must be an image.',
            'attach.mimes' => 'The main image must be a file of type: jpeg, png, jpg, gif, svg, webp.',
            'attach.max' => 'The main image may not be greater than 2MB.',
            'vision_image.image' => 'The vision attachment must be an image.',
            'vision_image.mimes' => 'The vision image must be a file of type: jpeg, png, jpg, gif, svg, webp.',
            'vision_image.max' => 'The vision image may not be greater than 2MB.',
            'mission_image.image' => 'The mission attachment must be an image.',
            'mission_image.mimes' => 'The mission image must be a file of type: jpeg, png, jpg, gif, svg, webp.',
            'mission_image.max' => 'The mission image may not be greater than 2MB.',
        ];
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        // Set default status if not provided
        if (!$this->has('status')) {
            $this->merge([
                'status' => true,
            ]);
        }

        // Clean up features array if provided
        if ($this->has('features') && is_array($this->features)) {
            $cleanFeatures = array_filter($this->features, function ($feature) {
                return !empty(trim($feature));
            });
            $this->merge([
                'features' => array_values($cleanFeatures),
            ]);
        }

        // Clean up video ID if provided
        if ($this->has('video_id') && !empty($this->video_id)) {
            // Extract video ID from full YouTube URL if provided
            $videoId = $this->video_id;
            if (preg_match('/(?:youtube\.com\/watch\?v=|youtu\.be\/)([a-zA-Z0-9_-]{11})/', $videoId, $matches)) {
                $videoId = $matches[1];
            }
            $this->merge([
                'video_id' => $videoId,
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
            'label' => 'section label',
            'title' => 'About Us title',
            'short_desc' => 'short description',
            'description' => 'main description',
            'button_text' => 'button text',
            'video_id' => 'YouTube video ID',
            'features' => 'features list',
            'attach' => 'main image',
            'vision_title' => 'vision title',
            'vision_desc' => 'vision description',
            'vision_icon' => 'vision icon',
            'vision_image' => 'vision image',
            'mission_title' => 'mission title',
            'mission_desc' => 'mission description',
            'mission_icon' => 'mission icon',
            'mission_image' => 'mission image',
            'status' => 'publication status',
        ];
    }
}
