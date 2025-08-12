<?php

namespace App\Http\Controllers\Admin\System;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\System\SettingRequest;
use App\Http\Resources\System\SettingResource;
use App\Services\System\SettingService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Throwable;

/**
 * @tags Settings
 */
class SettingController extends Controller
{
    protected SettingService $settingService;

    public function __construct(SettingService $settingService)
    {
        $this->settingService = $settingService;
    }

    /**
     * Display the single Setting resource.
     *
     * @param Request $request
     * @return SettingResource|JsonResponse
     */
    public function index(Request $request): SettingResource|JsonResponse
    {
        try {
            $setting = $this->settingService->getSettingContent($request);

            if ($setting) {
                return Response::success(new SettingResource($setting), 'Settings retrieved successfully.');
            }

            return Response::notFound('No settings content found.');
        } catch (Throwable $e) {
            return Response::error('Failed to retrieve settings: ' . $e->getMessage(), 500);
        }
    }

    /**
     * Store or update the single Setting resource.
     * This method handles both creation and updating of the single Setting entry.
     *
     * @param SettingRequest $request The validated request.
     * @return SettingResource|JsonResponse
     */
    public function store(SettingRequest $request): SettingResource|JsonResponse
    {
        try {
            $setting = $this->settingService->saveSettingContent($request->validated(), $request);

            $message = $setting->wasRecentlyCreated ? 'Settings created successfully.' : 'Settings updated successfully.';
            $statusCode = $setting->wasRecentlyCreated ? 201 : 200;

            return Response::success(new SettingResource($setting->load(['createdBy', 'updatedBy'])), $message, $statusCode);
        } catch (Throwable $e) {
            return Response::error('Failed to save settings: ' . $e->getMessage(), 500);
        }
    }
}
