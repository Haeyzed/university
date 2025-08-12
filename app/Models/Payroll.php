<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Support\Carbon;

/**
 * @class Payroll
 * @brief Model for managing staff payrolls.
 *
 * This model represents the 'payrolls' table, storing details
 * of salary calculations and payments for staff members.
 *
 * @property int $id
 * @property int $user_id
 * @property float $basic_salary
 * @property int $salary_type
 * @property float $total_earning
 * @property float $total_allowance
 * @property float $bonus
 * @property float $total_deduction
 * @property float $gross_salary
 * @property float $tax
 * @property float $net_salary
 * @property string $salary_month
 * @property string|null $pay_date
 * @property int|null $payment_method
 * @property string|null $note
 * @property int $status
 * @property int|null $created_by
 * @property int|null $updated_by
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 */
class Payroll extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'basic_salary',
        'salary_type',
        'total_earning',
        'total_allowance',
        'bonus',
        'total_deduction',
        'gross_salary',
        'tax',
        'net_salary',
        'salary_month',
        'pay_date',
        'payment_method',
        'note',
        'status',
        'created_by',
        'updated_by',
    ];

    /**
     * Get the user (staff) associated with the payroll.
     *
     * @return BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the payroll details (allowances/deductions) for this payroll.
     *
     * @return HasMany
     */
    public function details(): HasMany
    {
        return $this->hasMany(PayrollDetail::class);
    }

    /**
     * Get the user who created the payroll record.
     *
     * @return BelongsTo
     */
    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Get the user who last updated the payroll record.
     *
     * @return BelongsTo
     */
    public function updatedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    /**
     * Get the transaction associated with the payroll.
     *
     * @return MorphOne
     */
    public function transaction(): MorphOne
    {
        return $this->morphOne(Transaction::class, 'transactionable');
    }
}
