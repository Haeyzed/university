<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Illuminate\Support\Carbon;

/**
 * @class Student
 * @brief Model for managing student information.
 *
 * This model represents the 'students' table, storing comprehensive
 * details about enrolled students.
 *
 * @property int $id
 * @property string $student_id
 * @property string|null $registration_no
 * @property int|null $batch_id
 * @property int|null $program_id
 * @property string|null $admission_date
 * @property string $first_name
 * @property string $last_name
 * @property string|null $father_name
 * @property string|null $mother_name
 * @property string|null $father_occupation
 * @property string|null $mother_occupation
 * @property string|null $father_photo
 * @property string|null $mother_photo
 * @property string $email
 * @property Carbon|null $email_verified_at
 * @property string $password
 * @property string|null $password_text
 * @property string|null $country
 * @property int|null $present_province
 * @property int|null $present_district
 * @property string|null $present_village
 * @property string|null $present_address
 * @property int|null $permanent_province
 * @property int|null $permanent_district
 * @property string|null $permanent_village
 * @property string|null $permanent_address
 * @property int $gender
 * @property string $dob
 * @property string|null $phone
 * @property string|null $emergency_phone
 * @property string|null $religion
 * @property string|null $caste
 * @property string|null $mother_tongue
 * @property int|null $marital_status
 * @property int|null $blood_group
 * @property string|null $nationality
 * @property string|null $national_id
 * @property string|null $passport_no
 * @property string|null $school_name
 * @property string|null $school_exam_id
 * @property string|null $school_graduation_field
 * @property string|null $school_graduation_year
 * @property string|null $school_graduation_point
 * @property string|null $school_transcript
 * @property string|null $school_certificate
 * @property string|null $collage_name
 * @property string|null $collage_exam_id
 * @property string|null $collage_graduation_field
 * @property string|null $collage_graduation_year
 * @property string|null $collage_graduation_point
 * @property string|null $collage_transcript
 * @property string|null $collage_certificate
 * @property string|null $photo
 * @property string|null $signature
 * @property bool $login
 * @property int $status
 * @property int|null $is_transfer
 * @property string|null $remember_token
 * @property int|null $created_by
 * @property int|null $updated_by
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 */
class Student extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'student_id',
        'registration_no',
        'batch_id',
        'program_id',
        'admission_date',
        'first_name',
        'last_name',
        'father_name',
        'mother_name',
        'father_occupation',
        'mother_occupation',
        'father_photo',
        'mother_photo',
        'email',
        'email_verified_at',
        'password',
        'password_text',
        'country',
        'present_province',
        'present_district',
        'present_village',
        'present_address',
        'permanent_province',
        'permanent_district',
        'permanent_village',
        'permanent_address',
        'gender',
        'dob',
        'phone',
        'emergency_phone',
        'religion',
        'caste',
        'mother_tongue',
        'marital_status',
        'blood_group',
        'nationality',
        'national_id',
        'passport_no',
        'school_name',
        'school_exam_id',
        'school_graduation_field',
        'school_graduation_year',
        'school_graduation_point',
        'school_transcript',
        'school_certificate',
        'collage_name',
        'collage_exam_id',
        'collage_graduation_field',
        'collage_graduation_year',
        'collage_graduation_point',
        'collage_transcript',
        'collage_certificate',
        'photo',
        'signature',
        'login',
        'status',
        'is_transfer',
        'remember_token',
        'created_by',
        'updated_by',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * Get the current enrollment of a student by ID.
     *
     * @param int $id
     * @return StudentEnroll|null
     */
    public static function enroll(int $id): ?StudentEnroll
    {
        return StudentEnroll::query()->where('student_id', $id)
            ->where('status', 1)
            ->orderByDesc('id')
            ->first();
    }

    /**
     * Get the batch associated with the student.
     *
     * @return BelongsTo
     */
    public function batch(): BelongsTo
    {
        return $this->belongsTo(Batch::class);
    }

    /**
     * Get the program associated with the student.
     *
     * @return BelongsTo
     */
    public function program(): BelongsTo
    {
        return $this->belongsTo(Program::class);
    }

    /**
     * Get the present province of the student.
     *
     * @return BelongsTo
     */
    public function presentProvince(): BelongsTo
    {
        return $this->belongsTo(Province::class, 'present_province');
    }

    /**
     * Get the present district of the student.
     *
     * @return BelongsTo
     */
    public function presentDistrict(): BelongsTo
    {
        return $this->belongsTo(District::class, 'present_district');
    }

    /**
     * Get the permanent province of the student.
     *
     * @return BelongsTo
     */
    public function permanentProvince(): BelongsTo
    {
        return $this->belongsTo(Province::class, 'permanent_province');
    }

    /**
     * Get the permanent district of the student.
     *
     * @return BelongsTo
     */
    public function permanentDistrict(): BelongsTo
    {
        return $this->belongsTo(District::class, 'permanent_district');
    }

    /**
     * Get the user who created the student record.
     *
     * @return BelongsTo
     */
    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Get the user who last updated the student record.
     *
     * @return BelongsTo
     */
    public function updatedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    /**
     * The status types associated with the student.
     *
     * @return BelongsToMany
     */
    public function statuses(): BelongsToMany
    {
        return $this->belongsToMany(StatusType::class, 'status_type_student', 'student_id', 'status_type_id');
    }

    /**
     * Get the documents associated with the student.
     *
     * @return MorphToMany
     */
    public function documents(): MorphToMany
    {
        return $this->morphToMany(Document::class, 'docable');
    }

    /**
     * Get the contents associated with the student.
     *
     * @return MorphToMany
     */
    public function contents(): MorphToMany
    {
        return $this->morphToMany(Content::class, 'contentable');
    }

    /**
     * Get the student relatives.
     *
     * @return HasMany
     */
    public function relatives(): HasMany
    {
        return $this->hasMany(StudentRelative::class);
    }

    /**
     * Get the student enrollments.
     *
     * @return HasMany
     */
    public function studentEnrolls(): HasMany
    {
        return $this->hasMany(StudentEnroll::class);
    }

    /**
     * Get the student transfers.
     *
     * @return HasMany
     */
    public function studentTransfers(): HasMany
    {
        return $this->hasMany(StudentTransfer::class);
    }

    /**
     * Get the student exams.
     *
     * @return HasMany
     */
    public function exams(): HasMany
    {
        return $this->hasMany(Exam::class);
    }

    /**
     * Get the student leaves.
     *
     * @return HasMany
     */
    public function leaves(): HasMany
    {
        return $this->hasMany(StudentLeave::class);
    }

    /**
     * Get the student attendances.
     *
     * @return HasMany
     */
    public function studentAttendances(): HasMany
    {
        return $this->hasMany(StudentAttendance::class);
    }

    /**
     * Get the student assignments.
     *
     * @return HasMany
     */
    public function studentAssignments(): HasMany
    {
        return $this->hasMany(StudentAssignment::class);
    }

    /**
     * Get the certificates issued to this student.
     *
     * @return HasMany
     */
    public function certificates(): HasMany
    {
        return $this->hasMany(Certificate::class);
    }

    /**
     * Get the transfer credits for this student.
     *
     * @return HasMany
     */
    public function transferCredits(): HasMany
    {
        return $this->hasMany(TransferCredit::class);
    }

    /**
     * Get the library member record associated with the student.
     *
     * @return MorphOne
     */
    public function member(): MorphOne
    {
        return $this->morphOne(LibraryMember::class, 'memberable');
    }

    /**
     * Get the hostel room record associated with the student.
     *
     * @return MorphOne
     */
    public function hostelRoom(): MorphOne
    {
        return $this->morphOne(HostelMember::class, 'hostelable');
    }

    /**
     * Get the transport member record associated with the student.
     *
     * @return MorphOne
     */
    public function transport(): MorphOne
    {
        return $this->morphOne(TransportMember::class, 'transportable');
    }

    /**
     * Get the notes record associated with the student.
     *
     * @return MorphMany
     */
    public function notes(): MorphMany
    {
        return $this->morphMany(Note::class, 'noteable');
    }

    /**
     * Get the transactions record associated with the student.
     *
     * @return MorphMany
     */
    public function transactions(): MorphMany
    {
        return $this->morphMany(Transaction::class, 'transactionable');
    }

    /**
     * Get the current active enrollment.
     *
     * @return HasOne
     */
    public function currentEnroll(): HasOne
    {
        return $this->hasOne(StudentEnroll::class, 'student_id')->ofMany(['id' => 'max'], fn($query) => $query->where('status', 1));
    }

    /**
     * Get the first (earliest) enrollment.
     *
     * @return HasOne
     */
    public function firstEnroll(): HasOne
    {
        return $this->hasOne(StudentEnroll::class, 'student_id')->ofMany(['id' => 'min']);
    }

    /**
     * Get the last (most recent) enrollment.
     *
     * @return HasOne
     */
    public function lastEnroll(): HasOne
    {
        return $this->hasOne(StudentEnroll::class, 'student_id')->ofMany(['id' => 'max']);
    }
}
