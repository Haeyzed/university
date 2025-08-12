<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

// For teacher

/**
 * @class ClassRoutine
 * @brief Model for managing class routines/schedules.
 *
 * This model represents the 'class_routines' table, defining
 * the schedule for classes, including program, semester, section,
 * subject, classroom, and teacher.
 *
 * @property int $id
 * @property int $teacher_id
 * @property int $subject_id
 * @property int $room_id
 * @property int $session_id
 * @property int $program_id
 * @property int $semester_id
 * @property int $section_id
 * @property string $start_time
 * @property string $end_time
 * @property int $day
 * @property bool $status
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 */
class ClassRoutine extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'teacher_id',
        'subject_id',
        'room_id',
        'session_id',
        'program_id',
        'semester_id',
        'section_id',
        'start_time',
        'end_time',
        'day',
        'status',
    ];

    /**
     * Get the teacher (user) assigned to the class routine.
     *
     * @return BelongsTo
     */
    public function teacher(): BelongsTo
    {
        return $this->belongsTo(User::class, 'teacher_id');
    }

    /**
     * Get the subject associated with the class routine.
     *
     * @return BelongsTo
     */
    public function subject(): BelongsTo
    {
        return $this->belongsTo(Subject::class);
    }

    /**
     * Get the classroom associated with the class routine.
     *
     * @return BelongsTo
     */
    public function room(): BelongsTo
    {
        return $this->belongsTo(ClassRoom::class, 'room_id');
    }

    /**
     * Get the session associated with the class routine.
     *
     * @return BelongsTo
     */
    public function session(): BelongsTo
    {
        return $this->belongsTo(Session::class);
    }

    /**
     * Get the program associated with the class routine.
     *
     * @return BelongsTo
     */
    public function program(): BelongsTo
    {
        return $this->belongsTo(Program::class);
    }

    /**
     * Get the semester associated with the class routine.
     *
     * @return BelongsTo
     */
    public function semester(): BelongsTo
    {
        return $this->belongsTo(Semester::class);
    }

    /**
     * Get the section associated with the class routine.
     *
     * @return BelongsTo
     */
    public function section(): BelongsTo
    {
        return $this->belongsTo(Section::class);
    }
}
