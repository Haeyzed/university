<?php

namespace App\Services\Web;

use App\Models\Web\SocialSetting;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SocialSettingService
{
    /**
     * Retrieve the Social Setting content.
     *
     * @param Request $request
     * @return SocialSetting|null
     */
    public function getSocialSettingContent(Request $request): ?SocialSetting
    {
        $query = SocialSetting::query()
            ->with(['createdBy', 'updatedBy'])
            ->when($request->boolean('include_trashed'), fn($q) => $q->withTrashed())
            ->when($request->boolean('only_trashed'), fn($q) => $q->onlyTrashed());

        return $query->first();
    }

    /**
     * Store or update the Social Setting content.
     *
     * @param array $data
     * @return SocialSetting
     * @throws Exception
     */
    public function saveSocialSettingContent(array $data): SocialSetting
    {
        return DB::transaction(function () use ($data) {
            $socialSetting = SocialSetting::query()->firstOrNew();

            $socialSetting->fill($data);
            $socialSetting->status = $data['status'] ?? true;

            // Set created_by/updated_by if user is authenticated
            if (auth()->check()) {
                if (!$socialSetting->exists) {
                    $socialSetting->created_by = auth()->id();
                }
                $socialSetting->updated_by = auth()->id();
            }

            $socialSetting->save();

            return $socialSetting;
        });
    }
}
