<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;

/**
 * @class Session
 * @brief Model for managing academic sessions.
 *
 * This model represents the 'sessions' table, defining
 * academic years or periods.
 *
 * @property int $id
 * @property string $title
 * @property string|null $start_date
 * @property string|null $end_date
 * @property bool $current
 * @property bool $status
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 */
class Session extends Model
{
    use HasFactory;

    protected $table = 'academic_sessions';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'title',
        'start_date',
        'end_date',
        'current',
        'status',
    ];

    /**
     * The programs that belong to the session.
     *
     * @return BelongsToMany
     */
    public function programs(): BelongsToMany
    {
        return $this->belongsToMany(Program::class, 'program_session', 'session_id', 'program_id');
    }

    /**
     * Get the class routines for this session.
     *
     * @return HasMany
     */
    public function classes(): HasMany
    {
        return $this->hasMany(ClassRoutine::class);
    }

    /**
     * Get the contents for this session.
     *
     * @return HasMany
     */
    public function contents(): HasMany
    {
        return $this->hasMany(Content::class);
    }

    /**
     * Get the email notifications for this session.
     *
     * @return HasMany
     */
    public function emailNotifies(): HasMany
    {
        return $this->hasMany(EmailNotify::class);
    }

    /**
     * Get the exam routines for this session.
     *
     * @return HasMany
     */
    public function examRoutines(): HasMany
    {
        return $this->hasMany(ExamRoutine::class);
    }

    /**
     * Get the fees masters for this session.
     *
     * @return HasMany
     */
    public function feesMasters(): HasMany
    {
        return $this->hasMany(FeesMaster::class);
    }

    /**
     * Get the notices for this session.
     *
     * @return HasMany
     */
    public function notices(): HasMany
    {
        return $this->hasMany(Notice::class);
    }

    /**
     * Get the SMS notifications for this session.
     *
     * @return HasMany
     */
    public function smsNotifies(): HasMany
    {
        return $this->hasMany(SMSNotify::class);
    }

    /**
     * Get the student enrollments for this session.
     *
     * @return HasMany
     */
    public function studentEnrolls(): HasMany
    {
        return $this->hasMany(StudentEnroll::class);
    }

    /**
     * Get the transfer credits for this session.
     *
     * @return HasMany
     */
    public function transferCredits(): HasMany
    {
        return $this->hasMany(TransferCredit::class);
    }

    /**
     * Get the staff hourly attendances for this session.
     *
     * @return HasMany
     */
    public function staffHourlyAttendances(): HasMany
    {
        return $this->hasMany(StaffHourlyAttendance::class);
    }
}
