<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

/**
 * @class Application
 * @brief Model for managing student applications.
 *
 * This model represents the 'applications' table, storing details
 * of student applications, including user, program, batch, and status.
 *
 * @property int $id
 * @property string|null $registration_no
 * @property int|null $batch_id
 * @property int|null $program_id
 * @property string|null $apply_date
 * @property string $first_name
 * @property string $last_name
 * @property string|null $father_name
 * @property string|null $mother_name
 * @property string|null $father_occupation
 * @property string|null $mother_occupation
 * @property string|null $father_photo
 * @property string|null $mother_photo
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
 * @property string $email
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
 * @property float|null $fee_amount
 * @property int $pay_status
 * @property int|null $payment_method
 * @property int $status
 * @property int|null $created_by
 * @property int|null $updated_by
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 */
class Application extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'registration_no',
        'batch_id',
        'program_id',
        'apply_date',
        'first_name',
        'last_name',
        'father_name',
        'mother_name',
        'father_occupation',
        'mother_occupation',
        'father_photo',
        'mother_photo',
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
        'email',
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
        'fee_amount',
        'pay_status',
        'payment_method',
        'status',
        'created_by',
        'updated_by',
    ];

    /**
     * Get the batch associated with the application.
     *
     * @return BelongsTo
     */
    public function batch(): BelongsTo
    {
        return $this->belongsTo(Batch::class);
    }

    /**
     * Get the program associated with the application.
     *
     * @return BelongsTo
     */
    public function program(): BelongsTo
    {
        return $this->belongsTo(Program::class);
    }

    /**
     * Get the present province of the applicant.
     *
     * @return BelongsTo
     */
    public function presentProvince(): BelongsTo
    {
        return $this->belongsTo(Province::class, 'present_province');
    }

    /**
     * Get the present district of the applicant.
     *
     * @return BelongsTo
     */
    public function presentDistrict(): BelongsTo
    {
        return $this->belongsTo(District::class, 'present_district');
    }

    /**
     * Get the permanent province of the applicant.
     *
     * @return BelongsTo
     */
    public function permanentProvince(): BelongsTo
    {
        return $this->belongsTo(Province::class, 'permanent_province');
    }

    /**
     * Get the permanent district of the applicant.
     *
     * @return BelongsTo
     */
    public function permanentDistrict(): BelongsTo
    {
        return $this->belongsTo(District::class, 'permanent_district');
    }

    /**
     * Get the status type of the application.
     *
     * @return BelongsTo
     */
    public function statusType(): BelongsTo
    {
        return $this->belongsTo(StatusType::class);
    }

    /**
     * Get the user who created the application.
     *
     * @return BelongsTo
     */
    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Get the user who last updated the application.
     *
     * @return BelongsTo
     */
    public function updatedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }
}
