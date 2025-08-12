<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

/**
 * @class Event
 * @brief Model for managing events.
 *
 * This model represents the 'events' table, storing details
 * about various events happening in the institution.
 *
 * @property int $id
 * @property string $title
 * @property string $start_date
 * @property string $end_date
 * @property string|null $color
 * @property bool $status
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 */
class Event extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'title',
        'start_date',
        'end_date',
        'color',
        'status',
    ];
}
