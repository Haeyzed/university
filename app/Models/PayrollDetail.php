<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

/**
 * @class PayrollDetail
 * @brief Model for managing payroll details (allowances/deductions).
 *
 * This model represents the 'payroll_details' table, storing
 * individual components of a staff member's payroll.
 *
 * @property int $id
 * @property int $payroll_id
 * @property string|null $title
 * @property float $amount
 * @property int $status
 * @property int|null $created_by
 * @property int|null $updated_by
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 */
class PayrollDetail extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'payroll_id',
        'title',
        'amount',
        'status',
        'created_by',
        'updated_by',
    ];

    /**
     * Get the payroll that owns the detail.
     *
     * @return BelongsTo
     */
    public function payroll(): BelongsTo
    {
        return $this->belongsTo(Payroll::class);
    }

    /**
     * Get the user who created the payroll detail.
     *
     * @return BelongsTo
     */
    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Get the user who last updated the payroll detail.
     *
     * @return BelongsTo
     */
    public function updatedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }
}
