<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

/**
 * @class Expense
 * @brief Model for managing expenses.
 *
 * This model represents the 'expenses' table, storing details
 * of institutional expenditures, categorized by expense type.
 *
 * @property int $id
 * @property int $category_id
 * @property string $title
 * @property string|null $invoice_id
 * @property float $amount
 * @property string $date
 * @property string|null $reference
 * @property int|null $payment_method
 * @property string|null $note
 * @property string|null $attach
 * @property bool $status
 * @property int|null $created_by
 * @property int|null $updated_by
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 */
class Expense extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'category_id',
        'title',
        'invoice_id',
        'amount',
        'date',
        'reference',
        'payment_method',
        'note',
        'attach',
        'status',
        'created_by',
        'updated_by',
    ];

    /**
     * Get the expense category.
     *
     * @return BelongsTo
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(ExpenseCategory::class, 'category_id');
    }

    /**
     * Get the user who created the expense record.
     *
     * @return BelongsTo
     */
    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Get the user who last updated the expense record.
     *
     * @return BelongsTo
     */
    public function updatedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }
}
