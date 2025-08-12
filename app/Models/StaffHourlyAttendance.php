<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

/**
 * @class StaffHourlyAttendance
 * @brief Model for managing staff hourly attendance (e.g., for teachers).
 *
 * This model represents the 'staff_hourly_attendances' table, recording
 * attendance for staff based on specific subjects, sessions, etc.
 *
 * @property int $id
 * @property int $user_id
 * @property int $subject_id
 * @property int $session_id
 * @property int $program_id
 * @property int $semester_id
 * @property int $section_id
 * @property string|null $start_time
 * @property string|null $end_time
 * @property string $date
 * @property int $attendance
 * @property string|null $note
 * @property int|null $created_by
 * @property int|null $updated_by
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 */
class StaffHourlyAttendance extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'subject_id',
        'session_id',
        'program_id',
        'semester_id',
        'section_id',
        'start_time',
        'end_time',
        'date',
        'attendance',
        'note',
        'created_by',
        'updated_by',
    ];

    /**
     * Get the user (staff) associated with the hourly attendance.
     *
     * @return BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the subject associated with the hourly attendance.
     *
     * @return BelongsTo
     */
    public function subject(): BelongsTo
    {
        return $this->belongsTo(Subject::class);
    }

    /**
     * Get the session associated with the hourly attendance.
     *
     * @return BelongsTo
     */
    public function session(): BelongsTo
    {
        return $this->belongsTo(Session::class);
    }

    /**
     * Get the program associated with the hourly attendance.
     *
     * @return BelongsTo
     */
    public function program(): BelongsTo
    {
        return $this->belongsTo(Program::class);
    }

    /**
     * Get the semester associated with the hourly attendance.
     *
     * @return BelongsTo
     */
    public function semester(): BelongsTo
    {
        return $this->belongsTo(Semester::class);
    }

    /**
     * Get the section associated with the hourly attendance.
     *
     * @return BelongsTo
     */
    public function section(): BelongsTo
    {
        return $this->belongsTo(Section::class);
    }

    /**
     * Get the user who created the hourly attendance record.
     *
     * @return BelongsTo
     */
    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Get the user who last updated the hourly attendance record.
     *
     * @return BelongsTo
     */
    public function updatedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }
}
