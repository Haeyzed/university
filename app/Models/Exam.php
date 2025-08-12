<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

/**
 * @class Exam
 * @brief Model for managing exam results and attendance.
 *
 * This model represents the 'exams' table, storing student
 * performance and attendance for specific subjects and exam types.
 *
 * @property int $id
 * @property int $student_enroll_id
 * @property int $subject_id
 * @property int|null $exam_type_id
 * @property string|null $date
 * @property string|null $time
 * @property int $attendance
 * @property float|null $marks
 * @property float|null $achieve_marks
 * @property float $contribution
 * @property string|null $note
 * @property bool $status
 * @property int|null $created_by
 * @property int|null $updated_by
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 */
class Exam extends Model
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
        'exam_type_id',
        'date',
        'time',
        'attendance',
        'marks',
        'achieve_marks',
        'contribution',
        'note',
        'status',
        'created_by',
        'updated_by',
    ];

    /**
     * Get the student enrollment associated with the exam.
     *
     * @return BelongsTo
     */
    public function studentEnroll(): BelongsTo
    {
        return $this->belongsTo(StudentEnroll::class);
    }

    /**
     * Get the subject associated with the exam.
     *
     * @return BelongsTo
     */
    public function subject(): BelongsTo
    {
        return $this->belongsTo(Subject::class);
    }

    /**
     * Get the exam type.
     *
     * @return BelongsTo
     */
    public function type(): BelongsTo
    {
        return $this->belongsTo(ExamType::class);
    }

    /**
     * Get the user who created the exam record.
     *
     * @return BelongsTo
     */
    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Get the user who last updated the exam record.
     *
     * @return BelongsTo
     */
    public function updatedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }
}
