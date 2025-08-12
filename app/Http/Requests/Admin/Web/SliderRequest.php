<?php

namespace App\Http\Requests\Admin\Web;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\UploadedFile;
use Illuminate\Validation\Rule;

/**
 * @class SliderRequest
 * @brief Request for managing website sliders.
 *
 * This model represents the 'slider' form request
 * for public display on the website.
 *
 * @property string $title The title of the slider.
 * @property string|null $sub_title The subtitle of the slider.
 * @property string|null $button_text The text for the call-to-action button.
 * @property string|null $button_link The URL for the call-to-action button.
 * @property UploadedFile|null $attach The attached image file for the slider.
 * @property bool|null $status The publication status of the slider.
 */
class SliderRequest extends FormRequest
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
        $sliderId = $this->route('slider') ? $this->route('slider') : null;

        $rules = [
            /**
             * The title of the slider.
             * Must be unique across all sliders.
             * @var string $title
             * @example "Welcome to Our Campus"
             */
            'title' => [
                'required',
                'string',
                'max:255',
                Rule::unique('sliders', 'title')->ignore($sliderId),
            ],

            /**
             * The subtitle of the slider.
             * @var string|null $sub_title
             * @example "Explore our vibrant academic environment."
             */
            'sub_title' => 'nullable|string|max:500',

            /**
             * The text for the call-to-action button.
             * @var string|null $button_text
             * @example "Learn More"
             */
            'button_text' => 'nullable|string|max:255',

            /**
             * The URL for the call-to-action button.
             * @var string|null $button_link
             * @example "/about-us"
             */
            'button_link' => 'nullable|string|max:255',

            /**
             * The publication status of the slider.
             * @var bool $status
             * @example true
             */
            'status' => 'sometimes|boolean',
        ];

        if ($this->isMethod('post')) {
            // For store method - image is required
            /**
             * The attached image file for the slider.
             * Required for new sliders, must be a valid image file.
             * @var UploadedFile $attach
             * @example "slider_image.jpg"
             */
            $rules['attach'] = 'required|image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048';
        } elseif ($this->isMethod('put') || $this->isMethod('patch')) {
            // For update method - image is optional
            /**
             * The attached image file for the slider.
             * Optional for updates, must be a valid image file if provided.
             * @var UploadedFile|null $attach
             * @example "updated_slider_image.jpg"
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
            'title.required' => 'The slider title is required.',
            'title.max' => 'The slider title may not be greater than 255 characters.',
            'title.unique' => 'This slider title is already taken. Please choose a different one.',
            'sub_title.max' => 'The subtitle may not be greater than 500 characters.',
            'button_text.max' => 'The button text may not be greater than 255 characters.',
            'button_link.max' => 'The button link may not be greater than 255 characters.',
            'attach.required' => 'An image is required for the slider.',
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
            'title' => 'slider title',
            'sub_title' => 'slider subtitle',
            'button_text' => 'button text',
            'button_link' => 'button link',
            'attach' => 'slider image',
            'status' => 'publication status',
        ];
    }
}
