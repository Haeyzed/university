<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

/**
 * @class ItemIssue
 * @brief Model for managing item issue records.
 *
 * This model represents the 'item_issues' table, tracking
 * the issuance and return of items from inventory.
 *
 * @property int $id
 * @property int $item_id
 * @property int $user_id
 * @property int $quantity
 * @property string|null $issue_date
 * @property string|null $due_date
 * @property string|null $return_date
 * @property float|null $penalty
 * @property string|null $note
 * @property string|null $attach
 * @property int $status
 * @property int|null $issued_by
 * @property int|null $received_by
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 */
class ItemIssue extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'item_id',
        'user_id',
        'quantity',
        'issue_date',
        'due_date',
        'return_date',
        'penalty',
        'note',
        'attach',
        'status',
        'issued_by',
        'received_by',
    ];

    /**
     * Get the item that was issued.
     *
     * @return BelongsTo
     */
    public function item(): BelongsTo
    {
        return $this->belongsTo(Item::class);
    }

    /**
     * Get the user to whom the item was issued.
     *
     * @return BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the user who issued the item.
     *
     * @return BelongsTo
     */
    public function issuedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'issued_by');
    }

    /**
     * Get the user who received the returned item.
     *
     * @return BelongsTo
     */
    public function receivedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'received_by');
    }
}
