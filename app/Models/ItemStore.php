<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;

/**
 * @class ItemStore
 * @brief Model for managing item storage locations.
 *
 * This model represents the 'item_stores' table, defining
 * various locations where inventory items are stored.
 *
 * @property int $id
 * @property string $title
 * @property string $store_no
 * @property string|null $in_charge
 * @property string|null $email
 * @property string|null $phone
 * @property string|null $address
 * @property bool $status
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 */
class ItemStore extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'title',
        'store_no',
        'in_charge',
        'email',
        'phone',
        'address',
        'status',
    ];

    /**
     * Get the item stocks in this store.
     *
     * @return HasMany
     */
    public function stocks(): HasMany
    {
        return $this->hasMany(ItemStock::class, 'store_id');
    }
}
