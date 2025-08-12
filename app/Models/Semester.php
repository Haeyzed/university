<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;

/**
 * @class Semester
 * @brief Model for managing academic semesters.
 *
 * This model represents the 'semesters' table, organizing
 * academic periods within a program.
 *
 * @property int $id
 * @property string $title
 * @property string|null $year
 * @property bool $status
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 */
class Semester extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'title',
        'year',
        'status',
    ];

    /**
     * The programs that belong to the semester.
     *
     * @return BelongsToMany
     */
    public function programs(): BelongsToMany
    {
        return $this->belongsToMany(Program::class, 'program_semester', 'semester_id', 'program_id');
    }

    /**
     * Get the program semester sections for this semester.
     *
     * @return HasMany
     */
    public function programSections(): HasMany
    {
        return $this->hasMany(ProgramSemesterSection::class);
    }

    /**
     * Get the class routines for this semester.
     *
     * @return HasMany
     */
    public function classes(): HasMany
    {
        return $this->hasMany(ClassRoutine::class);
    }

    /**
     * Get the contents for this semester.
     *
     * @return HasMany
     */
    public function contents(): HasMany
    {
        return $this->hasMany(Content::class);
    }

    /**
     * Get the email notifications for this semester.
     *
     * @return HasMany
     */
    public function emailNotifies(): HasMany
    {
        return $this->hasMany(EmailNotify::class);
    }

    /**
     * Get the enrolled subjects for this semester.
     *
     * @return HasMany
     */
    public function enrollSubjects(): HasMany
    {
        return $this->hasMany(EnrollSubject::class);
    }

    /**
     * Get the exam routines for this semester.
     *
     * @return HasMany
     */
    public function examRoutines(): HasMany
    {
        return $this->hasMany(ExamRoutine::class);
    }

    /**
     * Get the fees masters for this semester.
     *
     * @return HasMany
     */
    public function feesMasters(): HasMany
    {
        return $this->hasMany(FeesMaster::class);
    }

    /**
     * Get the notices for this semester.
     *
     * @return HasMany
     */
    public function notices(): HasMany
    {
        return $this->hasMany(Notice::class);
    }

    /**
     * Get the SMS notifications for this semester.
     *
     * @return HasMany
     */
    public function smsNotifies(): HasMany
    {
        return $this->hasMany(SMSNotify::class);
    }

    /**
     * Get the student enrollments for this semester.
     *
     * @return HasMany
     */
    public function studentEnrolls(): HasMany
    {
        return $this->hasMany(StudentEnroll::class);
    }

    /**
     * Get the transfer credits for this semester.
     *
     * @return HasMany
     */
    public function transferCredits(): HasMany
    {
        return $this->hasMany(TransferCredit::class);
    }

    /**
     * Get the staff hourly attendances for this semester.
     *
     * @return HasMany
     */
    public function staffHourlyAttendances(): HasMany
    {
        return $this->hasMany(StaffHourlyAttendance::class);
    }
}
