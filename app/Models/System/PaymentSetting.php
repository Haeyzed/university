<?php

namespace App\Models\System;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;
use OwenIt\Auditing\Contracts\Auditable;

/**
 * @class PaymentSetting
 * @brief Model for managing payment gateway settings.
 *
 * This model represents the 'payment_settings' table, storing
 * configurations for various payment gateways.
 *
 * @property int $id
 * @property string $payment_gateway
 * @property string|null $paypal_client_id
 * @property string|null $paypal_secret
 * @property string|null $stripe_key
 * @property string|null $stripe_secret
 * @property string|null $razorpay_key
 * @property string|null $razorpay_secret
 * @property string|null $paystack_key
 * @property string|null $paystack_secret
 * @property string|null $merchant_email
 * @property string|null $flutterwave_public_key
 * @property string|null $flutterwave_secret_key
 * @property string|null $flutterwave_secret_hash
 * @property string|null $skrill_email
 * @property string|null $skrill_secret
 * @property bool $status
 * @property int|null $created_by
 * @property int|null $updated_by
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property Carbon|null $deleted_at
 */
class PaymentSetting extends Model implements Auditable
{
    use HasFactory, SoftDeletes, \OwenIt\Auditing\Auditable;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'payment_settings';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'payment_gateway',
        'paypal_client_id',
        'paypal_secret',
        'stripe_key',
        'stripe_secret',
        'razorpay_key',
        'razorpay_secret',
        'paystack_key',
        'paystack_secret',
        'merchant_email',
        'flutterwave_public_key',
        'flutterwave_secret_key',
        'flutterwave_secret_hash',
        'skrill_email',
        'skrill_secret',
        'status',
        'created_by',
        'updated_by',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'status' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    /**
     * Get the user who created the record.
     *
     * @return BelongsTo
     */
    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Get the user who last updated the record.
     *
     * @return BelongsTo
     */
    public function updatedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }
}
