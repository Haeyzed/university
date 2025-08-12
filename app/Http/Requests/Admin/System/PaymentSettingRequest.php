<?php

namespace App\Http\Requests\Admin\System;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

/**
 * @class PaymentSettingRequest
 * @brief Request for managing payment gateway settings.
 *
 * This model represents the 'payment_settings' form request
 * for configuring various payment gateways.
 *
 * @property string $payment_gateway The selected payment gateway.
 * @property string|null $paypal_client_id PayPal Client ID.
 * @property string|null $paypal_secret PayPal Secret.
 * @property string|null $stripe_key Stripe Publishable Key.
 * @property string|null $stripe_secret Stripe Secret Key.
 * @property string|null $razorpay_key Razorpay Key ID.
 * @property string|null $razorpay_secret Razorpay Key Secret.
 * @property string|null $paystack_key Paystack Public Key.
 * @property string|null $paystack_secret Paystack Secret Key.
 * @property string|null $merchant_email Paystack Merchant Email.
 * @property string|null $flutterwave_public_key Flutterwave Public Key.
 * @property string|null $flutterwave_secret_key Flutterwave Secret Key.
 * @property string|null $flutterwave_secret_hash Flutterwave Secret Hash.
 * @property string|null $skrill_email Skrill Merchant Email.
 * @property string|null $skrill_secret Skrill Secret Word.
 * @property bool|null $status The status of the payment setting.
 */
class PaymentSettingRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            /**
             * The selected payment gateway.
             * @var string $payment_gateway
             * @example "stripe"
             */
            'payment_gateway' => [
                'required',
                'string',
                Rule::in(['paypal', 'stripe', 'razorpay', 'paystack', 'flutterwave', 'skrill', 'none']),
            ],

            /**
             * PayPal Client ID.
             * @var string|null $paypal_client_id
             * @example "A_YOUR_PAYPAL_CLIENT_ID_EXAMPLE"
             */
            'paypal_client_id' => 'nullable|string|max:255',

            /**
             * PayPal Secret.
             * @var string|null $paypal_secret
             * @example "E_YOUR_PAYPAL_SECRET_EXAMPLE"
             */
            'paypal_secret' => 'nullable|string|max:255',

            /**
             * Stripe Publishable Key.
             * @var string|null $stripe_key
             * @example "pk_test_YOUR_STRIPE_PUBLISHABLE_KEY_EXAMPLE"
             */
            'stripe_key' => 'nullable|string|max:255',

            /**
             * Stripe Secret Key.
             * @var string|null $stripe_secret
             * @example "sk_test_YOUR_STRIPE_SECRET_KEY_EXAMPLE"
             */
            'stripe_secret' => 'nullable|string|max:255',

            /**
             * Razorpay Key ID.
             * @var string|null $razorpay_key
             * @example "rzp_test_YOUR_RAZORPAY_KEY_ID_EXAMPLE"
             */
            'razorpay_key' => 'nullable|string|max:255',

            /**
             * Razorpay Key Secret.
             * @var string|null $razorpay_secret
             * @example "YOUR_RAZORPAY_KEY_SECRET_EXAMPLE"
             */
            'razorpay_secret' => 'nullable|string|max:255',

            /**
             * Paystack Public Key.
             * @var string|null $paystack_key
             * @example "pk_test_YOUR_PAYSTACK_PUBLIC_KEY_EXAMPLE"
             */
            'paystack_key' => 'nullable|string|max:255',

            /**
             * Paystack Secret Key.
             * @var string|null $paystack_secret
             * @example "sk_test_YOUR_PAYSTACK_SECRET_KEY_EXAMPLE"
             */
            'paystack_secret' => 'nullable|string|max:255',

            /**
             * Paystack Merchant Email.
             * @var string|null $merchant_email
             * @example "merchant@example.com"
             */
            'merchant_email' => 'nullable|email|max:255',

            /**
             * Flutterwave Public Key.
             * @var string|null $flutterwave_public_key
             * @example "FLWPUBK_TEST-YOUR_FLUTTERWAVE_PUBLIC_KEY_EXAMPLE"
             */
            'flutterwave_public_key' => 'nullable|string|max:255',

            /**
             * Flutterwave Secret Key.
             * @var string|null $flutterwave_secret_key
             * @example "FLWSECK_TEST-YOUR_FLUTTERWAVE_SECRET_KEY_EXAMPLE"
             */
            'flutterwave_secret_key' => 'nullable|string|max:255',

            /**
             * Flutterwave Secret Hash.
             * @var string|null $flutterwave_secret_hash
             * @example "YOUR_FLUTTERWAVE_SECRET_HASH_EXAMPLE"
             */
            'flutterwave_secret_hash' => 'nullable|string|max:255',

            /**
             * Skrill Merchant Email.
             * @var string|null $skrill_email
             * @example "skrill_merchant@example.com"
             */
            'skrill_email' => 'nullable|email|max:255',

            /**
             * Skrill Secret Word.
             * @var string|null $skrill_secret
             * @example "YOUR_SKRILL_SECRET_WORD_EXAMPLE"
             */
            'skrill_secret' => 'nullable|string|max:255',

            /**
             * The status of the payment setting.
             * @var bool $status
             * @example true
             */
            'status' => 'sometimes|boolean',
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'payment_gateway.required' => 'The payment gateway is required.',
            'payment_gateway.in' => 'The selected payment gateway is invalid.',
            'merchant_email.email' => 'The merchant email must be a valid email address.',
            'skrill_email.email' => 'The Skrill email must be a valid email address.',
        ];
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        // Set default status if not provided
        if (!$this->has('status')) {
            $this->merge([
                'status' => true,
            ]);
        }

        $gateway = $this->input('payment_gateway');
        // Nullify fields not relevant to the selected gateway
        $fieldsToNullify = [
            'paypal_client_id', 'paypal_secret',
            'stripe_key', 'stripe_secret',
            'razorpay_key', 'razorpay_secret',
            'paystack_key', 'paystack_secret', 'merchant_email',
            'flutterwave_public_key', 'flutterwave_secret_key', 'flutterwave_secret_hash',
            'skrill_email', 'skrill_secret',
        ];

        $data = $this->all();
        foreach ($fieldsToNullify as $field) {
            // Check if the field is not part of the selected gateway's configuration
            // This logic needs to be more robust to handle partial matches or specific fields
            // For simplicity, we'll check if the field name starts with the gateway name
            // or if it's a specific field like merchant_email or skrill_email
            $isGatewaySpecificField = str_starts_with($field, $gateway);
            $isMerchantEmailForPaystack = ($field === 'merchant_email' && $gateway === 'paystack');
            $isSkrillEmailForSkrill = ($field === 'skrill_email' && $gateway === 'skrill');

            if (!$isGatewaySpecificField && !$isMerchantEmailForPaystack && !$isSkrillEmailForSkrill) {
                $data[$field] = null;
            }
        }

        $this->replace($data);
    }

    /**
     * Get custom attributes for validator errors.
     *
     * @return array<string, string>
     */
    public function attributes(): array
    {
        return [
            'payment_gateway' => 'payment gateway',
            'paypal_client_id' => 'PayPal Client ID',
            'paypal_secret' => 'PayPal Secret',
            'stripe_key' => 'Stripe Key',
            'stripe_secret' => 'Stripe Secret',
            'razorpay_key' => 'Razorpay Key',
            'razorpay_secret' => 'Razorpay Secret',
            'paystack_key' => 'Paystack Key',
            'paystack_secret' => 'Paystack Secret',
            'merchant_email' => 'Paystack Merchant Email',
            'flutterwave_public_key' => 'Flutterwave Public Key',
            'flutterwave_secret_key' => 'Flutterwave Secret Key',
            'flutterwave_secret_hash' => 'Flutterwave Secret Hash',
            'skrill_email' => 'Skrill Email',
            'skrill_secret' => 'Skrill Secret',
            'status' => 'status',
        ];
    }
}
