<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

/**
 * @class StudentRelative
 * @brief Model for managing student relatives.
 *
 * This model represents the 'student_relatives' table, storing
 * information about a student's family members or guardians.
 *
 * @property int $id
 * @property int $student_id
 * @property string|null $relation
 * @property string|null $name
 * @property string|null $occupation
 * @property string|null $email
 * @property string|null $phone
 * @property string|null $address
 * @property string|null $photo
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 */
class StudentRelative extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'student_id',
        'relation',
        'name',
        'occupation',
        'email',
        'phone',
        'address',
        'photo',
    ];

    /**
     * Get the student that owns the relative.
     *
     * @return BelongsTo
     */
    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }
}
