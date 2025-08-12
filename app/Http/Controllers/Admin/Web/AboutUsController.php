<?php

namespace App\Http\Controllers\Admin\Web;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Web\AboutUsRequest;
use App\Http\Resources\Web\AboutUsResource;
use App\Services\Web\AboutUsService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Throwable;

/**
 * @tags About Us
 */
class AboutUsController extends Controller
{
    protected AboutUsService $aboutUsService;

    public function __construct(AboutUsService $aboutUsService)
    {
        $this->aboutUsService = $aboutUsService;
    }

    /**
     * Display the About Us resource.
     *
     * @param Request $request
     * @return AboutUsResource|JsonResponse
     */
    public function index(Request $request): AboutUsResource|JsonResponse
    {
        try {
            $aboutUs = $this->aboutUsService->getAboutUsContent($request);

            if ($aboutUs) {
                return Response::success(new AboutUsResource($aboutUs), 'About Us retrieved successfully.');
            }

            return Response::notFound('No About Us content found.');
        } catch (Throwable $e) {
            return Response::error('Failed to retrieve About Us content: ' . $e->getMessage(), 500);
        }
    }

    /**
     * Store or update the About Us resource.
     * This method handles both creation and updating of the single About Us entry.
     *
     * @param AboutUsRequest $request The validated request.
     * @return AboutUsResource|JsonResponse
     */
    public function store(AboutUsRequest $request): AboutUsResource|JsonResponse
    {
        try {
            $aboutUs = $this->aboutUsService->saveAboutUsContent($request->validated(), $request);

            $message = $aboutUs->wasRecentlyCreated ? 'About Us content created successfully.' : 'About Us content updated successfully.';
            $statusCode = $aboutUs->wasRecentlyCreated ? 201 : 200;

            return Response::success(new AboutUsResource($aboutUs->load(['createdBy', 'updatedBy'], $message, $statusCode)));
        } catch (Throwable $e) {
            return Response::error('Failed to save About Us content: ' . $e->getMessage(), 500);
        }
    }
}
