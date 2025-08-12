<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Support\Carbon;

/**
 * @class LibraryMember
 * @brief Model for managing library members.
 *
 * This model represents the 'library_members' table, linking
 * users, students, or outside users as library members.
 *
 * @property int $id
 * @property string $memberable_type
 * @property int $memberable_id
 * @property string $library_id
 * @property string $date
 * @property int $status
 * @property int|null $created_by
 * @property int|null $updated_by
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 */
class LibraryMember extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'memberable_type',
        'memberable_id',
        'library_id',
        'date',
        'status',
        'created_by',
        'updated_by',
    ];

    /**
     * Get the parent memberable model (e.g., User, Student, OutsideUser).
     *
     * @return MorphTo
     */
    public function memberable(): MorphTo
    {
        return $this->morphTo();
    }

    /**
     * Get the user who created the library member record.
     *
     * @return BelongsTo
     */
    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Get the user who last updated the library member record.
     *
     * @return BelongsTo
     */
    public function updatedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    /**
     * Get the issue returns for this library member.
     *
     * @return HasMany
     */
    public function issueReturns(): HasMany
    {
        return $this->hasMany(IssueReturn::class, 'member_id');
    }
}
