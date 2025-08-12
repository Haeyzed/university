<?php

namespace App\Http\Requests\Admin\System;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

/**
 * @class SmsSettingRequest
 * @brief Request for managing SMS gateway settings.
 *
 * This model represents the 'sms_settings' form request
 * for configuring SMS sending capabilities.
 *
 * @property string $sms_gateway The selected SMS gateway.
 * @property string|null $vonage_key Vonage API Key.
 * @property string|null $vonage_secret Vonage API Secret.
 * @property string|null $vonage_number Vonage Sender Number.
 * @property string|null $twilio_sid Twilio Account SID.
 * @property string|null $twilio_auth_token Twilio Auth Token.
 * @property string|null $twilio_number Twilio Sender Number.
 * @property string|null $africas_talking_username AfricasTalking Username.
 * @property string|null $africas_talking_api_key AfricasTalking API Key.
 * @property string|null $textlocal_key TextLocal API Key.
 * @property string|null $textlocal_sender TextLocal Sender Name.
 * @property string|null $clickatell_api_key Clickatell API Key.
 * @property string|null $smscountry_username SMSCountry Username.
 * @property string|null $smscountry_password SMSCountry Password.
 * @property string|null $smscountry_sender_id SMSCountry Sender ID.
 * @property bool|null $status The status of the SMS settings.
 */
