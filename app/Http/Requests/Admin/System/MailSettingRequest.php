<?php

namespace App\Http\Requests\Admin\System;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

/**
 * @class MailSettingRequest
 * @brief Request for managing mail server settings.
 *
 * This model represents the 'mail_settings' form request.
 *
 * @property string $driver The mail driver (e.g., "smtp", "mailgun").
 * @property string $host The mail host.
 * @property string $port The mail port.
 * @property string $username The mail username.
 * @property string $password The mail password.
 * @property string $encryption The mail encryption type (e.g., "tls", "ssl").
 * @property string|null $sender_email The sender's email address.
 * @property string|null $sender_name The sender's name.
 * @property string|null $reply_email The reply-to email address.
 * @property bool|null $status The status of the mail setting.
 */
class MailSettingRequest extends FormRequest
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
             * The mail driver (e.g., "smtp", "mailgun").
             * @var string $driver
             * @example "smtp"
             */
            'driver' => 'required|string|max:255',

            /**
             * The mail host.
             * @var string $host
             * @example "smtp.mailtrap.io"
             */
            'host' => 'required|string|max:255',

            /**
             * The mail port.
             * @var string $port
             * @example "2525"
             */
            'port' => 'required|string|max:255',

            /**
             * The mail username.
             * @var string $username
             * @example "your_username"
             */
            'username' => 'required|string|max:255',

            /**
             * The mail password.
             * @var string $password
             * @example "your_password"
             */
            'password' => 'required|string|max:255',

            /**
             * The mail encryption type (e.g., "tls", "ssl").
             * @var string $encryption
             * @example "tls"
             */
            'encryption' => 'required|string|max:255',

            /**
             * The sender's email address.
             * @var string|null $sender_email
             * @example "noreply@example.com"
             */
            'sender_email' => 'nullable|email|max:255',

            /**
             * The sender's name.
             * @var string|null $sender_name
             * @example "University Support"
             */
            'sender_name' => 'nullable|string|max:255',

            /**
             * The reply-to email address.
             * @var string|null $reply_email
             * @example "support@example.com"
             */
            'reply_email' => 'nullable|email|max:255',

            /**
             * The status of the mail setting.
             * @var bool $status
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
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'driver.required' => 'The mail driver is required.',
            'host.required' => 'The mail host is required.',
            'port.required' => 'The mail port is required.',
            'username.required' => 'The mail username is required.',
            'password.required' => 'The mail password is required.',
            'encryption.required' => 'The mail encryption is required.',
            'sender_email.email' => 'The sender email must be a valid email address.',
            'reply_email.email' => 'The reply email must be a valid email address.',
        ];
    }

    /**
     * Get custom attributes for validator errors.
     *
     * @return array<string, string>
     */
    public function attributes(): array
    {
        return [
            'driver' => 'mail driver',
            'host' => 'mail host',
            'port' => 'mail port',
            'username' => 'mail username',
            'password' => 'mail password',
            'encryption' => 'mail encryption',
            'sender_email' => 'sender email',
            'sender_name' => 'sender name',
            'reply_email' => 'reply email',
            'status' => 'status',
        ];
    }
}
