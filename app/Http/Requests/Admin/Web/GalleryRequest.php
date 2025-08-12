<?php

namespace App\Http\Requests\Admin\Web;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\UploadedFile;
use Illuminate\Validation\Rule;

/**
 * @class GalleryRequest
 * @brief Request for managing website gallery items.
 *
 * This model represents the 'gallery' form request
 * for public display on the website.
 *
 * @property string|null $title The title of the gallery item.
 * @property string|null $description The description of the gallery item.
 * @property bool|null $status The publication status of the gallery item.
 * @property UploadedFile|null $attach The attached image file for the gallery item.
 */
class GalleryRequest extends FormRequest
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
        $galleryId = $this->route('gallery') ? $this->route('gallery')->id : null;

        $rules = [
            /**
             * The title of the gallery item.
             * @var string $title
             * @example "Campus Life"
             */
            'title' => 'nullable|string|max:255',

            /**
             * The description of the gallery item.
             * @var string|null $description
             * @example "Photos capturing the vibrant student life on campus."
             */
            'description' => 'nullable|string',

            /**
             * The publication status of the gallery item.
             * @var bool $status
             * @example true
             */
            'status' => 'sometimes|boolean',
        ];

        if ($this->isMethod('post')) {
            // For store method - image is required
            /**
             * The attached image file for the gallery item.
             * Required for new gallery items, must be a valid image file.
             * @var UploadedFile $attach
             * @example "campus_life_01.jpg"
             */
            $rules['attach'] = 'required|image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048';
        } elseif ($this->isMethod('put') || $this->isMethod('patch')) {
            // For update method - image is optional
            /**
             * The attached image file for the gallery item.
             * Optional for updates, must be a valid image file if provided.
             * @var UploadedFile|null $attach
             * @example "updated_campus_life.jpg"
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
            'title.max' => 'The gallery title may not be greater than 255 characters.',
            'attach.required' => 'An image is required for the gallery item.',
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
            'title' => 'gallery title',
            'description' => 'gallery description',
            'attach' => 'gallery image',
            'status' => 'publication status',
        ];
    }
}
