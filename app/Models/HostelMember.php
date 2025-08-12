<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Support\Carbon;

/**
 * @class HostelMember
 * @brief Model for managing hostel members.
 *
 * This model represents the 'hostel_members' table, linking
 * users or students to hostel rooms.
 *
 * @property int $id
 * @property string $hostelable_type
 * @property int $hostelable_id
 * @property int $hostel_room_id
 * @property string|null $start_date
 * @property string|null $end_date
 * @property string|null $note
 * @property int $status
 * @property int|null $created_by
 * @property int|null $updated_by
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 */
class HostelMember extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'hostelable_type',
        'hostelable_id',
        'hostel_room_id',
        'start_date',
        'end_date',
        'note',
        'status',
        'created_by',
        'updated_by',
    ];

    /**
     * Get the parent hostelable model (e.g., User, Student).
     *
     * @return MorphTo
     */
    public function hostelable(): MorphTo
    {
        return $this->morphTo();
    }

    /**
     * Get the hostel room.
     *
     * @return BelongsTo
     */
    public function room(): BelongsTo
    {
        return $this->belongsTo(HostelRoom::class);
    }

    /**
     * Get the user who created the hostel member record.
     *
     * @return BelongsTo
     */
    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Get the user who last updated the hostel member record.
     *
     * @return BelongsTo
     */
    public function updatedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }
}
