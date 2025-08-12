<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;

/**
 * @class PostalExchangeType
 * @brief Model for managing postal exchange types.
 *
 * This model represents the 'postal_exchange_types' table, defining
 * categories for different types of postal exchanges.
 *
 * @property int $id
 * @property string $title
 * @property string $slug
 * @property string|null $description
 * @property bool $status
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 */
class PostalExchangeType extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'title',
        'slug',
        'description',
        'status',
    ];

    /**
     * Get the postal exchanges associated with this type.
     *
     * @return HasMany
     */
    public function exchanges(): HasMany
    {
        return $this->hasMany(PostalExchange::class, 'category_id');
    }
}
