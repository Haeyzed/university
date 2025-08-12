<?php

namespace App\Services\System;

use App\Models\Setting;
use App\Traits\FileUploader;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SettingService
{
    use FileUploader;

    /**
     * Retrieve the single Setting content.
     *
     * @param Request $request
     * @return Setting|null
     */
    public function getSettingContent(Request $request): ?Setting
    {
        $query = Setting::query()
            ->with(['createdBy', 'updatedBy'])
            ->when($request->boolean('include_trashed'), fn($q) => $q->withTrashed())
            ->when($request->boolean('only_trashed'), fn($q) => $q->onlyTrashed());

        return $query->first();
    }

    /**
     * Store or update the single Setting content.
     *
     * @param array $data
     * @param Request $request
     * @return Setting
     * @throws Exception
     */
    public function saveSettingContent(array $data, Request $request): Setting
    {
        return DB::transaction(function () use ($data, $request) {
            $setting = Setting::query()->firstOrNew();

            $setting->fill($data);
            $setting->status = $data['status'] ?? true;

            // Handle logo image upload/update
            $setting->logo_path = $this->updateImage(
                $request,
                'logo_path',
                'settings',
                null, // No specific width constraint
                null, // No specific height constraint
                $setting,
                'logo_path',
            );

            // Handle favicon image upload/update
            $setting->favicon_path = $this->updateImage(
                $request,
                'favicon_path',
                'settings',
                null, // No specific width constraint
                null, // No specific height constraint
                $setting,
                'favicon_path',
            );

            // Set created_by/updated_by if user is authenticated
            if (auth()->check()) {
                if (!$setting->exists) {
                    $setting->created_by = auth()->id();
                }
                $setting->updated_by = auth()->id();
            }

            $setting->save();

            return $setting;
        });
    }
}
