<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

/**
 * @class PostalExchange
 * @brief Model for managing postal exchanges (incoming/outgoing mail).
 *
 * This model represents the 'postal_exchanges' table, tracking
 * the movement of physical mail and packages.
 *
 * @property int $id
 * @property int $type
 * @property int $category_id
 * @property string $title
 * @property string|null $reference
 * @property string|null $from
 * @property string|null $to
 * @property string|null $note
 * @property string|null $date
 * @property string|null $attach
 * @property int $status
 * @property int|null $created_by
 * @property int|null $updated_by
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 */
class PostalExchange extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'type',
        'category_id',
        'title',
        'reference',
        'from',
        'to',
        'note',
        'date',
        'attach',
        'status',
        'created_by',
        'updated_by',
    ];

    /**
     * Get the postal exchange type.
     *
     * @return BelongsTo
     */
    public function type(): BelongsTo
    {
        return $this->belongsTo(PostalExchangeType::class, 'category_id');
    }

    /**
     * Get the user who created the postal exchange record.
     *
     * @return BelongsTo
     */
    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Get the user who last updated the postal exchange record.
     *
     * @return BelongsTo
     */
    public function updatedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }
}
