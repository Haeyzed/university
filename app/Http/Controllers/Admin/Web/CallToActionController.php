<?php

namespace App\Http\Controllers\Admin\Web;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Web\CallToActionRequest;
use App\Http\Resources\Web\CallToActionResource;
use App\Services\Web\CallToActionService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Throwable;

/**
 * @tags Call To Action
 */
class CallToActionController extends Controller
{
    protected CallToActionService $callToActionService;

    public function __construct(CallToActionService $callToActionService)
    {
        $this->callToActionService = $callToActionService;
    }

    /**
     * Display the Call To Action resource.
     *
     * @param Request $request
     * @return CallToActionResource|JsonResponse
     */
    public function index(Request $request): CallToActionResource|JsonResponse
    {
        try {
            $callToAction = $this->callToActionService->getCallToActionContent($request);

            if ($callToAction) {
                return Response::success(new CallToActionResource($callToAction), 'Call To Action retrieved successfully.');
            }

            return Response::notFound('No Call To Action content found.');
        } catch (Throwable $e) {
            return Response::error('Failed to retrieve Call To Action content: ' . $e->getMessage(), 500);
        }
    }

    /**
     * Store or update the Call To Action resource.
     * This method handles both creation and updating of the single Call To Action entry.
     *
     * @param CallToActionRequest $request The validated request.
     * @return CallToActionResource|JsonResponse
     */
    public function store(CallToActionRequest $request): CallToActionResource|JsonResponse
    {
        try {
            $callToAction = $this->callToActionService->saveCallToActionContent($request->validated(), $request);

            $message = $callToAction->wasRecentlyCreated ? 'Call To Action content created successfully.' : 'Call To Action content updated successfully.';
            $statusCode = $callToAction->wasRecentlyCreated ? 201 : 200;

            return Response::success(new CallToActionResource($callToAction->load(['createdBy', 'updatedBy'])), $message, $statusCode);
        } catch (Throwable $e) {
            return Response::error('Failed to save Call To Action content: ' . $e->getMessage(), 500);
        }
    }
}
