<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;

/**
 * @class HostelRoom
 * @brief Model for managing hostel rooms.
 *
 * This model represents the 'hostel_rooms' table, storing details
 * about individual rooms within hostels.
 *
 * @property int $id
 * @property string $name
 * @property int $hostel_id
 * @property int $room_type_id
 * @property int $bed
 * @property float|null $fee
 * @property string|null $description
 * @property bool $status
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 */
class HostelRoom extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'hostel_id',
        'room_type_id',
        'bed',
        'fee',
        'description',
        'status',
    ];

    /**
     * Get the hostel that owns the room.
     *
     * @return BelongsTo
     */
    public function hostel(): BelongsTo
    {
        return $this->belongsTo(Hostel::class);
    }

    /**
     * Get the room type.
     *
     * @return BelongsTo
     */
    public function roomType(): BelongsTo
    {
        return $this->belongsTo(HostelRoomType::class, 'room_type_id');
    }

    /**
     * Get the hostel members in this room.
     *
     * @return HasMany
     */
    public function hostelMembers(): HasMany
    {
        return $this->hasMany(HostelMember::class);
    }
}
