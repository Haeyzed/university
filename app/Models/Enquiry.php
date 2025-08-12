<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

/**
 * @class Enquiry
 * @brief Model for managing enquiries.
 *
 * This model represents the 'enquiries' table, storing details
 * of inquiries received, including reference, source, program, and status.
 *
 * @property int $id
 * @property int|null $reference_id
 * @property int|null $source_id
 * @property int|null $program_id
 * @property string $name
 * @property string|null $father_name
 * @property string|null $phone
 * @property string|null $email
 * @property string|null $address
 * @property string|null $purpose
 * @property string|null $note
 * @property string $date
 * @property string|null $follow_up_date
 * @property string|null $assigned
 * @property int $number_of_students
 * @property int $status
 * @property int|null $created_by
 * @property int|null $updated_by
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 */
class Enquiry extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'reference_id',
        'source_id',
        'program_id',
        'name',
        'father_name',
        'phone',
        'email',
        'address',
        'purpose',
        'note',
        'date',
        'follow_up_date',
        'assigned',
        'number_of_students',
        'status',
        'created_by',
        'updated_by',
    ];

    /**
     * Get the enquiry reference.
     *
     * @return BelongsTo
     */
    public function reference(): BelongsTo
    {
        return $this->belongsTo(EnquiryReference::class, 'reference_id');
    }

    /**
     * Get the enquiry source.
     *
     * @return BelongsTo
     */
    public function source(): BelongsTo
    {
        return $this->belongsTo(EnquirySource::class, 'source_id');
    }

    /**
     * Get the program associated with the enquiry.
     *
     * @return BelongsTo
     */
    public function program(): BelongsTo
    {
        return $this->belongsTo(Program::class);
    }

    /**
     * Get the user who is assigned the enquiry.
     *
     * @return BelongsTo
     */
    public function assign(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned');
    }

    /**
     * Get the user who created the enquiry.
     *
     * @return BelongsTo
     */
    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Get the user who last updated the enquiry.
     *
     * @return BelongsTo
     */
    public function updatedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }
}
