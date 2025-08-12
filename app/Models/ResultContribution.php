<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

/**
 * @class ResultContribution
 * @brief Model for managing result contribution settings.
 *
 * This model represents the 'result_contributions' table, defining
 * the percentage contribution of different components (e.g., attendance, assignments)
 * to the final result.
 *
 * @property int $id
 * @property float $attendances
 * @property float $assignments
 * @property float $activities
 * @property bool $status
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 */
class ResultContribution extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'attendances',
        'assignments',
        'activities',
        'status',
    ];
}
