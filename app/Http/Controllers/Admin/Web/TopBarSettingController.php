<?php

namespace App\Http\Controllers\Admin\Web;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Web\TopBarSettingRequest;
use App\Http\Resources\Web\TopbarSettingResource;
use App\Services\Web\TopbarSettingService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Throwable;

/**
 * @tags Topbar Settings
 */
class TopBarSettingController extends Controller
{
    protected TopbarSettingService $topbarSettingService;

    public function __construct(TopbarSettingService $topbarSettingService)
    {
        $this->topbarSettingService = $topbarSettingService;
    }

    /**
     * Display the Topbar Setting resource.
     *
     * @param Request $request
     * @return TopbarSettingResource|JsonResponse
     */
    public function index(Request $request): TopbarSettingResource|JsonResponse
    {
        try {
            $topBarSetting = $this->topbarSettingService->getTopbarSettingContent($request);

            if ($topBarSetting) {
                return Response::success(new TopbarSettingResource($topBarSetting), 'Top bar Setting retrieved successfully.');
            }

            return Response::notFound('No Topbar Setting data found.');
        } catch (Throwable $e) {
            return Response::error('Failed to retrieve Topbar Setting: ' . $e->getMessage(), 500);
        }
    }

    /**
     * Store or update the Topbar Setting resource.
     * This method handles both creation and updating of the single Topbar Setting entry.
     *
     * @param TopBarSettingRequest $request The validated request.
     * @return TopbarSettingResource|JsonResponse
     */
    public function store(TopBarSettingRequest $request): TopbarSettingResource|JsonResponse
    {
        try {
            $topBarSetting = $this->topbarSettingService->saveTopbarSettingContent($request->validated());

            $message = $topBarSetting->wasRecentlyCreated ? 'Top bar Setting created successfully.' : 'Top bar Setting updated successfully.';
            $statusCode = $topBarSetting->wasRecentlyCreated ? 201 : 200;

            return Response::success(new TopbarSettingResource($topBarSetting->load(['createdBy', 'updatedBy'])), $message, $statusCode);
        } catch (Throwable $e) {
            return Response::error('Failed to save Topbar Setting: ' . $e->getMessage(), 500);
        }
    }
}
