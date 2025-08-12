<?php

namespace App\Http\Requests\Admin\System;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\UploadedFile;

/**
 * @class SettingRequest
 * @brief Request for managing general system settings.
 *
 * This request handles validation for the single 'settings' entry.
 *
 * @property string $title The main title of the website/academy.
 * @property string|null $academy_code The unique code for the academy.
 * @property string|null $meta_title The SEO meta title for the website.
 * @property string|null $meta_description The SEO meta description for the website.
 * @property string|null $meta_keywords The SEO meta keywords for the website.
 * @property UploadedFile|null $logo_path The logo image file.
 * @property UploadedFile|null $favicon_path The favicon image file.
 * @property string|null $phone The contact phone number.
 * @property string|null $email The contact email address.
 * @property string|null $fax The contact fax number.
 * @property string|null $address The physical address.
 * @property string|null $language The default language code (e.g., "en").
 * @property string|null $date_format The default date format (e.g., "Y-m-d").
 * @property string|null $time_format The default time format (e.g., "H:i:s").
 * @property string|null $week_start The start day of the week (0 for Sunday, 1 for Monday).
 * @property string|null $time_zone The default timezone (e.g., "Asia/Kathmandu").
 * @property string|null $currency The default currency code (e.g., "USD").
 * @property string|null $currency_symbol The symbol for the default currency (e.g., "$").
 * @property int $decimal_place The number of decimal places for currency.
 * @property string|null $copyright_text The copyright text for the footer.
 * @property bool|null $status The status of the settings (active/inactive).
 */
class SettingRequest extends FormRequest
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
             * The main title of the website/academy.
             * @var string $title
             * @example "My University"
             */
            'title' => 'required|string|max:255',

            /**
             * The unique code for the academy.
             * @var string|null $academy_code
             * @example "UNI-001"
             */
            'academy_code' => 'nullable|string|max:255',

            /**
             * The SEO meta title for the website.
             * @var string|null $meta_title
             * @example "My University - Education Excellence"
             */
            'meta_title' => 'nullable|string|max:255',

            /**
             * The SEO meta description for the website.
             * @var string|null $meta_description
             * @example "Leading university offering diverse academic programs."
             */
            'meta_description' => 'nullable|string',

            /**
             * The SEO meta keywords for the website.
             * @var string|null $meta_keywords
             * @example "university, education, courses, degrees"
             */
            'meta_keywords' => 'nullable|string',

            /**
             * The contact phone number.
             * @var string|null $phone
             * @example "+1234567890"
             */
            'phone' => 'nullable|string|max:50',

            /**
             * The contact email address.
             * @var string|null $email
             * @example "info@example.com"
             */
            'email' => 'nullable|email|max:255',

            /**
             * The contact fax number.
             * @var string|null $fax
             * @example "+1234567891"
             */
            'fax' => 'nullable|string|max:50',

            /**
             * The physical address.
             * @var string|null $address
             * @example "123 University Ave, City, Country"
             */
            'address' => 'nullable|string',

            /**
             * The default language code (e.g., "en").
             * @var string|null $language
             * @example "en"
             */
            'language' => 'nullable|string|max:10',

            /**
             * The default date format (e.g., "Y-m-d").
             * @var string|null $date_format
             * @example "Y-m-d"
             */
            'date_format' => 'nullable|string|max:50',

            /**
             * The default time format (e.g., "H:i:s").
             * @var string|null $time_format
             * @example "H:i:s"
             */
            'time_format' => 'nullable|string|max:50',

            /**
             * The start day of the week (0 for Sunday, 1 for Monday).
             * @var string|null $week_start
             * @example "1"
             */
            'week_start' => 'nullable|string|max:10',

            /**
             * The default timezone (e.g., "Asia/Kathmandu").
             * @var string|null $time_zone
             * @example "Asia/Kathmandu"
             */
            'time_zone' => 'nullable|string|max:100',

            /**
             * The default currency code (e.g., "USD").
             * @var string|null $currency
             * @example "USD"
             */
            'currency' => 'nullable|string|max:10',

            /**
             * The symbol for the default currency (e.g., "$").
             * @var string|null $currency_symbol
             * @example "$"
             */
            'currency_symbol' => 'nullable|string|max:10',

            /**
             * The number of decimal places for currency.
             * @var int $decimal_place
             * @example 2
             */
            'decimal_place' => 'nullable|integer|min:0|max:4',

            /**
             * The copyright text for the footer.
             * @var string|null $copyright_text
             * @example "© 2025 My University. All rights reserved."
             */
            'copyright_text' => 'nullable|string',

            /**
             * The status of the settings (active/inactive).
             * @var bool $status
             * @example true
             */
            'status' => 'sometimes|boolean',
        ];

        if ($this->isMethod('post')) {
            // For store method - image is required
            /**
             * The logo image file.
             * @var UploadedFile|null $logo_path
             *  "updated_setting_logo.jpg"
             */
            $rules['logo_path'] = 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048';

            /**
             * The favicon image file.
             * @var UploadedFile|null $favicon_path
             *  "updated_setting_favicon.jpg"
             */
            $rules['favicon_path'] = 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048';
        } elseif ($this->isMethod('put') || $this->isMethod('patch')) {
            // For update method - image is optional
            /**
             * The logo image file.
             * @var UploadedFile|null $logo_path
             *  "updated_setting_logo.jpg"
             */
            $rules['logo_path'] = 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048';

            /**
             * The favicon image file.
             * @var UploadedFile|null $favicon_path
             *  "updated_setting_favicon.jpg"
             */
            $rules['favicon_path'] = 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048';
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
            'title.required' => 'The website title is required.',
            'email.email' => 'The email address must be a valid email format.',
            'logo_path.image' => 'The logo must be an image file.',
            'logo_path.mimes' => 'The logo must be a file of type: jpeg, png, jpg, gif, svg, webp.',
            'logo_path.max' => 'The logo may not be greater than 2MB.',
            'favicon_path.image' => 'The favicon must be an image file.',
            'favicon_path.mimes' => 'The favicon must be a file of type: jpeg, png, jpg, gif, svg, webp.',
            'favicon_path.max' => 'The favicon may not be greater than 2MB.',
            'decimal_place.min' => 'The decimal place must be at least 0.',
            'decimal_place.max' => 'The decimal place may not be greater than 4.',
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

        // Merge boolean values for image removal flags
        $this->merge([
            'logo_path_removed' => $this->boolean('logo_path_removed'),
            'favicon_path_removed' => $this->boolean('favicon_path_removed'),
        ]);
    }

    /**
     * Get custom attributes for validator errors.
     *
     * @return array<string, string>
     */
    public function attributes(): array
    {
        return [
            'title' => 'website title',
            'academy_code' => 'academy code',
            'meta_title' => 'meta title',
            'meta_description' => 'meta description',
            'meta_keywords' => 'meta keywords',
            'logo_path' => 'logo image',
            'favicon_path' => 'favicon image',
            'phone' => 'phone number',
            'email' => 'email address',
            'fax' => 'fax number',
            'address' => 'address',
            'language' => 'language',
            'date_format' => 'date format',
            'time_format' => 'time format',
            'week_start' => 'week start day',
            'time_zone' => 'time zone',
            'currency' => 'currency',
            'currency_symbol' => 'currency symbol',
            'decimal_place' => 'decimal place',
            'copyright_text' => 'copyright text',
            'status' => 'status',
        ];
    }
}
