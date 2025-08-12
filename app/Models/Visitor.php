<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

/**
 * @class Visitor
 * @brief Model for managing visitor records.
 *
 * This model represents the 'visitors' table, storing details
 * of individuals visiting the institution.
 *
 * @property int $id
 * @property int $purpose_id
 * @property int|null $department_id
 * @property string $name
 * @property string|null $father_name
 * @property string|null $phone
 * @property string|null $email
 * @property string|null $address
 * @property string|null $visit_from
 * @property string|null $id_no
 * @property string|null $token
 * @property string $date
 * @property string|null $in_time
 * @property string|null $out_time
 * @property string|null $persons
 * @property string|null $note
 * @property string|null $attach
 * @property bool $status
 * @property int|null $created_by
 * @property int|null $updated_by
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 */
class Visitor extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'purpose_id',
        'department_id',
        'name',
        'father_name',
        'phone',
        'email',
        'address',
        'visit_from',
        'id_no',
        'token',
        'date',
        'in_time',
        'out_time',
        'persons',
        'note',
        'attach',
        'status',
        'created_by',
        'updated_by',
    ];

    /**
     * Get the visit purpose.
     *
     * @return BelongsTo
     */
    public function purpose(): BelongsTo
    {
        return $this->belongsTo(VisitPurpose::class, 'purpose_id');
    }

    /**
     * Get the department visited.
     *
     * @return BelongsTo
     */
    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class);
    }

    /**
     * Get the user who created the visitor record.
     *
     * @return BelongsTo
     */
    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Get the user who last updated the visitor record.
     *
     * @return BelongsTo
     */
    public function updatedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }
}
