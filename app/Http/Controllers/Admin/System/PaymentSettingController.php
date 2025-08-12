<?php

namespace App\Http\Controllers\Admin\System;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\System\PaymentSettingRequest;
use App\Http\Resources\System\PaymentSettingResource;
use App\Services\System\PaymentSettingService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Throwable;

/**
 * @tags Payment Settings
 */
class PaymentSettingController extends Controller
{
    protected PaymentSettingService $paymentSettingService;

    public function __construct(PaymentSettingService $paymentSettingService)
    {
        $this->paymentSettingService = $paymentSettingService;
    }

    /**
     * Display the Payment Settings resource.
     *
     * @param Request $request
     * @return PaymentSettingResource|JsonResponse
     */
    public function index(Request $request): PaymentSettingResource|JsonResponse
    {
        try {
            $paymentSetting = $this->paymentSettingService->getPaymentSettingsContent($request);

            if ($paymentSetting) {
                return Response::success(new PaymentSettingResource($paymentSetting), 'Payment Settings retrieved successfully.');
            }

            return Response::notFound('No Payment Settings content found.');
        } catch (Throwable $e) {
            return Response::error('Failed to retrieve Payment Settings content: ' . $e->getMessage(), 500);
        }
    }

    /**
     * Store or update the Payment Settings resource.
     * This method handles both creation and updating of the single Payment Settings entry.
     *
     * @param PaymentSettingRequest $request The validated request.
     * @return PaymentSettingResource|JsonResponse
     */
    public function store(PaymentSettingRequest $request): PaymentSettingResource|JsonResponse
    {
        try {
            $paymentSetting = $this->paymentSettingService->savePaymentSettingsContent($request->validated(), $request);

            $message = $paymentSetting->wasRecentlyCreated ? 'Payment Settings content created successfully.' : 'Payment Settings content updated successfully.';
            $statusCode = $paymentSetting->wasRecentlyCreated ? 201 : 200;

            return Response::success(new PaymentSettingResource($paymentSetting->load(['createdBy', 'updatedBy'])), $message, $statusCode);
        } catch (Throwable $e) {
            return Response::error('Failed to save Payment Settings content: ' . $e->getMessage(), 500);
        }
    }
}
