<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

/**
 * @class StudentAssignment
 * @brief Model for managing student assignment submissions.
 *
 * This model represents the 'student_assignments' table, recording
 * student submissions and marks for assignments.
 *
 * @property int $id
 * @property int $student_enroll_id
 * @property int $assignment_id
 * @property float|null $marks
 * @property int|null $attendance
 * @property string|null $date
 * @property string|null $attach
 * @property int|null $created_by
 * @property int|null $updated_by
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 */
class StudentAssignment extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'student_enroll_id',
        'assignment_id',
        'marks',
        'attendance',
        'date',
        'attach',
        'created_by',
        'updated_by',
    ];

    /**
     * Get the student enrollment associated with the assignment.
     *
     * @return BelongsTo
     */
    public function studentEnroll(): BelongsTo
    {
        return $this->belongsTo(StudentEnroll::class);
    }

    /**
     * Get the assignment.
     *
     * @return BelongsTo
     */
    public function assignment(): BelongsTo
    {
        return $this->belongsTo(Assignment::class);
    }

    /**
     * Get the user who created the student assignment record.
     *
     * @return BelongsTo
     */
    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Get the user who last updated the student assignment record.
     *
     * @return BelongsTo
     */
    public function updatedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }
}
