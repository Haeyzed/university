<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Carbon;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

/**
 * @class User
 * @brief Model for managing user (staff/admin) information.
 *
 * This model represents the 'users' table, storing comprehensive
 * details about staff members and administrators, including their roles and permissions.
 *
 * @property int $id
 * @property string $staff_id
 * @property int|null $department_id
 * @property int|null $designation_id
 * @property string $first_name
 * @property string $last_name
 * @property string|null $father_name
 * @property string|null $mother_name
 * @property string $email
 * @property Carbon|null $email_verified_at
 * @property string $password
 * @property string|null $password_text
 * @property int $gender
 * @property string $dob
 * @property string|null $joining_date
 * @property string|null $ending_date
 * @property string|null $phone
 * @property string|null $emergency_phone
 * @property string|null $religion
 * * @property string|null $caste
 * @property string|null $mother_tongue
 * @property int|null $marital_status
 * @property int|null $blood_group
 * @property string|null $nationality
 * @property string|null $national_id
 * @property string|null $passport_no
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
 * @property string|null $graduation_academy
 * @property string|null $year_of_graduation
 * @property string|null $graduation_field
 * @property string|null $experience
 * @property string|null $note
 * @property float $basic_salary
 * @property int $contract_type
 * @property int|null $work_shift
 * @property int $salary_type
 * @property string|null $epf_no
 * @property string|null $bank_account_name
 * @property string|null $bank_account_no
 * @property string|null $bank_name
 * @property string|null $ifsc_code
 * @property string|null $bank_brach
 * @property string|null $tin_no
 * @property string|null $photo
 * @property string|null $signature
 * @property string|null $resume
 * @property string|null $joining_letter
 * @property bool $is_admin
 * @property bool $login
 * @property int $status
 * @property string|null $remember_token
 * @property int|null $created_by
 * @property int|null $updated_by
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 */
class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'staff_id',
        'department_id',
        'designation_id',
        'first_name',
        'last_name',
        'father_name',
        'mother_name',
        'email',
        'email_verified_at',
        'password',
        'password_text',
        'gender',
        'dob',
        'joining_date',
        'ending_date',
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
        'graduation_academy',
        'year_of_graduation',
        'graduation_field',
        'experience',
        'note',
        'basic_salary',
        'contract_type',
        'work_shift',
        'salary_type',
        'epf_no',
        'bank_account_name',
        'bank_account_no',
        'bank_name',
        'ifsc_code',
        'bank_brach',
        'tin_no',
        'photo',
        'signature',
        'resume',
        'joining_letter',
        'is_admin',
        'login',
        'status',
        'remember_token',
        'created_by',
        'updated_by',
    ];

    /**
     * Get the department that the user belongs to.
     *
     * @return BelongsTo
     */
    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class);
    }

    /**
     * Get the designation that the user holds.
     *
     * @return BelongsTo
     */
    public function designation(): BelongsTo
    {
        return $this->belongsTo(Designation::class);
    }

    /**
     * The programs that the user is associated with (e.g., as a teacher).
     *
     * @return BelongsToMany
     */
    public function programs(): BelongsToMany
    {
        return $this->belongsToMany(Program::class, 'user_program', 'user_id', 'program_id');
    }

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
     * Get the documents associated with the user.
     *
     * @return MorphToMany
     */
    public function documents(): MorphToMany
    {
        return $this->morphToMany(Document::class, 'docable');
    }

    /**
     * Get the notes created by or for this user.
     *
     * @return HasMany
     */
    public function notes(): HasMany
    {
        return $this->hasMany(Note::class, 'created_by');
    }

    /**
     * Get the staff daily attendance records for this user.
     *
     * @return HasMany
     */
    public function staffAttendances(): HasMany
    {
        return $this->hasMany(StaffAttendance::class);
    }

    /**
     * Get the staff hourly attendance records for this user.
     *
     * @return HasMany
     */
    public function staffHourlyAttendances(): HasMany
    {
        return $this->hasMany(StaffHourlyAttendance::class);
    }

    /**
     * Get the leave applications submitted by this user.
     *
     * @return HasMany
     */
    public function leaves(): HasMany
    {
        return $this->hasMany(Leave::class);
    }

    /**
     * Get the payroll records for this user.
     *
     * @return HasMany
     */
    public function payrolls(): HasMany
    {
        return $this->hasMany(Payroll::class);
    }

    /**
     * Get the payroll details created by this user.
     *
     * @return HasMany
     */
    public function payrollDetails(): HasMany
    {
        return $this->hasMany(PayrollDetail::class, 'created_by');
    }

    /**
     * Get the assignments assigned by this user.
     *
     * @return HasMany
     */
    public function assignments(): HasMany
    {
        return $this->hasMany(Assignment::class, 'assign_by');
    }

    /**
     * Get the class routines taught by this user.
     *
     * @return HasMany
     */
    public function classRoutines(): HasMany
    {
        return $this->hasMany(ClassRoutine::class, 'teacher_id');
    }

    /**
     * Get the contents created by this user.
     *
     * @return HasMany
     */
    public function contents(): HasMany
    {
        return $this->hasMany(Content::class, 'created_by');
    }

    /**
     * Get the email notifications created by this user.
     *
     * @return HasMany
     */
    public function emailNotifies(): HasMany
    {
        return $this->hasMany(EmailNotify::class, 'created_by');
    }

    /**
     * Get the enquiries created by this user.
     *
     * @return HasMany
     */
    public function enquiries(): HasMany
    {
        return $this->hasMany(Enquiry::class, 'created_by');
    }

    /**
     * Get the exam routines where this user is an invigilator.
     *
     * @return BelongsToMany
     */
    public function examRoutines(): BelongsToMany
    {
        return $this->belongsToMany(ExamRoutine::class, 'exam_routine_user', 'user_id', 'exam_routine_id');
    }

    /**
     * Get the expenses created by this user.
     *
     * @return HasMany
     */
    public function expenses(): HasMany
    {
        return $this->hasMany(Expense::class, 'created_by');
    }

    /**
     * Get the fees created by this user.
     *
     * @return HasMany
     */
    public function fees(): HasMany
    {
        return $this->hasMany(Fee::class, 'created_by');
    }

    /**
     * Get the fees masters created by this user.
     *
     * @return HasMany
     */
    public function feesMasters(): HasMany
    {
        return $this->hasMany(FeesMaster::class, 'created_by');
    }

    /**
     * Get the incomes created by this user.
     *
     * @return HasMany
     */
    public function incomes(): HasMany
    {
        return $this->hasMany(Income::class, 'created_by');
    }

    /**
     * Get the item issues created by this user.
     *
     * @return HasMany
     */
    public function itemIssues(): HasMany
    {
        return $this->hasMany(ItemIssue::class, 'issued_by');
    }

    /**
     * Get the item stocks created by this user.
     *
     * @return HasMany
     */
    public function itemStocks(): HasMany
    {
        return $this->hasMany(ItemStock::class, 'created_by');
    }

    /**
     * Get the library member record associated with the user.
     *
     * @return MorphOne
     */
    public function libraryMember(): MorphOne
    {
        return $this->morphOne(LibraryMember::class, 'memberable');
    }

    /**
     * Get the meeting schedules created by this user.
     *
     * @return HasMany
     */
    public function meetingSchedules(): HasMany
    {
        return $this->hasMany(MeetingSchedule::class, 'created_by');
    }

    /**
     * Get the notices created by this user.
     *
     * @return HasMany
     */
    public function notices(): HasMany
    {
        return $this->hasMany(Notice::class, 'created_by');
    }

    /**
     * Get the postal exchanges created by this user.
     *
     * @return HasMany
     */
    public function postalExchanges(): HasMany
    {
        return $this->hasMany(PostalExchange::class, 'created_by');
    }

    /**
     * Get the SMS notifications created by this user.
     *
     * @return HasMany
     */
    public function smsNotifies(): HasMany
    {
        return $this->hasMany(SMSNotify::class, 'created_by');
    }

    /**
     * Get the students created by this user.
     *
     * @return HasMany
     */
    public function students(): HasMany
    {
        return $this->hasMany(Student::class, 'created_by');
    }

    /**
     * Get the student assignments created by this user.
     *
     * @return HasMany
     */
    public function studentAssignments(): HasMany
    {
        return $this->hasMany(StudentAssignment::class, 'created_by');
    }

    /**
     * Get the student attendances created by this user.
     *
     * @return HasMany
     */
    public function studentAttendances(): HasMany
    {
        return $this->hasMany(StudentAttendance::class, 'created_by');
    }

    /**
     * Get the student enrollments created by this user.
     *
     * @return HasMany
     */
    public function studentEnrolls(): HasMany
    {
        return $this->hasMany(StudentEnroll::class, 'created_by');
    }

    /**
     * Get the student leaves reviewed by this user.
     *
     * @return HasMany
     */
    public function studentLeaves(): HasMany
    {
        return $this->hasMany(StudentLeave::class, 'review_by');
    }

    /**
     * Get the student transfers created by this user.
     *
     * @return HasMany
     */
    public function studentTransfers(): HasMany
    {
        return $this->hasMany(StudentTransfer::class, 'created_by');
    }

    /**
     * Get the subject markings created by this user.
     *
     * @return HasMany
     */
    public function subjectMarkings(): HasMany
    {
        return $this->hasMany(SubjectMarking::class, 'created_by');
    }

    /**
     * Get the transactions created by this user.
     *
     * @return HasMany
     */
    public function transactions(): HasMany
    {
        return $this->hasMany(Transaction::class, 'created_by');
    }

    /**
     * Get the transport member record associated with the user.
     *
     * @return MorphOne
     */
    public function transportMember(): MorphOne
    {
        return $this->morphOne(TransportMember::class, 'transportable');
    }

    /**
     * Get the visitors created by this user.
     *
     * @return HasMany
     */
    public function visitors(): HasMany
    {
        return $this->hasMany(Visitor::class, 'created_by');
    }

    /**
     * Get the hostel member record associated with the user.
     *
     * @return MorphOne
     */
    public function hostelMember(): MorphOne
    {
        return $this->morphOne(HostelMember::class, 'hostelable');
    }

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
}
