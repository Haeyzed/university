<?php

namespace App\Services\Web;

use App\Models\Web\TopbarSetting;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Throwable;

class TopbarSettingService
{
    /**
     * Retrieve the Topbar Setting content.
     *
     * @param Request $request
     * @return TopbarSetting|null
     */
    public function getTopbarSettingContent(Request $request): ?TopbarSetting
    {
        $query = TopbarSetting::query()
            ->when($request->boolean('include_trashed'), fn($q) => $q->withTrashed())
            ->when($request->boolean('only_trashed'), fn($q) => $q->onlyTrashed());

        return $query->first();
    }

    /**
     * Store or update the Topbar Setting content.
     *
     * @param array $data
     * @return TopbarSetting
     * @throws Exception
     */
    public function saveTopbarSettingContent(array $data): TopbarSetting
    {
        return DB::transaction(function () use ($data) {
            $topbarSetting = TopbarSetting::query()->firstOrNew(); // Always get the single instance

            $topbarSetting->fill($data);
            $topbarSetting->status = $data['status'] ?? true;
            $topbarSetting->social_status = $data['social_status'] ?? true;

            // Set created_by/updated_by if user is authenticated
            if (auth()->check()) {
                if (!$topbarSetting->exists) {
                    $topbarSetting->created_by = auth()->id();
                }
                $topbarSetting->updated_by = auth()->id();
            }

            $topbarSetting->save();

            return $topbarSetting;
        });
    }
}
