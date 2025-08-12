<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Support\Carbon;

/**
 * @class Note
 * @brief Model for managing general notes.
 *
 * This model represents the 'notes' table, storing notes
 * that can be attached to various other models polymorphically.
 *
 * @property int $id
 * @property string $noteable_type
 * @property int $noteable_id
 * @property string $title
 * @property string|null $description
 * @property string|null $attach
 * @property bool $status
 * @property int|null $created_by
 * @property int|null $updated_by
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 */
class Note extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'noteable_type',
        'noteable_id',
        'title',
        'description',
        'attach',
        'status',
        'created_by',
        'updated_by',
    ];

    /**
     * Get the parent noteable model (e.g., User, Student).
     *
     * @return MorphTo
     */
    public function noteable(): MorphTo
    {
        return $this->morphTo();
    }

    /**
     * Get the user who created the note.
     *
     * @return BelongsTo
     */
    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Get the user who last updated the note.
     *
     * @return BelongsTo
     */
    public function updatedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }
}
