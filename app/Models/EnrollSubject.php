<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Carbon;

/**
 * @class EnrollSubject
 * @brief Model for managing subject enrollments.
 *
 * This model represents the 'enroll_subjects' table, defining
 * which subjects are available for enrollment within a specific
 * program, semester, and section.
 *
 * @property int $id
 * @property int $program_id
 * @property int $semester_id
 * @property int $section_id
 * @property bool $status
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 */
class EnrollSubject extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'program_id',
        'semester_id',
        'section_id',
        'status',
    ];

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
     * The subjects that can be enrolled.
     *
     * @return BelongsToMany
     */
    public function subjects(): BelongsToMany
    {
        return $this->belongsToMany(Subject::class, 'enroll_subject_subject', 'enroll_subject_id', 'subject_id');
    }
}
