<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;

/**
 * @class Assignment
 * @brief Model for managing assignments.
 *
 * This model represents the 'assignments' table, storing details
 * about academic assignments, including the associated subject and creator.
 *
 * @property int $id
 * @property int|null $faculty_id
 * @property int|null $program_id
 * @property int|null $session_id
 * @property int|null $semester_id
 * @property int|null $section_id
 * @property int $subject_id
 * @property string $title
 * @property string|null $description
 * @property float|null $total_marks
 * @property string|null $start_date
 * @property string|null $end_date
 * @property string|null $attach
 * @property bool $status
 * @property int $assign_by
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 */
class Assignment extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'faculty_id',
        'program_id',
        'session_id',
        'semester_id',
        'section_id',
        'subject_id',
        'title',
        'description',
        'total_marks',
        'start_date',
        'end_date',
        'attach',
        'status',
        'assign_by',
    ];

    /**
     * Get the faculty associated with the assignment.
     *
     * @return BelongsTo
     */
    public function faculty(): BelongsTo
    {
        return $this->belongsTo(Faculty::class);
    }

    /**
     * Get the program associated with the assignment.
     *
     * @return BelongsTo
     */
    public function program(): BelongsTo
    {
        return $this->belongsTo(Program::class);
    }

    /**
     * Get the session associated with the assignment.
     *
     * @return BelongsTo
     */
    public function session(): BelongsTo
    {
        return $this->belongsTo(Session::class);
    }

    /**
     * Get the semester associated with the assignment.
     *
     * @return BelongsTo
     */
    public function semester(): BelongsTo
    {
        return $this->belongsTo(Semester::class);
    }

    /**
     * Get the section associated with the assignment.
     *
     * @return BelongsTo
     */
    public function section(): BelongsTo
    {
        return $this->belongsTo(Section::class);
    }

    /**
     * Get the subject associated with the assignment.
     *
     * @return BelongsTo
     */
    public function subject(): BelongsTo
    {
        return $this->belongsTo(Subject::class);
    }

    /**
     * Get the user who assigned the assignment.
     *
     * @return BelongsTo
     */
    public function teacher(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assign_by');
    }

    /**
     * Get the student assignments for this assignment.
     *
     * @return HasMany
     */
    public function students(): HasMany
    {
        return $this->hasMany(StudentAssignment::class);
    }
}
