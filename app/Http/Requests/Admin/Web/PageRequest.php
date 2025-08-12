<?php

namespace App\Http\Requests\Admin\Web;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

/**
 * @class PageRequest
 * @brief Request for managing website page.
 *
 * This model represents the 'page' form request
 * for public display on the website.
 *
 * @property string $title The title of the page article.
 * @property string|null $slug The URL-friendly slug for the page article.
 * @property string $date The publication date of the page article.
 * @property string|null $description The short content/description of the page article.
 * @property string|null $meta_title The SEO meta title for the page article.
 * @property string|null $meta_description The SEO meta description for the page article.
 * @property bool|null $status The publication status of the page article.
 * @property UploadedFile|null $attach The attached image file for the page article.
 */
class PageRequest extends FormRequest
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
        $pageId = $this->route('page') ? $this->route('page') : null;

        $rules = [
            /**
             * The title of the page.
             * @var string $title
             * @example "Admissions"
             */
            'title' => 'required|string|max:255',

            /**
             * The URL-friendly slug for the page.
             * Must be unique across all pages.
             * @var string $slug
             * @example "admissions"
             */
            'slug' => [
                'sometimes',
                'string',
                'max:255',
                'regex:/^[a-z0-9]+(?:-[a-z0-9]+)*$/',
                Rule::unique('pages', 'slug')->ignore($pageId),
            ],

            /**
             * The content/description of the page.
             * @var string|null $description
             * @example "Information about the admission process, requirements, and deadlines."
             */
            'description' => 'nullable|string',

            /**
             * The SEO meta title for the page.
             * @var string|null $meta_title
             * @example "Admissions | University Pages"
             */
            'meta_title' => 'nullable|string|max:60',

            /**
             * The SEO meta description for the page.
             * @var string|null $meta_description
             * @example "Find out how to apply to our university, including requirements and deadlines."
             */
            'meta_description' => 'nullable|string|max:160',

            /**
             * The publication status of the page.
             * @var bool $status
             * @example true
             */
            'status' => 'sometimes|boolean',
        ];

        if ($this->isMethod('post')) {
            // For store method - image is required
            /**
             * The attached image file for the page.
             * Required for new pages, must be a valid image file.
             * @var UploadedFile $attach
             * @example "page_banner.jpg"
             */
            $rules['attach'] = 'required|image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048';
        } elseif ($this->isMethod('put') || $this->isMethod('patch')) {
            // For update method - image is optional
            /**
             * The attached image file for the page.
             * Optional for updates, must be a valid image file if provided.
             * @var UploadedFile|null $attach
             * @example "updated_page_banner.jpg"
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
            'title.required' => 'The page title is required.',
            'title.max' => 'The page title may not be greater than 255 characters.',
            'slug.unique' => 'This slug is already taken. Please choose a different one.',
            'slug.regex' => 'The slug format is invalid. Use only lowercase letters, numbers, and hyphens.',
            'attach.required' => 'An image is required for the page.',
            'attach.image' => 'The attached file must be an image.',
            'attach.mimes' => 'The image must be a file of type: jpeg, png, jpg, gif, svg, webp.',
            'attach.max' => 'The image may not be greater than 2MB.',
            'meta_title.max' => 'The meta title may not be greater than 60 characters.',
            'meta_description.max' => 'The meta description may not be greater than 160 characters.',
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
            'title' => 'page title',
            'slug' => 'URL slug',
            'description' => 'page content',
            'meta_title' => 'SEO meta title',
            'meta_description' => 'SEO meta description',
            'attach' => 'page image',
            'status' => 'publication status',
        ];
    }
}
