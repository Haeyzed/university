<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

/**
 * @class ScheduleSetting
 * @brief Model for managing schedule settings.
 *
 * This model represents the 'schedule_settings' table, storing
 * configurations for scheduled tasks like email or SMS notifications.
 *
 * @property int $id
 * @property string $slug
 * @property int $day
 * @property string $time
 * @property bool $email
 * @property bool $sms
 * @property bool $status
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 */
class ScheduleSetting extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'slug',
        'day',
        'time',
        'email',
        'sms',
        'status',
    ];
}
