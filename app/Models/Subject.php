<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;

/**
 * @class Subject
 * @brief Model for managing academic subjects/courses.
 *
 * This model represents the 'subjects' table, defining
 * individual academic subjects with their codes, credit hours, etc.
 *
 * @property int $id
 * @property string $title
 * @property string $code
 * @property int $credit_hour
 * @property int $subject_type
 * @property int $class_type
 * @property float|null $total_marks
 * @property float|null $passing_marks
 * @property string|null $description
 * @property bool $status
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 */
class Subject extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'title',
        'code',
        'credit_hour',
        'subject_type',
        'class_type',
        'total_marks',
        'passing_marks',
        'description',
        'status',
    ];

    /**
     * The programs that offer this subject.
     *
     * @return BelongsToMany
     */
    public function programs(): BelongsToMany
    {
        return $this->belongsToMany(Program::class, 'program_subject', 'subject_id', 'program_id');
    }

    /**
     * The enroll subjects that include this subject.
     *
     * @return BelongsToMany
     */
    public function subjectEnrolls(): BelongsToMany
    {
        return $this->belongsToMany(EnrollSubject::class, 'enroll_subject_subject', 'subject_id', 'enroll_subject_id');
    }

    /**
     * The student enrollments that include this subject.
     *
     * @return BelongsToMany
     */
    public function studentEnrolls(): BelongsToMany
    {
        return $this->belongsToMany(StudentEnroll::class, 'student_enroll_subject', 'subject_id', 'student_enroll_id');
    }

    /**
     * Get the assignments for this subject.
     *
     * @return HasMany
     */
    public function assignments(): HasMany
    {
        return $this->hasMany(Assignment::class);
    }

    /**
     * Get the class routines for this subject.
     *
     * @return HasMany
     */
    public function classes(): HasMany
    {
        return $this->hasMany(ClassRoutine::class);
    }

    /**
     * Get the exams for this subject.
     *
     * @return HasMany
     */
    public function exams(): HasMany
    {
        return $this->hasMany(Exam::class);
    }

    /**
     * Get the exam routines for this subject.
     *
     * @return HasMany
     */
    public function examRoutines(): HasMany
    {
        return $this->hasMany(ExamRoutine::class);
    }

    /**
     * Get the staff hourly attendances for this subject.
     *
     * @return HasMany
     */
    public function staffHourlyAttendances(): HasMany
    {
        return $this->hasMany(StaffHourlyAttendance::class);
    }

    /**
     * Get the student attendances for this subject.
     *
     * @return HasMany
     */
    public function attendances(): HasMany
    {
        return $this->hasMany(StudentAttendance::class);
    }

    /**
     * Get the subject markings for this subject.
     *
     * @return HasMany
     */
    public function subjectMarks(): HasMany
    {
        return $this->hasMany(SubjectMarking::class);
    }

    /**
     * Get the transfer credits for this subject.
     *
     * @return HasMany
     */
    public function transferCredits(): HasMany
    {
        return $this->hasMany(TransferCredit::class);
    }
}
