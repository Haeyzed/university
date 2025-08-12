<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

/**
 * @class StudentLeave
 * @brief Model for managing student leave applications.
 *
 * This model represents the 'student_leaves' table, storing details
 * of leave requests submitted by students.
 *
 * @property int $id
 * @property int $student_id
 * @property int|null $review_by
 * @property string $apply_date
 * @property string $from_date
 * @property string $to_date
 * @property string|null $subject
 * @property string|null $reason
 * @property string|null $attach
 * @property string|null $note
 * @property int $status
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 */
class StudentLeave extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'student_id',
        'review_by',
        'apply_date',
        'from_date',
        'to_date',
        'subject',
        'reason',
        'attach',
        'note',
        'status',
    ];

    /**
     * Get the student who applied for the leave.
     *
     * @return BelongsTo
     */
    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

    /**
     * Get the user who reviewed the leave application.
     *
     * @return BelongsTo
     */
    public function reviewBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'review_by');
    }
}
