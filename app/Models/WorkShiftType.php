<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;

/**
 * @class WorkShiftType
 * @brief Model for managing work shift types.
 *
 * This model represents the 'work_shift_types' table, defining
 * different categories of work shifts (e.g., Morning, Evening).
 *
 * @property int $id
 * @property string $title
 * @property string $slug
 * @property string|null $description
 * @property bool $status
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 */
class WorkShiftType extends Model
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
     * Get all staffs associated with this work shift type.
     *
     * @return HasMany
     */
    public function staffs(): HasMany
    {
        return $this->hasMany(User::class, 'work_shift', 'id');
    }
}
