<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;

/**
 * @class Item
 * @brief Model for managing inventory items.
 *
 * This model represents the 'items' table, storing details
 * about various items in the institution's inventory.
 *
 * @property int $id
 * @property string $name
 * @property int $category_id
 * @property string|null $unit
 * @property string|null $serial_number
 * @property int $quantity
 * @property string|null $description
 * @property string|null $attach
 * @property bool $status
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 */
class Item extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'category_id',
        'unit',
        'serial_number',
        'quantity',
        'description',
        'attach',
        'status',
    ];

    /**
     * Get the item category.
     *
     * @return BelongsTo
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(ItemCategory::class, 'category_id');
    }

    /**
     * Get the item stocks for this item.
     *
     * @return HasMany
     */
    public function stocks(): HasMany
    {
        return $this->hasMany(ItemStock::class);
    }

    /**
     * Get the item issues for this item.
     *
     * @return HasMany
     */
    public function issues(): HasMany
    {
        return $this->hasMany(ItemIssue::class);
    }
}
