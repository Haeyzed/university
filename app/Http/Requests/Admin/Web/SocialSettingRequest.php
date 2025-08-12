<?php

namespace App\Http\Requests\Admin\Web;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\ValidationRule;

/**
 * @class SocialSettingRequest
 * @brief Request for managing social media links.
 *
 * This model represents the 'social_settings' form request
 * for public display on the website.
 *
 * @property string|null $facebook The Facebook profile URL.
 * @property string|null $twitter The Twitter profile URL.
 * @property string|null $linkedin The LinkedIn profile URL.
 * @property string|null $instagram The Instagram profile URL.
 * @property string|null $pinterest The Pinterest profile URL.
 * @property string|null $youtube The YouTube channel URL.
 * @property string|null $tiktok The TikTok profile URL.
 * @property string|null $skype The Skype username.
 * @property string|null $telegram The Telegram username or link.
 * @property string|null $whatsapp The WhatsApp number or link.
 * @property bool|null $status The publication status of the social settings.
 */
class SocialSettingRequest extends FormRequest
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
             * The Facebook profile URL.
             * @var string|null $facebook
             * @example "https://facebook.com/universityofexample"
             */
            'facebook' => 'nullable|url|max:255',

            /**
             * The Twitter profile URL.
             * @var string|null $twitter
             * @example "https://twitter.com/universityofexample"
             */
            'twitter' => 'nullable|url|max:255',

            /**
             * The LinkedIn profile URL.
             * @var string|null $linkedin
             * @example "https://linkedin.com/company/universityofexample"
             */
            'linkedin' => 'nullable|url|max:255',

            /**
             * The Instagram profile URL.
             * @var string|null $instagram
             * @example "https://instagram.com/universityofexample"
             */
            'instagram' => 'nullable|url|max:255',

            /**
             * The Pinterest profile URL.
             * @var string|null $pinterest
             * @example "https://pinterest.com/universityofexample"
             */
            'pinterest' => 'nullable|url|max:255',

            /**
             * The YouTube channel URL.
             * @var string|null $youtube
             * @example "https://youtube.com/channel/universityofexample"
             */
            'youtube' => 'nullable|url|max:255',

            /**
             * The TikTok profile URL.
             * @var string|null $tiktok
             * @example "https://tiktok.com/@universityofexample"
             */
            'tiktok' => 'nullable|url|max:255',

            /**
             * The Skype username.
             * @var string|null $skype
             * @example "live:universityofexample"
             */
            'skype' => 'nullable|string|max:255',

            /**
             * The Telegram username or link.
             * @var string|null $telegram
             * @example "https://t.me/universityofexample"
             */
            'telegram' => 'nullable|string|max:255',

            /**
             * The WhatsApp number or link.
             * @var string|null $whatsapp
             * @example "https://wa.me/1234567890"
             */
            'whatsapp' => 'nullable|string|max:255',

            /**
             * The publication status of the social settings.
             * @var bool $status
             * @example true
             */
            'status' => 'sometimes|boolean',
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'facebook.url' => 'The Facebook link must be a valid URL.',
            'twitter.url' => 'The Twitter link must be a valid URL.',
            'linkedin.url' => 'The LinkedIn link must be a valid URL.',
            'instagram.url' => 'The Instagram link must be a valid URL.',
            'pinterest.url' => 'The Pinterest link must be a valid URL.',
            'youtube.url' => 'The YouTube link must be a valid URL.',
            'tiktok.url' => 'The TikTok link must be a valid URL.',
            'skype.max' => 'The Skype field may not be greater than 255 characters.',
            'telegram.max' => 'The Telegram field may not be greater than 255 characters.',
            'whatsapp.max' => 'The WhatsApp field may not be greater than 255 characters.',
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
            'facebook' => 'Facebook URL',
            'twitter' => 'Twitter URL',
            'linkedin' => 'LinkedIn URL',
            'instagram' => 'Instagram URL',
            'pinterest' => 'Pinterest URL',
            'youtube' => 'YouTube URL',
            'tiktok' => 'TikTok URL',
            'skype' => 'Skype username',
            'telegram' => 'Telegram username/link',
            'whatsapp' => 'WhatsApp number/link',
            'status' => 'publication status',
        ];
    }
}
