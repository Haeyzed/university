<?php

namespace App\Services\System;

use App\Models\System\PaymentSetting;
use App\Traits\EnvironmentVariable;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PaymentSettingService
{
    use EnvironmentVariable;

    /**
     * Retrieve the Payment Settings content.
     *
     * @param Request $request
     * @return PaymentSetting|null
     */
    public function getPaymentSettingsContent(Request $request): ?PaymentSetting
    {
        $query = PaymentSetting::query()
            ->with(['createdBy', 'updatedBy'])
            ->when($request->boolean('include_trashed'), fn($q) => $q->withTrashed())
            ->when($request->boolean('only_trashed'), fn($q) => $q->onlyTrashed());

        return $query->first();
    }

    /**
     * Store or update the Payment Settings content.
     *
     * @param array $data
     * @param Request $request
     * @return PaymentSetting
     * @throws Exception
     */
    public function savePaymentSettingsContent(array $data, Request $request): PaymentSetting
    {
        return DB::transaction(function () use ($data, $request) {
            $paymentSetting = PaymentSetting::query()->firstOrNew();

            $paymentSetting->fill($data);
            $paymentSetting->status = $data['status'] ?? true;

            // Set created_by/updated_by if user is authenticated
            if (auth()->check()) {
                if (!$paymentSetting->exists) {
                    $paymentSetting->created_by = auth()->id();
                }
                $paymentSetting->updated_by = auth()->id();
            }

            $paymentSetting->save();

            // Update environment variables based on the selected gateway
            $this->updateEnvVariable('PAYMENT_GATEWAY', '"'.$request->payment_gateway.'"' ?? '"none"');

            switch ($request->payment_gateway) {
                case 'paypal':
                    $this->updateEnvVariable('PAYPAL_CLIENT_ID', '"'.$request->paypal_client_id.'"' ?? '"none"');
                    $this->updateEnvVariable('PAYPAL_SECRET', '"'.$request->paypal_secret.'"' ?? '"none"');
                    // Clear other gateway variables
                    $this->updateEnvVariable('STRIPE_KEY', '"none"');
                    $this->updateEnvVariable('STRIPE_SECRET', '"none"');
                    $this->updateEnvVariable('RAZORPAY_KEY', '"none"');
                    $this->updateEnvVariable('RAZORPAY_SECRET', '"none"');
                    $this->updateEnvVariable('PAYSTACK_KEY', '"none"');
                    $this->updateEnvVariable('PAYSTACK_SECRET', '"none"');
                    $this->updateEnvVariable('MERCHANT_EMAIL', '"none"');
                    $this->updateEnvVariable('FLW_PUBLIC_KEY', '"none"');
                    $this->updateEnvVariable('FLW_SECRET_KEY', '"none"');
                    $this->updateEnvVariable('FLW_SECRET_HASH', '"none"');
                    $this->updateEnvVariable('SKRILL_EMAIL', '"none"');
                    $this->updateEnvVariable('SKRILL_SECRET', '"none"');
                    break;
                case 'stripe':
                    $this->updateEnvVariable('STRIPE_KEY', '"'.$request->stripe_key.'"' ?? '"none"');
                    $this->updateEnvVariable('STRIPE_SECRET', '"'.$request->stripe_secret.'"' ?? '"none"');
                    // Clear other gateway variables
                    $this->updateEnvVariable('PAYPAL_CLIENT_ID', '"none"');
                    $this->updateEnvVariable('PAYPAL_SECRET', '"none"');
                    $this->updateEnvVariable('RAZORPAY_KEY', '"none"');
                    $this->updateEnvVariable('RAZORPAY_SECRET', '"none"');
                    $this->updateEnvVariable('PAYSTACK_KEY', '"none"');
                    $this->updateEnvVariable('PAYSTACK_SECRET', '"none"');
                    $this->updateEnvVariable('MERCHANT_EMAIL', '"none"');
                    $this->updateEnvVariable('FLW_PUBLIC_KEY', '"none"');
                    $this->updateEnvVariable('FLW_SECRET_KEY', '"none"');
                    $this->updateEnvVariable('FLW_SECRET_HASH', '"none"');
                    $this->updateEnvVariable('SKRILL_EMAIL', '"none"');
                    $this->updateEnvVariable('SKRILL_SECRET', '"none"');
                    break;
                case 'razorpay':
                    $this->updateEnvVariable('RAZORPAY_KEY', '"'.$request->razorpay_key.'"' ?? '"none"');
                    $this->updateEnvVariable('RAZORPAY_SECRET', '"'.$request->razorpay_secret.'"' ?? '"none"');
                    // Clear other gateway variables
                    $this->updateEnvVariable('PAYPAL_CLIENT_ID', '"none"');
                    $this->updateEnvVariable('PAYPAL_SECRET', '"none"');
                    $this->updateEnvVariable('STRIPE_KEY', '"none"');
                    $this->updateEnvVariable('STRIPE_SECRET', '"none"');
                    $this->updateEnvVariable('PAYSTACK_KEY', '"none"');
                    $this->updateEnvVariable('PAYSTACK_SECRET', '"none"');
                    $this->updateEnvVariable('MERCHANT_EMAIL', '"none"');
                    $this->updateEnvVariable('FLW_PUBLIC_KEY', '"none"');
                    $this->updateEnvVariable('FLW_SECRET_KEY', '"none"');
                    $this->updateEnvVariable('FLW_SECRET_HASH', '"none"');
                    $this->updateEnvVariable('SKRILL_EMAIL', '"none"');
                    $this->updateEnvVariable('SKRILL_SECRET', '"none"');
                    break;
                case 'paystack':
                    $this->updateEnvVariable('PAYSTACK_KEY', '"'.$request->paystack_key.'"' ?? '"none"');
                    $this->updateEnvVariable('PAYSTACK_SECRET', '"'.$request->paystack_secret.'"' ?? '"none"');
                    $this->updateEnvVariable('MERCHANT_EMAIL', '"'.$request->merchant_email.'"' ?? '"none"');
                    // Clear other gateway variables
                    $this->updateEnvVariable('PAYPAL_CLIENT_ID', '"none"');
                    $this->updateEnvVariable('PAYPAL_SECRET', '"none"');
                    $this->updateEnvVariable('STRIPE_KEY', '"none"');
                    $this->updateEnvVariable('STRIPE_SECRET', '"none"');
                    $this->updateEnvVariable('RAZORPAY_KEY', '"none"');
                    $this->updateEnvVariable('RAZORPAY_SECRET', '"none"');
                    $this->updateEnvVariable('FLW_PUBLIC_KEY', '"none"');
                    $this->updateEnvVariable('FLW_SECRET_KEY', '"none"');
                    $this->updateEnvVariable('FLW_SECRET_HASH', '"none"');
                    $this->updateEnvVariable('SKRILL_EMAIL', '"none"');
                    $this->updateEnvVariable('SKRILL_SECRET', '"none"');
                    break;
                case 'flutterwave':
                    $this->updateEnvVariable('FLW_PUBLIC_KEY', '"'.$request->flutterwave_public_key.'"' ?? '"none"');
                    $this->updateEnvVariable('FLW_SECRET_KEY', '"'.$request->flutterwave_secret_key.'"' ?? '"none"');
                    $this->updateEnvVariable('FLW_SECRET_HASH', '"'.$request->flutterwave_secret_hash.'"' ?? '"none"');
                    // Clear other gateway variables
                    $this->updateEnvVariable('PAYPAL_CLIENT_ID', '"none"');
                    $this->updateEnvVariable('PAYPAL_SECRET', '"none"');
                    $this->updateEnvVariable('STRIPE_KEY', '"none"');
                    $this->updateEnvVariable('STRIPE_SECRET', '"none"');
                    $this->updateEnvVariable('RAZORPAY_KEY', '"none"');
                    $this->updateEnvVariable('RAZORPAY_SECRET', '"none"');
                    $this->updateEnvVariable('PAYSTACK_KEY', '"none"');
                    $this->updateEnvVariable('PAYSTACK_SECRET', '"none"');
                    $this->updateEnvVariable('MERCHANT_EMAIL', '"none"');
                    $this->updateEnvVariable('SKRILL_EMAIL', '"none"');
                    $this->updateEnvVariable('SKRILL_SECRET', '"none"');
                    break;
                case 'skrill':
                    $this->updateEnvVariable('SKRILL_EMAIL', '"'.$request->skrill_email.'"' ?? '"none"');
                    $this->updateEnvVariable('SKRILL_SECRET', '"'.$request->skrill_secret.'"' ?? '"none"');
                    // Clear other gateway variables
                    $this->updateEnvVariable('PAYPAL_CLIENT_ID', '"none"');
                    $this->updateEnvVariable('PAYPAL_SECRET', '"none"');
                    $this->updateEnvVariable('STRIPE_KEY', '"none"');
                    $this->updateEnvVariable('STRIPE_SECRET', '"none"');
                    $this->updateEnvVariable('RAZORPAY_KEY', '"none"');
                    $this->updateEnvVariable('RAZORPAY_SECRET', '"none"');
                    $this->updateEnvVariable('PAYSTACK_KEY', '"none"');
                    $this->updateEnvVariable('PAYSTACK_SECRET', '"none"');
                    $this->updateEnvVariable('MERCHANT_EMAIL', '"none"');
                    $this->updateEnvVariable('FLW_PUBLIC_KEY', '"none"');
                    $this->updateEnvVariable('FLW_SECRET_KEY', '"none"');
                    $this->updateEnvVariable('FLW_SECRET_HASH', '"none"');
                    break;
                case 'none':
                default:
                    // Clear all payment gateway variables if 'none' is selected
                    $this->updateEnvVariable('PAYPAL_CLIENT_ID', '"none"');
                    $this->updateEnvVariable('PAYPAL_SECRET', '"none"');
                    $this->updateEnvVariable('STRIPE_KEY', '"none"');
                    $this->updateEnvVariable('STRIPE_SECRET', '"none"');
                    $this->updateEnvVariable('RAZORPAY_KEY', '"none"');
                    $this->updateEnvVariable('RAZORPAY_SECRET', '"none"');
                    $this->updateEnvVariable('PAYSTACK_KEY', '"none"');
                    $this->updateEnvVariable('PAYSTACK_SECRET', '"none"');
                    $this->updateEnvVariable('MERCHANT_EMAIL', '"none"');
                    $this->updateEnvVariable('FLW_PUBLIC_KEY', '"none"');
                    $this->updateEnvVariable('FLW_SECRET_KEY', '"none"');
                    $this->updateEnvVariable('FLW_SECRET_HASH', '"none"');
                    $this->updateEnvVariable('SKRILL_EMAIL', '"none"');
                    $this->updateEnvVariable('SKRILL_SECRET', '"none"');
                    break;
            }

            return $paymentSetting;
        });
    }
}
