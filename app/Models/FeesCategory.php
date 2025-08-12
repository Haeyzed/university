<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;

/**
 * @class FeesCategory
 * @brief Model for managing fees categories.
 *
 * This model represents the 'fees_categories' table, used to
 * categorize different types of fees (e.g., Admission, Semester).
 *
 * @property int $id
 * @property string $title
 * @property string $slug
 * @property string|null $description
 * @property bool $status
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 */
class FeesCategory extends Model
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
     * Get the fees associated with this category.
     *
     * @return HasMany
     */
    public function fees(): HasMany
    {
        return $this->hasMany(Fee::class, 'category_id');
    }

    /**
     * Get the fees masters associated with this category.
     *
     * @return HasMany
     */
    public function masters(): HasMany
    {
        return $this->hasMany(FeesMaster::class, 'category_id');
    }

    /**
     * The fees discounts that apply to this category.
     *
     * @return BelongsToMany
     */
    public function discounts(): BelongsToMany
    {
        return $this->belongsToMany(FeesDiscount::class, 'fees_category_fees_discount', 'fees_category_id', 'fees_discount_id');
    }

    /**
     * The fees fines that apply to this category.
     *
     * @return BelongsToMany
     */
    public function fines(): BelongsToMany
    {
        return $this->belongsToMany(FeesFine::class, 'fees_category_fees_fine', 'fees_category_id', 'fees_fine_id');
    }
}
