<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

/**
 * @class Complain
 * @brief Model for managing complaints.
 *
 * This model represents the 'complains' table, storing details
 * of complaints, including their source, type, and the user who filed it.
 *
 * @property int $id
 * @property int $type_id
 * @property int|null $source_id
 * @property string $name
 * @property string|null $father_name
 * @property string|null $phone
 * @property string|null $email
 * @property string $date
 * @property string|null $action_taken
 * @property string|null $assigned
 * @property string|null $issue
 * @property string|null $note
 * @property string|null $attach
 * @property int $status
 * @property int|null $created_by
 * @property int|null $updated_by
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 */
class Complain extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'type_id',
        'source_id',
        'name',
        'father_name',
        'phone',
        'email',
        'date',
        'action_taken',
        'assigned',
        'issue',
        'note',
        'attach',
        'status',
        'created_by',
        'updated_by',
    ];

    /**
     * Get the complain type.
     *
     * @return BelongsTo
     */
    public function type(): BelongsTo
    {
        return $this->belongsTo(ComplainType::class, 'type_id');
    }

    /**
     * Get the complain source.
     *
     * @return BelongsTo
     */
    public function source(): BelongsTo
    {
        return $this->belongsTo(ComplainSource::class, 'source_id');
    }

    /**
     * Get the user who created the complain.
     *
     * @return BelongsTo
     */
    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Get the user who last updated the complain.
     *
     * @return BelongsTo
     */
    public function updatedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    /**
     * Get the user who is assigned the complain.
     *
     * @return BelongsTo
     */
    public function assign(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned');
    }
}
