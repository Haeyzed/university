<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Support\Carbon;

/**
 * @class Transaction
 * @brief Model for managing financial transactions.
 *
 * This model represents the 'transactions' table, storing records
 * of financial movements, linked polymorphically to various sources
 * like fees, income, expenses, or payrolls.
 *
 * @property int $id
 * @property int $transactionable_id
 * @property string $transactionable_type
 * @property string $transaction_id
 * @property float $amount
 * @property int $type
 * @property int|null $created_by
 * @property int|null $updated_by
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 */
class Transaction extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'transactionable_id',
        'transactionable_type',
        'transaction_id',
        'amount',
        'type',
        'created_by',
        'updated_by',
    ];

    /**
     * Get the parent transactionable model (e.g., Fee, Income, Expense, Payroll).
     *
     * @return MorphTo
     */
    public function transactionable(): MorphTo
    {
        return $this->morphTo();
    }

    /**
     * Get the user who created the transaction record.
     *
     * @return BelongsTo
     */
    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Get the user who last updated the transaction record.
     *
     * @return BelongsTo
     */
    public function updatedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }
}
