<?php

namespace App\Services\System;

use App\Models\SmsSetting;
use App\Traits\EnvironmentVariable;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SmsSettingService
{
    use EnvironmentVariable;

    /**
     * Retrieve the SMS settings content.
     *
     * @param Request $request
     * @return SmsSetting|null
     */
    public function getSmsSettingsContent(Request $request): ?SmsSetting
    {
        $query = SmsSetting::query()
            ->with(['createdBy', 'updatedBy'])
            ->when($request->boolean('include_trashed'), fn($q) => $q->withTrashed())
            ->when($request->boolean('only_trashed'), fn($q) => $q->onlyTrashed());

        return $query->first();
    }

    /**
     * Store or update the SMS settings content.
     *
     * @param array $data
     * @param Request $request
     * @return SmsSetting
     * @throws Exception
     */
    public function saveSmsSettingsContent(array $data, Request $request): SmsSetting
    {
        return DB::transaction(function () use ($data, $request) {
            $smsSetting = SmsSetting::query()->firstOrNew();

            $smsSetting->fill($data);
            $smsSetting->status = $data['status'] ?? true;

            // Set created_by/updated_by if user is authenticated
            if (auth()->check()) {
                if (!$smsSetting->exists) {
                    $smsSetting->created_by = auth()->id();
                }
                $smsSetting->updated_by = auth()->id();
            }

            $smsSetting->save();

            // Update environment variables
            $this->updateEnvVariable('SMS_GATEWAY', '"'.$request->sms_gateway.'"' ?? '"none"');
            $this->updateEnvVariable('VONAGE_KEY', '"'.$request->vonage_key.'"' ?? '"none"');
            $this->updateEnvVariable('VONAGE_SECRET', '"'.$request->vonage_secret.'"' ?? '"none"');
            $this->updateEnvVariable('VONAGE_NUMBER', '"'.$request->vonage_number.'"' ?? '"none"');
            $this->updateEnvVariable('TWILIO_SID', '"'.$request->twilio_sid.'"' ?? '"none"');
            $this->updateEnvVariable('TWILIO_AUTH_TOKEN', '"'.$request->twilio_auth_token.'"' ?? '"none"');
            $this->updateEnvVariable('TWILIO_NUMBER', '"'.$request->twilio_number.'"' ?? '"none"');
            $this->updateEnvVariable('AFRICASTALKING_USERNAME', '"'.$request->africas_talking_username.'"' ?? '"none"');
            $this->updateEnvVariable('AFRICASTALKING_API_KEY', '"'.$request->africas_talking_api_key.'"' ?? '"none"');
            $this->updateEnvVariable('TEXT_LOCAL_KEY', '"'.$request->textlocal_key.'"' ?? '"none"');
            $this->updateEnvVariable('TEXT_LOCAL_SENDER', '"'.$request->textlocal_sender.'"' ?? '"none"');
            $this->updateEnvVariable('CLICKATELL_API_KEY', '"'.$request->clickatell_api_key.'"' ?? '"none"');
            $this->updateEnvVariable('SMSCOUNTRY_USER', '"'.$request->smscountry_username.'"' ?? '"none"');
            $this->updateEnvVariable('SMSCOUNTRY_PASSWORD', '"'.$request->smscountry_password.'"' ?? '"none"');
            $this->updateEnvVariable('SMSCOUNTRY_SENDER_ID', '"'.$request->smscountry_sender_id.'"' ?? '"none"');

            return $smsSetting;
        });
    }
}
