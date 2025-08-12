<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Support\Carbon;

/**
 * @class Fee
 * @brief Model for managing individual student fees.
 *
 * This model represents the 'fees' table, storing details
 * of fees assigned and paid by students.
 *
 * @property int $id
 * @property int $student_enroll_id
 * @property int $category_id
 * @property float $fee_amount
 * @property float $fine_amount
 * @property float $discount_amount
 * @property float $paid_amount
 * @property string $assign_date
 * @property string $due_date
 * @property string|null $pay_date
 * @property int|null $payment_method
 * @property string|null $note
 * @property int $status
 * @property int|null $created_by
 * @property int|null $updated_by
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 */
class Fee extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'student_enroll_id',
        'category_id',
        'fee_amount',
        'fine_amount',
        'discount_amount',
        'paid_amount',
        'assign_date',
        'due_date',
        'pay_date',
        'payment_method',
        'note',
        'status',
        'created_by',
        'updated_by',
    ];

    /**
     * Get the student enrollment associated with the fee.
     *
     * @return BelongsTo
     */
    public function studentEnroll(): BelongsTo
    {
        return $this->belongsTo(StudentEnroll::class);
    }

    /**
     * Get the fees category.
     *
     * @return BelongsTo
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(FeesCategory::class, 'category_id');
    }

    /**
     * Get the user who created the fee record.
     *
     * @return BelongsTo
     */
    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Get the user who last updated the fee record.
     *
     * @return BelongsTo
     */
    public function updatedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    /**
     * Get the transaction associated with the fee.
     *
     * @return MorphOne
     */
    public function transaction(): MorphOne
    {
        return $this->morphOne(Transaction::class, 'transactionable');
    }
}
