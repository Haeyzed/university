<?php

namespace App\Http\Requests\Admin\Web;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

/**
 * @class TopBarSettingRequest
 * @brief Request for managing website topbar settings.
 *
 * This model represents the 'topbar_settings' form request
 * for public display on the website.
 *
 * @property string|null $address_title The title for the address in the topbar.
 * @property string|null $address The address displayed in the topbar.
 * @property string $email The email address displayed in the topbar.
 * @property string $phone The phone number displayed in the topbar.
 * @property string|null $working_hour The working hours displayed in the topbar.
 * @property string|null $about_title The title for the 'About' section in the topbar.
 * @property string|null $about_summery A summary for the 'About' section in the topbar.
 * @property string|null $social_title The title for the social media section in the topbar.
 * @property bool|null $social_status The status of the social media section.
 * @property bool|null $status The publication status of the topbar setting.
 */
class TopBarSettingRequest extends FormRequest
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
        return [
            /**
             * The title for the address in the topbar.
             * @var string|null $address_title
             * @example "Our Location"
             */
            'address_title' => 'nullable|string|max:255',
            /**
             * The address displayed in the topbar.
             * @var string|null $address
             * @example "123 University St, City, Country"
             */
            'address' => 'nullable|string|max:255',
            /**
             * The email address displayed in the topbar.
             * @var string $email
             * @example "info@university.edu"
             */
            'email' => 'required|email|max:255',
            /**
             * The phone number displayed in the topbar.
             * @var string $phone
             * @example "+1234567890"
             */
            'phone' => 'required|string|max:255',
            /**
             * The working hours displayed in the topbar.
             * @var string|null $working_hour
             * @example "Mon-Fri: 9 AM - 5 PM"
             */
            'working_hour' => 'nullable|string|max:255',
            /**
             * The title for the 'About' section in the topbar.
             * @var string|null $about_title
             * @example "About Us"
             */
            'about_title' => 'nullable|string|max:255',
            /**
             * A summary for the 'About' section in the topbar.
             * @var string|null $about_summery
             * @example "Leading education since 1990."
             */
            'about_summery' => 'nullable|string|max:500',
            /**
             * The title for the social media section in the topbar.
             * @var string|null $social_title
             * @example "Follow Us"
             */
            'social_title' => 'nullable|string|max:255',
            /**
             * The status of the social media section.
             * @var bool|null $social_status
             * @example true
             */
            'social_status' => 'nullable|boolean',
            /**
             * The publication status of the topbar setting.
             * @var bool|null $status
             * @example true
             */
            'status' => 'sometimes|boolean',
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
        // Set default social_status if not provided
        if (!$this->has('social_status')) {
            $this->merge([
                'social_status' => true,
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
            'address_title' => 'address title',
            'address' => 'address',
            'email' => 'email address',
            'phone' => 'phone number',
            'working_hour' => 'working hour',
            'about_title' => 'about title',
            'about_summery' => 'about summary',
            'social_title' => 'social title',
            'social_status' => 'social status',
            'status' => 'status',
        ];
    }
}
