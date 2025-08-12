<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;

/**
 * @class MeetingType
 * @brief Model for managing meeting types.
 *
 * This model represents the 'meeting_types' table, defining
 * categories for different types of meetings.
 *
 * @property int $id
 * @property string $title
 * @property string $slug
 * @property string|null $description
 * @property bool $status
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 */
class MeetingType extends Model
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
     * Get the meeting schedules associated with this type.
     *
     * @return HasMany
     */
    public function schedules(): HasMany
    {
        return $this->hasMany(MeetingSchedule::class, 'type_id');
    }
}
