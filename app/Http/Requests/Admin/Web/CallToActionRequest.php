<?php

namespace App\Http\Requests\Admin\Web;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\UploadedFile;
use Illuminate\Validation\Rule;

/**
 * @class CallToActionRequest
 * @brief Request for managing website Call To Action content.
 *
 * This model represents the 'call_to_action' form request
 * for public display on the website.
 *
 * @property string $title The main title for the Call To Action section.
 * @property string $sub_title The subtitle for the Call To Action section.
 * @property string|null $button_text The text for the call-to-action button.
 * @property string|null $button_link The URL for the call-to-action button.
 * @property string|null $video_id The YouTube video ID to embed.
 * @property UploadedFile|null $image The main image for the Call To Action section.
 * @property UploadedFile|null $bg_image The background image for the Call To Action section.
 * @property bool|null $status The publication status of the Call To Action content.
 */
class CallToActionRequest extends FormRequest
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
        $rules = [
            /**
             * The main title for the Call To Action section.
             * @var string $title
             * @example "Ready to Enroll?"
             */
            'title' => 'required|string|max:255',
            /**
             * The subtitle for the Call To Action section.
             * @var string $sub_title
             * @example "Join our community today!"
             */
            'sub_title' => 'required|string|max:255',
            /**
             * The text for the call-to-action button.
             * @var string|null $button_text
             * @example "Apply Now"
             */
            'button_text' => 'nullable|string|max:255',
            /**
             * The link for the call-to-action button.
             * @var string|null $button_link
             * @example "https://example.com/apply"
             */
            'button_link' => 'nullable|url|max:255',
            /**
             * The YouTube video ID to embed.
             * @var string|null $video_id
             * @example "dQw4w9WgXcQ"
             */
            'video_id' => 'nullable|string|max:255|regex:/^[a-zA-Z0-9_-]{11}$/',
            /**
             * The status of the Call To Action (e.g., 1 for active, 0 for inactive).
             * @var int|null $status
             * @example 1
             */
            'status' => 'nullable|boolean',
        ];

        if ($this->isMethod('post')) {
            // For store method - image is required
            /**
             * The main image for the Call To Action section.
             * @var UploadedFile|null $image
             * @example (file upload)
             */
            $rules['image'] = 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048';
            /**
             * The background image for the Call To Action section.
             * @var UploadedFile|null $bg_image
             * @example (file upload)
             */
            $rules['bg_image'] = 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048';
        } elseif ($this->isMethod('put') || $this->isMethod('patch')) {
            // For update method - image is optional
            /**
             * The main image for the Call To Action section.
             *  Optional for updates, must be a valid image file if provided.
             * @var UploadedFile|null $image
             * "updated_call_to_action_banner.jpg"
             */
            $rules['image'] = 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048';
            /**
             * The background image for the Call To Action section.
             *  Optional for updates, must be a valid image file if provided.
             * @var UploadedFile|null $bg_image
             * "updated_call_to_action_banner.jpg"
             */
            $rules['bg_image'] = 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048';
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
            'title.required' => 'The title is required.',
            'title.max' => 'The title may not be greater than 255 characters.',
            'sub_title.required' => 'The subtitle is required.',
            'sub_title.max' => 'The subtitle may not be greater than 255 characters.',
            'button_text.max' => 'The button text may not be greater than 255 characters.',
            'button_link.url' => 'The button link must be a valid URL.',
            'button_link.max' => 'The button link may not be greater than 255 characters.',
            'video_id.regex' => 'The video ID must be a valid YouTube video ID format.',
            'image.image' => 'The main image must be an image.',
            'image.mimes' => 'The main image must be a file of type: jpeg, png, jpg, gif, svg, webp.',
            'image.max' => 'The main image may not be greater than 2MB.',
            'bg_image.image' => 'The background image must be an image.',
            'bg_image.mimes' => 'The background image must be a file of type: jpeg, png, jpg, gif, svg, webp.',
            'bg_image.max' => 'The background image may not be greater than 2MB.',
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
            'title' => 'title',
            'sub_title' => 'subtitle',
            'button_text' => 'button text',
            'button_link' => 'button link',
            'video_id' => 'YouTube video ID',
            'image' => 'main image',
            'bg_image' => 'background image',
            'status' => 'status',
        ];
    }
}
