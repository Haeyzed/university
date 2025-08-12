<?php

namespace App\Services\Web;

use App\Models\Web\CallToAction;
use App\Traits\FileUploader;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CallToActionService
{
    use FileUploader;

    /**
     * Retrieve the Call To Action content.
     *
     * @param Request $request
     * @return CallToAction|null
     */
    public function getCallToActionContent(Request $request): ?CallToAction
    {
        $query = CallToAction::query()
            ->with(['createdBy', 'updatedBy'])
            ->when($request->boolean('include_trashed'), fn($q) => $q->withTrashed())
            ->when($request->boolean('only_trashed'), fn($q) => $q->onlyTrashed());

        return $query->first();
    }

    /**
     * Store or update the Call To Action content.
     *
     * @param array $data
     * @param Request $request
     * @return CallToAction
     * @throws Exception
     */
    public function saveCallToActionContent(array $data, Request $request): CallToAction
    {
        return DB::transaction(function () use ($data, $request) {
            $callToAction = CallToAction::query()->firstOrNew();

            $callToAction->fill($data);
            $callToAction->status = $data['status'] ?? true;

            // Handle main image upload/update
            $callToAction->image = $this->updateImage($request, 'image', 'call-to-action', 500, 280, $callToAction, 'image');
            // Handle background image upload/update
            $callToAction->bg_image = $this->updateImage($request, 'bg_image', 'call-to-action', 1920, 1080, $callToAction, 'bg_image');

            // Set created_by/updated_by if user is authenticated
            if (auth()->check()) {
                if (!$callToAction->exists) {
                    $callToAction->created_by = auth()->id();
                }
                $callToAction->updated_by = auth()->id();
            }

            $callToAction->save();

            return $callToAction;
        });
    }
}
