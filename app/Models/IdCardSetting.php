<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

/**
 * @class IdCardSetting
 * @brief Model for managing ID card settings.
 *
 * This model represents the 'id_card_settings' table, storing
 * configurations for generating student and staff ID cards.
 *
 * @property int $id
 * @property string $slug
 * @property string|null $title
 * @property string|null $subtitle
 * @property string|null $logo
 * @property string|null $background
 * @property string|null $website_url
 * @property string|null $validity
 * @property string|null $address
 * @property string|null $prefix
 * @property bool $student_photo
 * @property bool $signature
 * @property bool $barcode
 * @property bool $status
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 */
class IdCardSetting extends Model
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
        'subtitle',
        'logo',
        'background',
        'website_url',
        'validity',
        'address',
        'prefix',
        'student_photo',
        'signature',
        'barcode',
        'status',
    ];
}
