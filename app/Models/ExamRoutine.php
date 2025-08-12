<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Carbon;

/**
 * @class ExamRoutine
 * @brief Model for managing exam routines/schedules.
 *
 * This model represents the 'exam_routines' table, defining
 * the schedule for exams, including exam type, session, program,
 * semester, section, and subject.
 *
 * @property int $id
 * @property int $exam_type_id
 * @property int $session_id
 * @property int $program_id
 * @property int $semester_id
 * @property int $section_id
 * @property int $subject_id
 * @property string $date
 * @property string $start_time
 * @property string $end_time
 * @property bool $status
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 */
class ExamRoutine extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'exam_type_id',
        'session_id',
        'program_id',
        'semester_id',
        'section_id',
        'subject_id',
        'date',
        'start_time',
        'end_time',
        'status',
    ];

    /**
     * Get the exam type associated with the routine.
     *
     * @return BelongsTo
     */
    public function type(): BelongsTo
    {
        return $this->belongsTo(ExamType::class);
    }

    /**
     * Get the session associated with the routine.
     *
     * @return BelongsTo
     */
    public function session(): BelongsTo
    {
        return $this->belongsTo(Session::class);
    }

    /**
     * Get the program associated with the routine.
     *
     * @return BelongsTo
     */
    public function program(): BelongsTo
    {
        return $this->belongsTo(Program::class);
    }

    /**
     * Get the semester associated with the routine.
     *
     * @return BelongsTo
     */
    public function semester(): BelongsTo
    {
        return $this->belongsTo(Semester::class);
    }

    /**
     * Get the section associated with the routine.
     *
     * @return BelongsTo
     */
    public function section(): BelongsTo
    {
        return $this->belongsTo(Section::class);
    }

    /**
     * Get the subject associated with the routine.
     *
     * @return BelongsTo
     */
    public function subject(): BelongsTo
    {
        return $this->belongsTo(Subject::class);
    }

    /**
     * The rooms associated with the exam routine.
     *
     * @return BelongsToMany
     */
    public function rooms(): BelongsToMany
    {
        return $this->belongsToMany(ClassRoom::class, 'exam_routine_room', 'exam_routine_id', 'room_id');
    }

    /**
     * The users (invigilators) associated with the exam routine.
     *
     * @return BelongsToMany
     */
    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'exam_routine_user', 'exam_routine_id', 'user_id');
    }
}
