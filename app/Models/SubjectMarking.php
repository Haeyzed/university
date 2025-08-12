<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

/**
 * @class SubjectMarking
 * @brief Model for managing final subject markings/results.
 *
 * This model represents the 'subject_markings' table, storing
 * aggregated marks for students in a subject, including contributions
 * from exams, attendance, and assignments.
 *
 * @property int $id
 * @property int $student_enroll_id
 * @property int $subject_id
 * @property float|null $exam_marks
 * @property float|null $attendances
 * @property float|null $assignments
 * @property float|null $activities
 * @property float $total_marks
 * @property string|null $publish_date
 * @property string|null $publish_time
 * @property bool $status
 * @property int|null $created_by
 * @property int|null $updated_by
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 */
class SubjectMarking extends Model
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
        'exam_marks',
        'attendances',
        'assignments',
        'activities',
        'total_marks',
        'publish_date',
        'publish_time',
        'status',
        'created_by',
        'updated_by',
    ];

    /**
     * Get the student enrollment associated with the marking.
     *
     * @return BelongsTo
     */
    public function studentEnroll(): BelongsTo
    {
        return $this->belongsTo(StudentEnroll::class);
    }

    /**
     * Get the subject associated with the marking.
     *
     * @return BelongsTo
     */
    public function subject(): BelongsTo
    {
        return $this->belongsTo(Subject::class);
    }

    /**
     * Get the user who created the subject marking record.
     *
     * @return BelongsTo
     */
    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Get the user who last updated the subject marking record.
     *
     * @return BelongsTo
     */
    public function updatedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }
}
