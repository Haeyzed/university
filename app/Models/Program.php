<?php

namespace App\Models;

use App\Models\Web\Course as WebCourse;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;

/**
 * @class Program
 * @brief Model for managing academic programs.
 *
 * This model represents the 'programs' table, defining
 * academic courses of study offered by the institution.
 *
 * @property int $id
 * @property int $faculty_id
 * @property string $title
 * @property string $slug
 * @property string|null $shortcode
 * @property bool $registration
 * @property bool $status
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 */
class Program extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'faculty_id',
        'title',
        'slug',
        'shortcode',
        'registration',
        'status',
    ];

    /**
     * Get the faculty that owns the program.
     *
     * @return BelongsTo
     */
    public function faculty(): BelongsTo
    {
        return $this->belongsTo(Faculty::class);
    }

    /**
     * The batches that belong to the program.
     *
     * @return BelongsToMany
     */
    public function batches(): BelongsToMany
    {
        return $this->belongsToMany(Batch::class, 'batch_program', 'program_id', 'batch_id');
    }

    /**
     * The semesters that belong to the program.
     *
     * @return BelongsToMany
     */
    public function semesters(): BelongsToMany
    {
        return $this->belongsToMany(Semester::class, 'program_semester', 'program_id', 'semester_id');
    }

    /**
     * The classrooms that belong to the program.
     *
     * @return BelongsToMany
     */
    public function rooms(): BelongsToMany
    {
        return $this->belongsToMany(ClassRoom::class, 'program_class_room', 'program_id', 'class_room_id');
    }

    /**
     * The sessions that belong to the program.
     *
     * @return BelongsToMany
     */
    public function sessions(): BelongsToMany
    {
        return $this->belongsToMany(Session::class, 'program_session', 'program_id', 'session_id');
    }

    /**
     * The subjects that belong to the program.
     *
     * @return BelongsToMany
     */
    public function subjects(): BelongsToMany
    {
        return $this->belongsToMany(Subject::class, 'program_subject', 'program_id', 'subject_id');
    }

    /**
     * Get the program semester sections for this program.
     *
     * @return HasMany
     */
    public function programSemesterSections(): HasMany
    {
        return $this->hasMany(ProgramSemesterSection::class);
    }

    /**
     * Get the applications for this program.
     *
     * @return HasMany
     */
    public function applications(): HasMany
    {
        return $this->hasMany(Application::class);
    }

    /**
     * Get the class routines for this program.
     *
     * @return HasMany
     */
    public function classes(): HasMany
    {
        return $this->hasMany(ClassRoutine::class);
    }

    /**
     * Get the contents for this program.
     *
     * @return HasMany
     */
    public function contents(): HasMany
    {
        return $this->hasMany(Content::class);
    }

    /**
     * Get the email notifications for this program.
     *
     * @return HasMany
     */
    public function emailNotifies(): HasMany
    {
        return $this->hasMany(EmailNotify::class);
    }

    /**
     * Get the enquiries for this program.
     *
     * @return HasMany
     */
    public function enquiries(): HasMany
    {
        return $this->hasMany(Enquiry::class);
    }

    /**
     * Get the exam routines for this program.
     *
     * @return HasMany
     */
    public function examRoutines(): HasMany
    {
        return $this->hasMany(ExamRoutine::class);
    }

    /**
     * Get the fees masters for this program.
     *
     * @return HasMany
     */
    public function feesMasters(): HasMany
    {
        return $this->hasMany(FeesMaster::class);
    }

    /**
     * Get the notices for this program.
     *
     * @return HasMany
     */
    public function notices(): HasMany
    {
        return $this->hasMany(Notice::class);
    }

    /**
     * Get the SMS notifications for this program.
     *
     * @return HasMany
     */
    public function smsNotifies(): HasMany
    {
        return $this->hasMany(SMSNotify::class);
    }

    /**
     * Get the students enrolled in this program.
     *
     * @return HasMany
     */
    public function students(): HasMany
    {
        return $this->hasMany(Student::class);
    }

    /**
     * Get the student enrollments for this program.
     *
     * @return HasMany
     */
    public function studentEnrolls(): HasMany
    {
        return $this->hasMany(StudentEnroll::class);
    }

    /**
     * Get the transfer credits for this program.
     *
     * @return HasMany
     */
    public function transferCredits(): HasMany
    {
        return $this->hasMany(TransferCredit::class);
    }

    /**
     * Get the user programs for this program.
     *
     * @return HasMany
     */
    public function users(): HasMany
    {
        return $this->hasMany(UserProgram::class);
    }

    /**
     * Get the web courses associated with this program.
     *
     * @return HasMany
     */
    public function webCourses(): HasMany
    {
        return $this->hasMany(WebCourse::class);
    }
}
