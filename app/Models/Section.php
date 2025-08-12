<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;

/**
 * @class Section
 * @brief Model for managing academic sections.
 *
 * This model represents the 'sections' table, organizing
 * students into smaller groups within a semester or program.
 *
 * @property int $id
 * @property string $title
 * @property int|null $seat
 * @property bool $status
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 */
class Section extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'title',
        'seat',
        'status',
    ];

    /**
     * Get the semester program sections for this section.
     *
     * @return HasMany
     */
    public function semesterPrograms(): HasMany
    {
        return $this->hasMany(ProgramSemesterSection::class);
    }

    /**
     * Get the program semester sections for this section.
     *
     * @return HasMany
     */
    public function programSemesters(): HasMany
    {
        return $this->hasMany(ProgramSemesterSection::class);
    }

    /**
     * Get the class routines for this section.
     *
     * @return HasMany
     */
    public function classes(): HasMany
    {
        return $this->hasMany(ClassRoutine::class);
    }

    /**
     * Get the contents for this section.
     *
     * @return HasMany
     */
    public function contents(): HasMany
    {
        return $this->hasMany(Content::class);
    }

    /**
     * Get the email notifications for this section.
     *
     * @return HasMany
     */
    public function emailNotifies(): HasMany
    {
        return $this->hasMany(EmailNotify::class);
    }

    /**
     * Get the enrolled subjects for this section.
     *
     * @return HasMany
     */
    public function enrollSubjects(): HasMany
    {
        return $this->hasMany(EnrollSubject::class);
    }

    /**
     * Get the exam routines for this section.
     *
     * @return HasMany
     */
    public function examRoutines(): HasMany
    {
        return $this->hasMany(ExamRoutine::class);
    }

    /**
     * Get the fees masters for this section.
     *
     * @return HasMany
     */
    public function feesMasters(): HasMany
    {
        return $this->hasMany(FeesMaster::class);
    }

    /**
     * Get the notices for this section.
     *
     * @return HasMany
     */
    public function notices(): HasMany
    {
        return $this->hasMany(Notice::class);
    }

    /**
     * Get the SMS notifications for this section.
     *
     * @return HasMany
     */
    public function smsNotifies(): HasMany
    {
        return $this->hasMany(SMSNotify::class);
    }

    /**
     * Get the students in this section.
     *
     * @return HasMany
     */
    public function students(): HasMany
    {
        return $this->hasMany(Student::class);
    }

    /**
     * Get the student enrollments for this section.
     *
     * @return HasMany
     */
    public function studentEnrolls(): HasMany
    {
        return $this->hasMany(StudentEnroll::class);
    }

    /**
     * Get the staff hourly attendances for this section.
     *
     * @return HasMany
     */
    public function staffHourlyAttendances(): HasMany
    {
        return $this->hasMany(StaffHourlyAttendance::class);
    }
}
