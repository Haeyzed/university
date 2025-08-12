<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;
use OwenIt\Auditing\Contracts\Auditable;

/**
 * @class SmsSetting
 * @brief Model for managing SMS gateway settings.
 *
 * This model represents the 'sms_settings' table, storing
 * configurations for sending SMS messages from the application.
 *
 * @property int $id
 * @property string $sms_gateway
 * @property string|null $vonage_key
 * @property string|null $vonage_secret
 * @property string|null $vonage_number
 * @property string|null $twilio_sid
 * @property string|null $twilio_auth_token
 * @property string|null $twilio_number
 * @property string|null $africas_talking_username
 * @property string|null $africas_talking_api_key
 * @property string|null $textlocal_key
 * @property string|null $textlocal_sender
 * @property string|null $clickatell_api_key
 * @property string|null $smscountry_username
 * @property string|null $smscountry_password
 * @property string|null $smscountry_sender_id
 * @property bool $status
 * @property int|null $created_by
 * @property int|null $updated_by
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property Carbon|null $deleted_at
 */
class SmsSetting extends Model implements Auditable
{
    use HasFactory, SoftDeletes, \OwenIt\Auditing\Auditable;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'sms_settings';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'sms_gateway',
        'vonage_key',
        'vonage_secret',
        'vonage_number',
        'twilio_sid',
        'twilio_auth_token',
        'twilio_number',
        'africas_talking_username',
        'africas_talking_api_key',
        'textlocal_key',
        'textlocal_sender',
        'clickatell_api_key',
        'smscountry_username',
        'smscountry_password',
        'smscountry_sender_id',
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
     * Get the user who created the SMS setting.
     *
     * @return BelongsTo
     */
    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Get the user who last updated the SMS setting.
     *
     * @return BelongsTo
     */
    public function updatedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    /**
     * Scope a query to only include active mail setting.
     *
     * @param Builder $query
     * @return Builder
     */
    public function scopeActive(Builder $query): Builder
    {
        return $query->where('status', true);
    }
}
