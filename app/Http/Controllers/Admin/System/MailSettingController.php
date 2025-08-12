<?php

namespace App\Http\Controllers\Admin\System;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\System\MailSettingRequest;
use App\Http\Resources\System\MailSettingResource;
use App\Services\System\MailSettingService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Throwable;

/**
 * @tags Mail Settings
 */
class MailSettingController extends Controller
{
    protected MailSettingService $mailSettingService;

    public function __construct(MailSettingService $mailSettingService)
    {
        $this->mailSettingService = $mailSettingService;
    }

    /**
     * Display the Mail Settings resource.
     *
     * @param Request $request
     * @return MailSettingResource|JsonResponse
     */
    public function index(Request $request): MailSettingResource|JsonResponse
    {
        try {
            $mailSetting = $this->mailSettingService->getMailSettingsContent($request);

            if ($mailSetting) {
                return Response::success(new MailSettingResource($mailSetting), 'Mail Settings retrieved successfully.');
            }

            return Response::notFound('No Mail Settings content found.');
        } catch (Throwable $e) {
            return Response::error('Failed to retrieve Mail Settings content: ' . $e->getMessage(), 500);
        }
    }

    /**
     * Store or update the Mail Settings resource.
     * This method handles both creation and updating of the single Mail Settings entry.
     *
     * @param MailSettingRequest $request The validated request.
     * @return MailSettingResource|JsonResponse
     */
    public function store(MailSettingRequest $request): MailSettingResource|JsonResponse
    {
        try {
            $mailSetting = $this->mailSettingService->saveMailSettingsContent($request->validated(), $request);

            $message = $mailSetting->wasRecentlyCreated ? 'Mail Settings created successfully.' : 'Mail Settings updated successfully.';
            $statusCode = $mailSetting->wasRecentlyCreated ? 201 : 200;

            return Response::success(new MailSettingResource($mailSetting->load(['createdBy', 'updatedBy'])), $message, $statusCode);
        } catch (Throwable $e) {
            return Response::error('Failed to save Mail Settings content: ' . $e->getMessage(), 500);
        }
    }
}
