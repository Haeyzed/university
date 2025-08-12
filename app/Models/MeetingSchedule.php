<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

/**
 * @class MeetingSchedule
 * @brief Model for managing meeting schedules.
 *
 * This model represents the 'meeting_schedules' table, storing details
 * of scheduled meetings, including type, attendees, and status.
 *
 * @property int $id
 * @property int $type_id
 * @property int|null $user_id
 * @property string $name
 * @property string|null $father_name
 * @property string|null $phone
 * @property string|null $email
 * @property string|null $address
 * @property string|null $purpose
 * @property string|null $note
 * @property string|null $id_no
 * @property string|null $token
 * @property string $date
 * @property string|null $in_time
 * @property string|null $out_time
 * @property string|null $persons
 * @property string|null $attach
 * @property int $status
 * @property int|null $created_by
 * @property int|null $updated_by
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 */
class MeetingSchedule extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'type_id',
        'user_id',
        'name',
        'father_name',
        'phone',
        'email',
        'address',
        'purpose',
        'note',
        'id_no',
        'token',
        'date',
        'in_time',
        'out_time',
        'persons',
        'attach',
        'status',
        'created_by',
        'updated_by',
    ];

    /**
     * Get the meeting type.
     *
     * @return BelongsTo
     */
    public function type(): BelongsTo
    {
        return $this->belongsTo(MeetingType::class, 'type_id');
    }

    /**
     * Get the user associated with the meeting (if internal).
     *
     * @return BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the user who created the meeting schedule.
     *
     * @return BelongsTo
     */
    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Get the user who last updated the meeting schedule.
     *
     * @return BelongsTo
     */
    public function updatedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }
}
