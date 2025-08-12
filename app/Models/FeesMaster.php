<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Carbon;

/**
 * @class FeesMaster
 * @brief Model for managing master fees configurations.
 *
 * This model represents the 'fees_masters' table, defining
 * general fee structures for programs, semesters, etc.
 *
 * @property int $id
 * @property int $category_id
 * @property int|null $faculty_id
 * @property int|null $program_id
 * @property int|null $session_id
 * @property int|null $semester_id
 * @property int|null $section_id
 * @property float $amount
 * @property int $type
 * @property string $assign_date
 * @property string $due_date
 * @property bool $status
 * @property int|null $created_by
 * @property int|null $updated_by
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 */
class FeesMaster extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'category_id',
        'faculty_id',
        'program_id',
        'session_id',
        'semester_id',
        'section_id',
        'amount',
        'type',
        'assign_date',
        'due_date',
        'status',
        'created_by',
        'updated_by',
    ];

    /**
     * Get the fees category.
     *
     * @return BelongsTo
     */
    public function feesCategory(): BelongsTo
    {
        return $this->belongsTo(FeesCategory::class, 'category_id');
    }

    /**
     * Get the faculty associated with the fees master.
     *
     * @return BelongsTo
     */
    public function faculty(): BelongsTo
    {
        return $this->belongsTo(Faculty::class);
    }

    /**
     * Get the program associated with the fees master.
     *
     * @return BelongsTo
     */
    public function program(): BelongsTo
    {
        return $this->belongsTo(Program::class);
    }

    /**
     * Get the session associated with the fees master.
     *
     * @return BelongsTo
     */
    public function session(): BelongsTo
    {
        return $this->belongsTo(Session::class);
    }

    /**
     * Get the semester associated with the fees master.
     *
     * @return BelongsTo
     */
    public function semester(): BelongsTo
    {
        return $this->belongsTo(Semester::class);
    }

    /**
     * Get the section associated with the fees master.
     *
     * @return BelongsTo
     */
    public function section(): BelongsTo
    {
        return $this->belongsTo(Section::class);
    }

    /**
     * Get the user who created the fees master.
     *
     * @return BelongsTo
     */
    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Get the user who last updated the fees master.
     *
     * @return BelongsTo
     */
    public function updatedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    /**
     * The student enrollments associated with this fees master.
     *
     * @return BelongsToMany
     */
    public function studentEnrolls(): BelongsToMany
    {
        return $this->belongsToMany(StudentEnroll::class, 'fees_master_student_enroll', 'fees_master_id', 'student_enroll_id');
    }
}
