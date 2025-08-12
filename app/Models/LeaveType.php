<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;

/**
 * @class LeaveType
 * @brief Model for managing leave types.
 *
 * This model represents the 'leave_types' table, defining
 * different categories of leaves (e.g., Casual, Sick).
 *
 * @property int $id
 * @property string $title
 * @property string $slug
 * @property int $limit
 * @property string|null $description
 * @property bool $status
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 */
class LeaveType extends Model
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
        'limit',
        'description',
        'status',
    ];

    /**
     * Get the leaves associated with this type.
     *
     * @return HasMany
     */
    public function leaves(): HasMany
    {
        return $this->hasMany(Leave::class, 'type_id');
    }
}
