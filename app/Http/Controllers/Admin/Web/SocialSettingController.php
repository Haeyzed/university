<?php

namespace App\Http\Controllers\Admin\Web;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Web\SocialSettingRequest;
use App\Http\Resources\Web\SocialSettingResource;
use App\Services\Web\SocialSettingService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Throwable;

/**
 * @tags Social Settings
 */
class SocialSettingController extends Controller
{
    protected SocialSettingService $socialSettingService;

    public function __construct(SocialSettingService $socialSettingService)
    {
        $this->socialSettingService = $socialSettingService;
    }

    /**
     * Display the Social Setting resource.
     *
     * @param Request $request
     * @return SocialSettingResource|JsonResponse
     */
    public function index(Request $request): SocialSettingResource|JsonResponse
    {
        try {
            $socialSetting = $this->socialSettingService->getSocialSettingContent($request);

            if ($socialSetting) {
                return Response::success(new SocialSettingResource($socialSetting), 'Social Setting retrieved successfully.');
            }

            return Response::notFound('No Social Setting data found.');
        } catch (Throwable $e) {
            return Response::error('Failed to retrieve Social Setting content: ' . $e->getMessage(), 500);
        }
    }

    /**
     * Store or update the Social Setting resource.
     * This method handles both creation and updating of the single Social Setting entry.
     *
     * @param SocialSettingRequest $request The validated request.
     * @return SocialSettingResource|JsonResponse
     */
    public function store(SocialSettingRequest $request): SocialSettingResource|JsonResponse
    {
        try {
            $socialSetting = $this->socialSettingService->saveSocialSettingContent($request->validated());

            $message = $socialSetting->wasRecentlyCreated ? 'Social Setting content created successfully.' : 'Social Setting content updated successfully.';
            $statusCode = $socialSetting->wasRecentlyCreated ? 201 : 200;

            return Response::success(new SocialSettingResource($socialSetting->load(['createdBy', 'updatedBy'])), $message, $statusCode);
        } catch (Throwable $e) {
            return Response::error('Failed to save Social Setting content: ' . $e->getMessage(), 500);
        }
    }
}
