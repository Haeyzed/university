<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Support\Carbon;

/**
 * @class OutsideUser
 * @brief Model for managing outside users (non-staff/non-student).
 *
 * This model represents the 'outside_users' table, storing details
 * of external individuals who interact with the system (e.g., library members).
 *
 * @property int $id
 * @property string $first_name
 * @property string $last_name
 * @property string|null $father_name
 * @property string|null $mother_name
 * @property string|null $father_occupation
 * @property string|null $mother_occupation
 * @property string|null $father_photo
 * @property string|null $mother_photo
 * @property string|null $email
 * @property string|null $phone
 * @property string|null $country
 * @property int|null $present_province
 * @property int|null $present_district
 * @property string|null $present_village
 * @property string|null $present_address
 * @property int|null $permanent_province
 * @property int|null $permanent_district
 * @property string|null $permanent_village
 * @property string|null $permanent_address
 * @property string|null $education_level
 * @property string|null $occupation
 * @property int $gender
 * @property string $dob
 * @property string|null $religion
 * @property string|null $caste
 * @property string|null $mother_tongue
 * @property int|null $marital_status
 * @property int|null $blood_group
 * @property string|null $nationality
 * @property string|null $national_id
 * @property string|null $passport_no
 * @property string|null $photo
 * @property string|null $signature
 * @property int $status
 * @property int|null $created_by
 * @property int|null $updated_by
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 */
class OutsideUser extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'first_name',
        'last_name',
        'father_name',
        'mother_name',
        'father_occupation',
        'mother_occupation',
        'father_photo',
        'mother_photo',
        'email',
        'phone',
        'country',
        'present_province',
        'present_district',
        'present_village',
        'present_address',
        'permanent_province',
        'permanent_district',
        'permanent_village',
        'permanent_address',
        'education_level',
        'occupation',
        'gender',
        'dob',
        'religion',
        'caste',
        'mother_tongue',
        'marital_status',
        'blood_group',
        'nationality',
        'national_id',
        'passport_no',
        'photo',
        'signature',
        'status',
        'created_by',
        'updated_by',
    ];

    /**
     * Get the present province of the user.
     *
     * @return BelongsTo
     */
    public function presentProvince(): BelongsTo
    {
        return $this->belongsTo(Province::class, 'present_province');
    }

    /**
     * Get the present district of the user.
     *
     * @return BelongsTo
     */
    public function presentDistrict(): BelongsTo
    {
        return $this->belongsTo(District::class, 'present_district');
    }

    /**
     * Get the permanent province of the user.
     *
     * @return BelongsTo
     */
    public function permanentProvince(): BelongsTo
    {
        return $this->belongsTo(Province::class, 'permanent_province');
    }

    /**
     * Get the permanent district of the user.
     *
     * @return BelongsTo
     */
    public function permanentDistrict(): BelongsTo
    {
        return $this->belongsTo(District::class, 'permanent_district');
    }

    /**
     * Get the user who created the outside user record.
     *
     * @return BelongsTo
     */
    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Get the user who last updated the outside user record.
     *
     * @return BelongsTo
     */
    public function updatedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    /**
     * Get the library member record associated with the outside user.
     *
     * @return MorphOne
     */
    public function member(): MorphOne
    {
        return $this->morphOne(LibraryMember::class, 'memberable');
    }
}
