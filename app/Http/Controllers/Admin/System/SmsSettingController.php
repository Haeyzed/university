<?php

namespace App\Http\Controllers\Admin\System;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\System\SmsSettingRequest;
use App\Http\Resources\System\SmsSettingResource;
use App\Services\System\SmsSettingService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Throwable;

/**
 * @tags SMS Settings
 */
class SmsSettingController extends Controller
{
    protected SmsSettingService $smsSettingService;

    public function __construct(SmsSettingService $smsSettingService)
    {
        $this->smsSettingService = $smsSettingService;
    }

    /**
     * Display the SMS Settings resource.
     *
     * @param Request $request
     * @return SmsSettingResource|JsonResponse
     */
    public function index(Request $request): SmsSettingResource|JsonResponse
    {
        try {
            $smsSetting = $this->smsSettingService->getSmsSettingsContent($request);

            if ($smsSetting) {
                return Response::success(new SmsSettingResource($smsSetting), 'SMS Settings retrieved successfully.');
            }

            return Response::notFound('No SMS Settings content found.');
        } catch (Throwable $e) {
            return Response::error('Failed to retrieve SMS Settings content: ' . $e->getMessage(), 500);
        }
    }

    /**
     * Store or update the SMS Settings resource.
     * This method handles both creation and updating of the single SMS Settings entry.
     *
     * @param SmsSettingRequest $request The validated request.
     * @return SmsSettingResource|JsonResponse
     */
    public function store(SmsSettingRequest $request): SmsSettingResource|JsonResponse
    {
        try {
            $smsSetting = $this->smsSettingService->saveSmsSettingsContent($request->validated(), $request);

            $message = $smsSetting->wasRecentlyCreated ? 'SMS Settings created successfully.' : 'SMS Settings updated successfully.';
            $statusCode = $smsSetting->wasRecentlyCreated ? 201 : 200;

            return Response::success(new SmsSettingResource($smsSetting->load(['createdBy', 'updatedBy'])), $message, $statusCode);
        } catch (Throwable $e) {
            return Response::error('Failed to save SMS Settings content: ' . $e->getMessage(), 500);
        }
    }
}
