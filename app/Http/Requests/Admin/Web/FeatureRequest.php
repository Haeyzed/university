<?php

namespace App\Http\Requests\Admin\Web;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\UploadedFile;
use Illuminate\Validation\Rule;

/**
 * @class FeatureRequest
 * @brief Request for managing website features.
 *
 * This model represents the 'feature' form request
 * for public display on the website.
 *
 * @property string $title The title of the feature.
 * @property string $description The description of the feature.
 * @property string|null $icon The icon associated with the feature.
 * @property UploadedFile|null $attach The attached image file for the feature.
 * @property bool|null $status The publication status of the feature.
 */
class FeatureRequest extends FormRequest
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
        $featureId = $this->route('feature') ? $this->route('feature') : null;

        $rules = [
            /**
             * The title of the feature.
             * Must be unique across all features.
             * @var string $title
             * @example "Modern Facilities"
             */
            'title' => [
                'required',
                'string',
                'max:255',
                Rule::unique('features', 'title')->ignore($featureId),
            ],

            /**
             * The description of the feature.
             * @var string $description
             * @example "State-of-the-art laboratories, libraries, and sports complexes."
             */
            'description' => 'required|string',

            /**
             * The icon associated with the feature.
             * @var string|null $icon
             * @example "fas fa-flask"
             */
            'icon' => 'nullable|string|max:255',

            /**
             * The publication status of the feature.
             * @var bool $status
             * @example true
             */
            'status' => 'sometimes|boolean',
        ];

        if ($this->isMethod('post')) {
            // For store method - image is required
            /**
             * The attached image file for the feature.
             * Required for new features, must be a valid image file.
             * @var UploadedFile $attach
             * @example "feature_icon.png"
             */
            $rules['attach'] = 'required|image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048';
        } elseif ($this->isMethod('put') || $this->isMethod('patch')) {
            // For update method - image is optional
            /**
             * The attached image file for the feature.
             * Optional for updates, must be a valid image file if provided.
             * @var UploadedFile|null $attach
             * @example "updated_feature_icon.png"
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
            'title.required' => 'The feature title is required.',
            'title.max' => 'The feature title may not be greater than 255 characters.',
            'title.unique' => 'This feature title already exists. Please choose a different one.',
            'description.required' => 'The feature description is required.',
            'icon.max' => 'The icon name may not be greater than 255 characters.',
            'attach.required' => 'An image is required for the feature.',
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
            'title' => 'feature title',
            'description' => 'feature description',
            'icon' => 'feature icon',
            'attach' => 'feature image',
            'status' => 'publication status',
        ];
    }
}
