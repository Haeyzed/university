<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;

/**
 * @class ExamType
 * @brief Model for managing exam types.
 *
 * This model represents the 'exam_types' table, defining
 * different categories of exams (e.g., Midterm, Final).
 *
 * @property int $id
 * @property string $title
 * @property float $marks
 * @property float $contribution
 * @property string|null $description
 * @property bool $status
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 */
class ExamType extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'title',
        'marks',
        'contribution',
        'description',
        'status',
    ];

    /**
     * Get the exams associated with this type.
     *
     * @return HasMany
     */
    public function exams(): HasMany
    {
        return $this->hasMany(Exam::class);
    }

    /**
     * Get the exam routines associated with this type.
     *
     * @return HasMany
     */
    public function routines(): HasMany
    {
        return $this->hasMany(ExamRoutine::class);
    }
}
