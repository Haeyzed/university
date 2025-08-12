<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;

/**
 * @class StudentEnroll
 * @brief Model for managing student enrollments in programs.
 *
 * This model represents the 'student_enrolls' table, linking
 * students to specific programs, sessions, semesters, and sections.
 *
 * @property int $id
 * @property int $student_id
 * @property int $program_id
 * @property int $session_id
 * @property int $semester_id
 * @property int $section_id
 * @property bool $status
 * @property int|null $created_by
 * @property int|null $updated_by
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 */
class StudentEnroll extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'student_id',
        'program_id',
        'session_id',
        'semester_id',
        'section_id',
        'status',
        'created_by',
        'updated_by',
    ];

    /**
     * Get the student associated with the enrollment.
     *
     * @return BelongsTo
     */
    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

    /**
     * Get the program associated with the enrollment.
     *
     * @return BelongsTo
     */
    public function program(): BelongsTo
    {
        return $this->belongsTo(Program::class);
    }

    /**
     * Get the session associated with the enrollment.
     *
     * @return BelongsTo
     */
    public function session(): BelongsTo
    {
        return $this->belongsTo(Session::class);
    }

    /**
     * Get the semester associated with the enrollment.
     *
     * @return BelongsTo
     */
    public function semester(): BelongsTo
    {
        return $this->belongsTo(Semester::class);
    }

    /**
     * Get the section associated with the enrollment.
     *
     * @return BelongsTo
     */
    public function section(): BelongsTo
    {
        return $this->belongsTo(Section::class);
    }

    /**
     * Get the user who created the enrollment record.
     *
     * @return BelongsTo
     */
    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Get the user who last updated the enrollment record.
     *
     * @return BelongsTo
     */
    public function updatedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    /**
     * The subjects enrolled by the student.
     *
     * @return BelongsToMany
     */
    public function subjects(): BelongsToMany
    {
        return $this->belongsToMany(Subject::class, 'student_enroll_subject', 'student_enroll_id', 'subject_id');
    }

    /**
     * The fees masters associated with this student enrollment.
     *
     * @return BelongsToMany
     */
    public function feesMasters(): BelongsToMany
    {
        return $this->belongsToMany(FeesMaster::class, 'fees_master_student_enroll', 'student_enroll_id', 'fees_master_id');
    }

    /**
     * Get the fees for this student enrollment.
     *
     * @return HasMany
     */
    public function fees(): HasMany
    {
        return $this->hasMany(Fee::class);
    }

    /**
     * Get the exams for this student enrollment.
     *
     * @return HasMany
     */
    public function exams(): HasMany
    {
        return $this->hasMany(Exam::class);
    }

    /**
     * Get the subject markings for this student enrollment.
     *
     * @return HasMany
     */
    public function subjectMarks(): HasMany
    {
        return $this->hasMany(SubjectMarking::class);
    }

    /**
     * Get the student assignments for this student enrollment.
     *
     * @return HasMany
     */
    public function assignments(): HasMany
    {
        return $this->hasMany(StudentAssignment::class);
    }

    /**
     * Get the student attendances for this student enrollment.
     *
     * @return HasMany
     */
    public function attendances(): HasMany
    {
        return $this->hasMany(StudentAttendance::class);
    }
}