class SmsSettingRequest extends FormRequest
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
             * The selected SMS gateway.
             * @var string $sms_gateway
             * @example "twilio"
             */
            'sms_gateway' => 'required|string|in:none,vonage,twilio,africastalking,textlocal,clickatell,smscountry',

            /**
             * Vonage API Key.
             * @var string|null $vonage_key
             * @example "VONAGE_API_KEY_EXAMPLE"
             */
            'vonage_key' => 'nullable|string|max:255',
            /**
             * Vonage API Secret.
             * @var string|null $vonage_secret
             * @example "VONAGE_API_SECRET_EXAMPLE"
             */
            'vonage_secret' => 'nullable|string|max:255',
            /**
             * Vonage Sender Number.
             * @var string|null $vonage_number
             * @example "1234567890"
             */
            'vonage_number' => 'nullable|string|max:255',

            /**
             * Twilio Account SID.
             * @var string|null $twilio_sid
             * @example "ACxxxxxxxxxxxxxxxxxxxxxxxxxxxxx"
             */
            'twilio_sid' => 'nullable|string|max:255',
            /**
             * Twilio Auth Token.
             * @var string|null $twilio_auth_token
             * @example "your_twilio_auth_token"
             */
            'twilio_auth_token' => 'nullable|string|max:255',
            /**
             * Twilio Sender Number.
             * @var string|null $twilio_number
             * @example "+1501712266"
             */
            'twilio_number' => 'nullable|string|max:255',

            /**
             * AfricasTalking Username.
             * @var string|null $africas_talking_username
             * @example "sandbox"
             */
            'africas_talking_username' => 'nullable|string|max:255',
            /**
             * AfricasTalking API Key.
             * @var string|null $africas_talking_api_key
             * @example "AFRICASTALKING_API_KEY_EXAMPLE"
             */
            'africas_talking_api_key' => 'nullable|string|max:255',

            /**
             * TextLocal API Key.
             * @var string|null $textlocal_key
             * @example "TEXTLOCAL_API_KEY_EXAMPLE"
             */
            'textlocal_key' => 'nullable|string|max:255',
            /**
             * TextLocal Sender Name.
             * @var string|null $textlocal_sender
             * @example "TXTLCL"
             */
            'textlocal_sender' => 'nullable|string|max:255',

            /**
             * Clickatell API Key.
             * @var string|null $clickatell_api_key
             * @example "CLICKATELL_API_KEY_EXAMPLE"
             */
            'clickatell_api_key' => 'nullable|string|max:255',

            /**
             * SMSCountry Username.
             * @var string|null $smscountry_username
             * @example "SMSCOUNTRY_USERNAME_EXAMPLE"
             */
            'smscountry_username' => 'nullable|string|max:255',
            /**
             * SMSCountry Password.
             * @var string|null $smscountry_password
             * @example "SMSCOUNTRY_PASSWORD_EXAMPLE"
             */
            'smscountry_password' => 'nullable|string|max:255',
            /**
             * SMSCountry Sender ID.
             * @var string|null $smscountry_sender_id
             * @example "SMSCountry"
             */
            'smscountry_sender_id' => 'nullable|string|max:255',

            /**
             * The status of the SMS settings.
             * @var bool $status
             * @example true
             */
            'status' => 'sometimes|boolean',
        ];

        // Conditional validation based on sms_gateway
        switch ($this->input('sms_gateway')) {
            case 'vonage':
                $rules['vonage_key'] = 'required|string|max:255';
                $rules['vonage_secret'] = 'required|string|max:255';
                $rules['vonage_number'] = 'required|string|max:255';
                break;
            case 'twilio':
                $rules['twilio_sid'] = 'required|string|max:255';
                $rules['twilio_auth_token'] = 'required|string|max:255';
                $rules['twilio_number'] = 'required|string|max:255';
                break;
            case 'africastalking':
                $rules['africas_talking_username'] = 'required|string|max:255';
                $rules['africas_talking_api_key'] = 'required|string|max:255';
                break;
            case 'textlocal':
                $rules['textlocal_key'] = 'required|string|max:255';
                $rules['textlocal_sender'] = 'required|string|max:255';
                break;
            case 'clickatell':
                $rules['clickatell_api_key'] = 'required|string|max:255';
                break;
            case 'smscountry':
                $rules['smscountry_username'] = 'required|string|max:255';
                $rules['smscountry_password'] = 'required|string|max:255';
                $rules['smscountry_sender_id'] = 'required|string|max:255';
                break;
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
            'sms_gateway.required' => 'The SMS gateway is required.',
            'sms_gateway.in' => 'The selected SMS gateway is invalid.',
            'vonage_key.required' => 'The Vonage API Key is required when Vonage is selected.',
            'vonage_secret.required' => 'The Vonage API Secret is required when Vonage is selected.',
            'vonage_number.required' => 'The Vonage Sender Number is required when Vonage is selected.',
            'twilio_sid.required' => 'The Twilio Account SID is required when Twilio is selected.',
            'twilio_auth_token.required' => 'The Twilio Auth Token is required when Twilio is selected.',
            'twilio_number.required' => 'The Twilio Sender Number is required when Twilio is selected.',
            'africas_talking_username.required' => 'The AfricasTalking Username is required when AfricasTalking is selected.',
            'africas_talking_api_key.required' => 'The AfricasTalking API Key is required when AfricasTalking is selected.',
            'textlocal_key.required' => 'The TextLocal API Key is required when TextLocal is selected.',
            'textlocal_sender.required' => 'The TextLocal Sender Name is required when TextLocal is selected.',
            'clickatell_api_key.required' => 'The Clickatell API Key is required when Clickatell is selected.',
            'smscountry_username.required' => 'The SMSCountry Username is required when SMSCountry is selected.',
            'smscountry_password.required' => 'The SMSCountry Password is required when SMSCountry is selected.',
            'smscountry_sender_id.required' => 'The SMSCountry Sender ID is required when SMSCountry is selected.',
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

        // Ensure sensitive fields are null if not the selected gateway
        $gateway = $this->input('sms_gateway');
        $fieldsToNullify = [
            'vonage' => ['vonage_key', 'vonage_secret', 'vonage_number'],
            'twilio' => ['twilio_sid', 'twilio_auth_token', 'twilio_number'],
            'africastalking' => ['africas_talking_username', 'africas_talking_api_key'],
            'textlocal' => ['textlocal_key', 'textlocal_sender'],
            'clickatell' => ['clickatell_api_key'],
            'smscountry' => ['smscountry_username', 'smscountry_password', 'smscountry_sender_id'],
        ];

        foreach ($fieldsToNullify as $g => $fields) {
            if ($g !== $gateway) {
                foreach ($fields as $field) {
                    if ($this->has($field)) {
                        $this->merge([$field => null]);
                    }
                }
            }
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
            'sms_gateway' => 'SMS gateway',
            'vonage_key' => 'Vonage API Key',
            'vonage_secret' => 'Vonage API Secret',
            'vonage_number' => 'Vonage Sender Number',
            'twilio_sid' => 'Twilio Account SID',
            'twilio_auth_token' => 'Twilio Auth Token',
            'twilio_number' => 'Twilio Sender Number',
            'africas_talking_username' => 'AfricasTalking Username',
            'africas_talking_api_key' => 'AfricasTalking API Key',
            'textlocal_key' => 'TextLocal API Key',
            'textlocal_sender' => 'TextLocal Sender Name',
            'clickatell_api_key' => 'Clickatell API Key',
            'smscountry_username' => 'SMSCountry Username',
            'smscountry_password' => 'SMSCountry Password',
            'smscountry_sender_id' => 'SMSCountry Sender ID',
            'status' => 'status',
        ];
    }
}
