<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Carbon;

/**
 * @class FeesFine
 * @brief Model for managing fees fines.
 *
 * This model represents the 'fees_fines' table, defining
 * penalties for late fee payments.
 *
 * @property int $id
 * @property int $start_day
 * @property int $end_day
 * @property float $amount
 * @property int $type
 * @property bool $status
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 */
class FeesFine extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'start_day',
        'end_day',
        'amount',
        'type',
        'status',
    ];

    /**
     * The fees categories this fine applies to.
     *
     * @return BelongsToMany
     */
    public function feesCategories(): BelongsToMany
    {
        return $this->belongsToMany(FeesCategory::class, 'fees_category_fees_fine', 'fees_fine_id', 'fees_category_id');
    }
}
