<?php

namespace App\Http\Requests\Admin\Web;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\UploadedFile;
use Illuminate\Validation\Rule;

/**
 * @class TestimonialRequest
 * @brief Request for managing website testimonial content.
 *
 * This model represents the 'testimonials' form request
 * for public display on the website.
 *
 * @property string $name The name of the person giving the testimonial.
 * @property string|null $designation The designation or title of the person.
 * @property string $description The content of the testimonial.
 * @property float|null $rating The rating given by the person (1-5).
 * @property UploadedFile|null $attach The image attachment for the testimonial.
 * @property bool|null $attach_removed Flag to indicate if the image should be removed.
 * @property bool|null $status The publication status of the testimonial.
 */
class TestimonialRequest extends FormRequest
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
             * The name of the person giving the testimonial.
             * @var string $name
             * @example "John Doe"
             */
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('testimonials', 'name')->ignore($this->route('testimonial')),
            ],

            /**
             * The designation or title of the person.
             * @var string|null $designation
             * @example "Alumni, Class of 2020"
             */
            'designation' => 'nullable|string|max:255',

            /**
             * The actual testimonial description.
             * @var string $description
             * @example "This university provided an excellent learning environment."
             */
            'description' => 'required|string',

            /**
             * The rating given by the person (1-5).
             * @var float|null $rating
             * @example 5.0
             */
            'rating' => 'nullable|numeric|min:1|max:5',

            /**
             * The status of the testimonial (e.g., 1 for active, 0 for inactive).
             * @var bool|null $status
             * @example true
             */
            'status' => 'sometimes|boolean',
        ];

        // Handle image validation based on HTTP method
        if ($this->isMethod('post')) {
            // For store method - main image is required
            /**
             * The image attachment for the testimonial.
             * Required for new content, must be a valid image file.
             * @var UploadedFile $attach
             * @example (file upload)
             */
            $rules['attach'] = 'required|image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048';
        } elseif ($this->isMethod('put') || $this->isMethod('patch')) {
            // For update method - image is optional, but required if not removed
            /**
             * The main image for the Testimonial section.
             * Optional for updates, must be a valid image file if provided.
             * @var UploadedFile|null $attach
             * @example "updated_testimonial.jpg"
             */
            $rules['attach'] = ['nullable', 'image', 'mimes:jpeg,png,jpg,gif,svg,webp', 'max:2048',];
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
            'name.required' => 'The name is required.',
            'name.unique' => 'A testimonial with this name already exists.',
            'name.max' => 'The name may not be greater than 255 characters.',
            'description.required' => 'The description is required.',
            'rating.numeric' => 'The rating must be a number.',
            'rating.min' => 'The rating must be at least 1.',
            'rating.max' => 'The rating may not be greater than 5.',
            'attach.required' => 'An image is required for the testimonial.',
            'attach.image' => 'The attachment must be an image.',
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
            'name' => 'name',
            'designation' => 'designation',
            'description' => 'description',
            'rating' => 'rating',
            'attach' => 'image',
            'status' => 'status',
        ];
    }
}
