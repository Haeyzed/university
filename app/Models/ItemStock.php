<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

/**
 * @class ItemStock
 * @brief Model for managing item stock records.
 *
 * This model represents the 'item_stocks' table, tracking
 * the acquisition and storage of inventory items.
 *
 * @property int $id
 * @property int $item_id
 * @property int $supplier_id
 * @property int $store_id
 * @property int $quantity
 * @property float|null $price
 * @property string $date
 * @property string|null $reference
 * @property int|null $payment_method
 * @property string|null $description
 * @property string|null $attach
 * @property bool $status
 * @property int|null $created_by
 * @property int|null $updated_by
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 */
class ItemStock extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'item_id',
        'supplier_id',
        'store_id',
        'quantity',
        'price',
        'date',
        'reference',
        'payment_method',
        'description',
        'attach',
        'status',
        'created_by',
        'updated_by',
    ];

    /**
     * Get the item associated with the stock.
     *
     * @return BelongsTo
     */
    public function item(): BelongsTo
    {
        return $this->belongsTo(Item::class);
    }

    /**
     * Get the supplier of the item.
     *
     * @return BelongsTo
     */
    public function supplier(): BelongsTo
    {
        return $this->belongsTo(ItemSupplier::class, 'supplier_id');
    }

    /**
     * Get the store where the item is stocked.
     *
     * @return BelongsTo
     */
    public function store(): BelongsTo
    {
        return $this->belongsTo(ItemStore::class, 'store_id');
    }

    /**
     * Get the user who created the stock record.
     *
     * @return BelongsTo
     */
    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Get the user who last updated the stock record.
     *
     * @return BelongsTo
     */
    public function updatedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }
}
