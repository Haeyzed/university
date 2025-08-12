<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

/**
 * @class StudentAttendance
 * @brief Model for managing student attendance.
 *
 * This model represents the 'student_attendances' table, recording
 * the attendance of students for specific subjects.
 *
 * @property int $id
 * @property int $student_enroll_id
 * @property int $subject_id
 * @property string $date
 * @property string|null $time
 * @property int $attendance
 * @property string|null $note
 * @property int|null $created_by
 * @property int|null $updated_by
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 */
class StudentAttendance extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'student_enroll_id',
        'subject_id',
        'date',
        'time',
        'attendance',
        'note',
        'created_by',
        'updated_by',
    ];

    /**
     * Get the student enrollment associated with the attendance.
     *
     * @return BelongsTo
     */
    public function studentEnroll(): BelongsTo
    {
        return $this->belongsTo(StudentEnroll::class);
    }

    /**
     * Get the subject associated with the attendance.
     *
     * @return BelongsTo
     */
    public function subject(): BelongsTo
    {
        return $this->belongsTo(Subject::class);
    }

    /**
     * Get the user who created the attendance record.
     *
     * @return BelongsTo
     */
    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Get the user who last updated the attendance record.
     *
     * @return BelongsTo
     */
    public function updatedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }
}
