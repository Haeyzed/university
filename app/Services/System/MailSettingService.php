<?php

namespace App\Services\System;

use App\Models\MailSetting;
use App\Traits\EnvironmentVariable;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MailSettingService
{
    use EnvironmentVariable;

    /**
     * Retrieve the Mail Settings content.
     *
     * @param Request $request
     * @return MailSetting|null
     */
    public function getMailSettingsContent(Request $request): ?MailSetting
    {
        $query = MailSetting::query()
            ->with(['createdBy', 'updatedBy'])
            ->when($request->boolean('include_trashed'), fn($q) => $q->withTrashed())
            ->when($request->boolean('only_trashed'), fn($q) => $q->onlyTrashed());

        return $query->first();
    }

    /**
     * Store or update the Mail Settings content and update .env variables.
     *
     * @param array $data
     * @param Request $request
     * @return MailSetting
     * @throws Exception
     */
    public function saveMailSettingsContent(array $data, Request $request): MailSetting
    {
        return DB::transaction(function () use ($data, $request) {
            $mailSetting = MailSetting::query()->firstOrNew();

            $mailSetting->fill($data);
            $mailSetting->status = $data['status'] ?? true;

            // Set created_by/updated_by if user is authenticated
            if (auth()->check()) {
                if (!$mailSetting->exists) {
                    $mailSetting->created_by = auth()->id();
                }
                $mailSetting->updated_by = auth()->id();
            }

            $mailSetting->save();

            // Update environment variables
            $this->updateEnvVariable('MAIL_MAILER', '"' . ($data['driver'] ?? 'null') . '"');
            $this->updateEnvVariable('MAIL_HOST', '"' . ($data['host'] ?? 'null') . '"');
            $this->updateEnvVariable('MAIL_PORT', '"' . ($data['port'] ?? 'null') . '"');
            $this->updateEnvVariable('MAIL_USERNAME', '"' . ($data['username'] ?? 'null') . '"');
            $this->updateEnvVariable('MAIL_PASSWORD', '"' . ($data['password'] ?? 'null') . '"');
            $this->updateEnvVariable('MAIL_ENCRYPTION', '"' . ($data['encryption'] ?? 'null') . '"');
            $this->updateEnvVariable('MAIL_FROM_ADDRESS', '"' . ($data['sender_email'] ?? 'null') . '"');
            $this->updateEnvVariable('MAIL_FROM_NAME', '"' . ($data['sender_name'] ?? 'null') . '"');
            $this->updateEnvVariable('MAIL_REPLY_TO_ADDRESS', '"' . ($data['reply_email'] ?? 'null') . '"');


            return $mailSetting;
        });
    }
}
