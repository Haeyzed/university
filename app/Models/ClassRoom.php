<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;

/**
 * @class ClassRoom
 * @brief Model for managing classrooms.
 *
 * This model represents the 'class_rooms' table, storing details
 * about physical classrooms or virtual meeting spaces.
 *
 * @property int $id
 * @property string $title
 * @property string $slug
 * @property string|null $floor
 * @property string|null $capacity
 * @property string|null $type
 * @property string|null $description
 * @property bool $status
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 */
class ClassRoom extends Model
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
        'floor',
        'capacity',
        'type',
        'description',
        'status',
    ];

    /**
     * Get the class routines associated with this classroom.
     *
     * @return HasMany
     */
    public function classRoutines(): HasMany
    {
        return $this->hasMany(ClassRoutine::class, 'room_id');
    }

    /**
     * The programs that use this classroom.
     *
     * @return BelongsToMany
     */
    public function programs(): BelongsToMany
    {
        return $this->belongsToMany(Program::class, 'program_class_room', 'class_room_id', 'program_id');
    }

    /**
     * The exam routines associated with this classroom.
     *
     * @return BelongsToMany
     */
    public function examRoutines(): BelongsToMany
    {
        return $this->belongsToMany(ExamRoutine::class, 'exam_routine_room', 'room_id', 'exam_routine_id');
    }
}
