<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

/**
 * @class PhoneLog
 * @brief Model for managing phone call logs.
 *
 * This model represents the 'phone_logs' table, storing records
 * of incoming and outgoing phone calls.
 *
 * @property int $id
 * @property string|null $name
 * @property string $phone
 * @property string $date
 * @property string|null $follow_up_date
 * @property string|null $call_duration
 * @property string|null $start_time
 * @property string|null $end_time
 * @property string|null $purpose
 * @property string|null $note
 * @property int $call_type
 * @property bool $status
 * @property int|null $created_by
 * @property int|null $updated_by
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 */
class PhoneLog extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'phone',
        'date',
        'follow_up_date',
        'call_duration',
        'start_time',
        'end_time',
        'purpose',
        'note',
        'call_type',
        'status',
        'created_by',
        'updated_by',
    ];

    /**
     * Get the user who created the phone log.
     *
     * @return BelongsTo
     */
    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Get the user who last updated the phone log.
     *
     * @return BelongsTo
     */
    public function updatedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }
}
