<?php

namespace App\Http\Requests\Admin\Web;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

/**
 * @class NewsRequest
 * @brief Request for managing website news.
 *
 * This model represents the 'news' form request
 * for public display on the website.
 *
 * @property string $title The title of the news article.
 * @property string|null $slug The URL-friendly slug for the news article.
 * @property string $date The publication date of the news article.
 * @property string|null $short_description The short content/description of the news article.
 * @property string|null $long_description The main content/description of the news article.
 * @property string|null $meta_title The SEO meta title for the news article.
 * @property string|null $meta_description The SEO meta description for the news article.
 * @property bool|null $status The publication status of the news article.
 * @property UploadedFile|null $attach The attached image file for the news article.
 */
class NewsRequest extends FormRequest
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
        $newsId = $this->route('news') ? $this->route('news') : null;

        $rules = [
            /**
             * The title of the news article.
             * @var string $title
             * @example "University Researchers Discover New Planet"
             */
            'title' => 'required|string|max:255',

            /**
             * The URL-friendly slug for the news article.
             * Must be unique across all news articles.
             * @var string $slug
             * @example "university-researchers-discover-new-planet"
             */
            'slug' => [
                'nullable',
                'string',
                'max:255',
                'regex:/^[a-z0-9]+(?:-[a-z0-9]+)*$/',
                Rule::unique('news', 'slug')->ignore($newsId),
            ],

            /**
             * The publication date of the news article.
             * @var string $date
             * @example "2024-07-18"
             */
            'date' => 'required|date',

            /**
             * The short description or summary of the news article.
             * @var string|null $short_description
             * @example "A team of astronomers at our university has identified a new exoplanet..."
             */
            'short_description' => 'nullable|string|max:500',

            /**
             * The main content/description of the news article.
             * @var string|null $long_description
             * @example "In a groundbreaking discovery, Professor Anya Sharma and her team..."
             */
            'long_description' => 'nullable|string',

            /**
             * The SEO meta title for the news article.
             * @var string|null $meta_title
             * @example "New Planet Discovery | University News"
             */
            'meta_title' => 'nullable|string|max:60',

            /**
             * The SEO meta description for the news article.
             * @var string|null $meta_description
             * @example "University researchers discover a new exoplanet in the Kepler system..."
             */
            'meta_description' => 'nullable|string|max:160',

            /**
             * The publication status of the news article.
             * @var bool $status
             * @example true
             */
            'status' => 'sometimes|boolean',
        ];

        if ($this->isMethod('post')) {
            // For store method - image is required
            /**
             * The attached image file for the news article.
             * Required for new articles, must be a valid image file.
             * @var UploadedFile $attach
             * @example "news_image.jpg"
             */
            $rules['attach'] = 'required|image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048';
        } elseif ($this->isMethod('put') || $this->isMethod('patch')) {
            // For update method - image is optional
            /**
             * The attached image file for the news article.
             * Optional for updates, must be a valid image file if provided.
             * @var UploadedFile|null $attach
             * @example "updated_news_image.jpg"
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
            'title.required' => 'The news title is required.',
            'title.max' => 'The news title may not be greater than 255 characters.',
            'slug.unique' => 'This slug is already taken. Please choose a different one.',
            'slug.regex' => 'The slug format is invalid. Use only lowercase letters, numbers, and hyphens.',
            'date.required' => 'The publication date is required.',
            'date.date' => 'The publication date must be a valid date.',
            'short_description.max' => 'The short description may not be greater than 500 characters.',
            'attach.required' => 'An image is required for the news article.',
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
            'title' => 'news title',
            'slug' => 'URL slug',
            'date' => 'publication date',
            'short_description' => 'short description',
            'long_description' => 'long description',
            'meta_title' => 'SEO meta title',
            'meta_description' => 'SEO meta description',
            'attach' => 'news image',
            'status' => 'publication status',
        ];
    }
}
