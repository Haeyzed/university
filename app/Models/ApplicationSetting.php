<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

/**
 * @class ApplicationSetting
 * @brief Model for managing application-related settings.
 *
 * This model represents the 'application_settings' table, storing
 * various configurations for the application process.
 *
 * @property int $id
 * @property string $slug
 * @property string|null $title
 * @property string|null $header_left
 * @property string|null $header_center
 * @property string|null $header_right
 * @property string|null $body
 * @property string|null $footer_left
 * @property string|null $footer_center
 * @property string|null $footer_right
 * @property string|null $logo_left
 * @property string|null $logo_right
 * @property string|null $background
 * @property float|null $fee_amount
 * @property bool $pay_online
 * @property bool $status
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 */
class ApplicationSetting extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'slug',
        'title',
        'header_left',
        'header_center',
        'header_right',
        'body',
        'footer_left',
        'footer_center',
        'footer_right',
        'logo_left',
        'logo_right',
        'background',
        'fee_amount',
        'pay_online',
        'status',
    ];

    /**
     * Get the first active application setting.
     *
     * @return ApplicationSetting|null
     */
    public static function active(): ?self
    {
        return self::query()->where('status', true)->first();
    }
}
