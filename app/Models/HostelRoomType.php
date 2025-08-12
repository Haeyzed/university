<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;

/**
 * @class HostelRoomType
 * @brief Model for managing hostel room types.
 *
 * This model represents the 'hostel_room_types' table, defining
 * categories for different types of hostel rooms.
 *
 * @property int $id
 * @property string $title
 * @property float|null $fee
 * @property string|null $description
 * @property bool $status
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 */
class HostelRoomType extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'title',
        'fee',
        'description',
        'status',
    ];

    /**
     * Get the hostel rooms of this type.
     *
     * @return HasMany
     */
    public function rooms(): HasMany
    {
        return $this->hasMany(HostelRoom::class, 'room_type_id');
    }
}
