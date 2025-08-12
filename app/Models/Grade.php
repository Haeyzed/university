<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

/**
 * @class Grade
 * @brief Model for managing grading system.
 *
 * This model represents the 'grades' table, defining
 * grade points, minimum and maximum marks, and remarks.
 *
 * @property int $id
 * @property string $title
 * @property float $point
 * @property float $min_mark
 * @property float $max_mark
 * @property string|null $remark
 * @property bool $status
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 */
class Grade extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'title',
        'point',
        'min_mark',
        'max_mark',
        'remark',
        'status',
    ];
}
