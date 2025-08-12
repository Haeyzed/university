<?php

namespace App\Http\Resources\System;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Carbon;

/**
 * Class SmsSettingResource
 *
 * @property int $id The unique identifier for the SMS setting.
 * @property string $sms_gateway The selected SMS gateway (e.g., twilio, vonage, africastalking).
 * @property string|null $vonage_key The Vonage API key.
 * @property string|null $vonage_secret Masked Vonage API secret.
 * @property string|null $vonage_number The Vonage sender number.
 * @property string|null $twilio_sid The Twilio account SID.
 * @property string|null $twilio_auth_token Masked Twilio Auth Token.
 * @property string|null $twilio_number The Twilio sender number.
 * @property string|null $africas_talking_username The Africa's Talking username.
 * @property string|null $africas_talking_api_key Masked Africa's Talking API key.
 * @property string|null $textlocal_key Masked TextLocal API key.
 * @property string|null $textlocal_sender The TextLocal sender name.
 * @property string|null $clickatell_api_key Masked Clickatell API key.
 * @property string|null $smscountry_username The SMSCountry username.
 * @property string|null $smscountry_password Masked SMSCountry password.
 * @property string|null $smscountry_sender_id The SMSCountry sender ID.
 * @property bool $status The status of the SMS setting (true for active).
 * @property int|null $created_by The ID of the user who created the setting.
 * @property string|null $created_by_name The name of the user who created the setting.
 * @property int|null $updated_by The ID of the user who last updated the setting.
 * @property string|null $updated_by_name The name of the user who last updated the setting.
 * @property string $created_at The creation timestamp in "Y-m-d H:i:s" format.
 * @property string $updated_at The last updated timestamp in "Y-m-d H:i:s" format.
 * @property string|null $deleted_at The deletion timestamp, if soft-deleted.
 */
class SmsSettingResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            /**
             * The unique identifier for the SMS setting.
             * @var int $id
             * @example 1
             */
            'id' => $this->id,

            /**
             * The selected SMS gateway.
             * @var string $sms_gateway
             * @example "twilio"
             */
            'sms_gateway' => $this->sms_gateway,

            /**
             * Vonage API Key.
             * @var string|null $vonage_key
             */
            'vonage_key' => $this->vonage_key,
            /**
             * Vonage API Secret.
             * @var string|null $vonage_secret
             */
            'vonage_secret' => $this->when($this->sms_gateway === 'vonage', '********'), // Mask sensitive data
            /**
             * Vonage Sender Number.
             * @var string|null $vonage_number
             */
            'vonage_number' => $this->vonage_number,

            /**
             * Twilio Account SID.
             * @var string|null $twilio_sid
             */
            'twilio_sid' => $this->twilio_sid,
            /**
             * Twilio Auth Token.
             * @var string|null $twilio_auth_token
             */
            'twilio_auth_token' => $this->when($this->sms_gateway === 'twilio', '********'), // Mask sensitive data
            /**
             * Twilio Sender Number.
             * @var string|null $twilio_number
             */
            'twilio_number' => $this->twilio_number,

            /**
             * AfricasTalking Username.
             * @var string|null $africas_talking_username
             */
            'africas_talking_username' => $this->africas_talking_username,
            /**
             * AfricasTalking API Key.
             * @var string|null $africas_talking_api_key
             */
            'africas_talking_api_key' => $this->when($this->sms_gateway === 'africastalking', '********'), // Mask sensitive data

            /**
             * TextLocal API Key.
             * @var string|null $textlocal_key
             */
            'textlocal_key' => $this->when($this->sms_gateway === 'textlocal', '********'), // Mask sensitive data
            /**
             * TextLocal Sender Name.
             * @var string|null $textlocal_sender
             */
            'textlocal_sender' => $this->textlocal_sender,

            /**
             * Clickatell API Key.
             * @var string|null $clickatell_api_key
             */
            'clickatell_api_key' => $this->when($this->sms_gateway === 'clickatell', '********'), // Mask sensitive data

            /**
             * SMSCountry Username.
             * @var string|null $smscountry_username
             */
            'smscountry_username' => $this->smscountry_username,
            /**
             * SMSCountry Password.
             * @var string|null $smscountry_password
             */
            'smscountry_password' => $this->when($this->sms_gateway === 'smscountry', '********'), // Mask sensitive data
            /**
             * SMSCountry Sender ID.
             * @var string|null $smscountry_sender_id
             */
            'smscountry_sender_id' => $this->smscountry_sender_id,

            /**
             * The status of the SMS setting (true for active, false for inactive).
             * @var bool $status
             * @example true
             */
            'status' => (bool)$this->status,

            /**
             * The ID of the user who created the record.
             * @var int|null $created_by
             * @example 1
             */
            'created_by' => $this->created_by,

            /**
             * The name of the user who created the record.
             * @var string|null $created_by_name
             * @example "Admin User"
             */
            'created_by_name' => $this->whenLoaded('createdBy', fn() => $this->createdBy->name),

            /**
             * The ID of the user who last updated the record.
             * @var int|null $updated_by
             * @example 1
             */
            'updated_by' => $this->updated_by,

            /**
             * The name of the user who last updated the record.
             * @var string|null $updated_by_name
             * @example "Admin User"
             */
            'updated_by_name' => $this->whenLoaded('updatedBy', fn() => $this->updatedBy->name),

            /**
             * The timestamp when the record was created.
             * @var string $created_at
             * @example "2024-07-19 12:00:00"
             */
            'created_at' => Carbon::parse($this->created_at)->format('Y-m-d H:i:s'),

            /**
             * The timestamp when the record was last updated.
             * @var string $updated_at
             * @example "2024-07-19 12:30:00"
             */
            'updated_at' => Carbon::parse($this->updated_at)->format('Y-m-d H:i:s'),

            /**
             * The timestamp when the record was last deleted.
             * @var string|null $deleted_at
             * @example "2024-07-19 12:30:00"
             */
            'deleted_at' => $this->deleted_at ? Carbon::parse($this->deleted_at)->format('Y-m-d H:i:s') : null,
        ];
    }
}
