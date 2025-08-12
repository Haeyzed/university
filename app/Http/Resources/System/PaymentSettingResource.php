<?php

namespace App\Http\Resources\System;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Carbon;

/**
 * Class PaymentSettingResource
 *
 * @property int $id The unique identifier for the payment setting.
 * @property string $payment_gateway The selected payment gateway (e.g., paypal, stripe, razorpay).
 * @property string|null $paypal_client_id PayPal Client ID.
 * @property string|null $paypal_secret Masked PayPal Secret.
 * @property string|null $stripe_key Stripe Publishable Key.
 * @property string|null $stripe_secret Masked Stripe Secret Key.
 * @property string|null $razorpay_key Razorpay Key ID.
 * @property string|null $razorpay_secret Masked Razorpay Key Secret.
 * @property string|null $paystack_key Paystack Public Key.
 * @property string|null $paystack_secret Masked Paystack Secret Key.
 * @property string|null $merchant_email Paystack Merchant Email.
 * @property string|null $flutterwave_public_key Flutterwave Public Key.
 * @property string|null $flutterwave_secret_key Masked Flutterwave Secret Key.
 * @property string|null $flutterwave_secret_hash Masked Flutterwave Secret Hash.
 * @property string|null $skrill_email Skrill Merchant Email.
 * @property string|null $skrill_secret Masked Skrill Secret Word.
 * @property bool $status Indicates if the payment setting is active.
 * @property int|null $created_by ID of the user who created the setting.
 * @property string|null $created_by_name Name of the user who created the setting.
 * @property int|null $updated_by ID of the user who last updated the setting.
 * @property string|null $updated_by_name Name of the user who last updated the setting.
 * @property string $created_at Timestamp of creation (Y-m-d H:i:s).
 * @property string $updated_at Timestamp of last update (Y-m-d H:i:s).
 * @property string|null $deleted_at Timestamp of soft deletion, if applicable (Y-m-d H:i:s).
 */

class PaymentSettingResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            /**
             * The unique identifier for the payment setting.
             * @var int $id
             * @example 1
             */
            'id' => $this->id,

            /**
             * The selected payment gateway.
             * @var string $payment_gateway
             * @example "stripe"
             */
            'payment_gateway' => $this->payment_gateway,

            /**
             * PayPal Client ID.
             * @var string|null $paypal_client_id
             * @example "sb-example-client-id"
             */
            'paypal_client_id' => $this->paypal_client_id,

            /**
             * PayPal Secret (masked).
             * @var string|null $paypal_secret
             * @example "********"
             */
            'paypal_secret' => $this->paypal_secret ? '********' : null,

            /**
             * Stripe Publishable Key.
             * @var string|null $stripe_key
             * @example "pk_test_example"
             */
            'stripe_key' => $this->stripe_key,

            /**
             * Stripe Secret Key (masked).
             * @var string|null $stripe_secret
             * @example "********"
             */
            'stripe_secret' => $this->stripe_secret ? '********' : null,

            /**
             * Razorpay Key ID.
             * @var string|null $razorpay_key
             * @example "rzp_test_example"
             */
            'razorpay_key' => $this->razorpay_key,

            /**
             * Razorpay Key Secret (masked).
             * @var string|null $razorpay_secret
             * @example "********"
             */
            'razorpay_secret' => $this->razorpay_secret ? '********' : null,

            /**
             * Paystack Public Key.
             * @var string|null $paystack_key
             * @example "pk_test_example"
             */
            'paystack_key' => $this->paystack_key,

            /**
             * Paystack Secret Key (masked).
             * @var string|null $paystack_secret
             * @example "********"
             */
            'paystack_secret' => $this->paystack_secret ? '********' : null,

            /**
             * Paystack Merchant Email.
             * @var string|null $merchant_email
             * @example "merchant@example.com"
             */
            'merchant_email' => $this->merchant_email,

            /**
             * Flutterwave Public Key.
             * @var string|null $flutterwave_public_key
             * @example "FLWPUBK_TEST-example"
             */
            'flutterwave_public_key' => $this->flutterwave_public_key,

            /**
             * Flutterwave Secret Key (masked).
             * @var string|null $flutterwave_secret_key
             * @example "********"
             */
            'flutterwave_secret_key' => $this->flutterwave_secret_key ? '********' : null,

            /**
             * Flutterwave Secret Hash (masked).
             * @var string|null $flutterwave_secret_hash
             * @example "********"
             */
            'flutterwave_secret_hash' => $this->flutterwave_secret_hash ? '********' : null,

            /**
             * Skrill Merchant Email.
             * @var string|null $skrill_email
             * @example "skrill@example.com"
             */
            'skrill_email' => $this->skrill_email,

            /**
             * Skrill Secret Word (masked).
             * @var string|null $skrill_secret
             * @example "********"
             */
            'skrill_secret' => $this->skrill_secret ? '********' : null,

            /**
             * The status of the payment setting (true for active, false for inactive).
             * @var bool $status
             * @example true
             */
            'status' => (bool)$this->status,

            /**
             * The ID of the user who created the record.
             * @var int|null $created_by
             * @example 1
             */
            'created_by' => $this->created_by,

            /**
             * The name of the user who created the record.
             * @var string|null $created_by_name
             * @example "Admin User"
             */
            'created_by_name' => $this->whenLoaded('createdBy', fn() => $this->createdBy->name),

            /**
             * The ID of the user who last updated the record.
             * @var int|null $updated_by
             * @example 1
             */
            'updated_by' => $this->updated_by,

            /**
             * The name of the user who last updated the record.
             * @var string|null $updated_by_name
             * @example "Admin User"
             */
            'updated_by_name' => $this->whenLoaded('updatedBy', fn() => $this->updatedBy->name),

            /**
             * The timestamp when the record was created.
             * @var string $created_at
             * @example "2024-07-19 12:00:00"
             */
            'created_at' => Carbon::parse($this->created_at)->format('Y-m-d H:i:s'),

            /**
             * The timestamp when the record was last updated.
             * @var string $updated_at
             * @example "2024-07-19 12:30:00"
             */
            'updated_at' => Carbon::parse($this->updated_at)->format('Y-m-d H:i:s'),

            /**
             * The timestamp when the record was last deleted.
             * @var string|null $deleted_at
             * @example "2024-07-19 12:30:00"
             */
            'deleted_at' => $this->deleted_at ? Carbon::parse($this->deleted_at)->format('Y-m-d H:i:s') : null,
        ];
    }
}
