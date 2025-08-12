<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

/**
 * @class PrintSetting
 * @brief Model for managing general print settings.
 *
 * This model represents the 'print_settings' table, storing
 * configurations for various printable documents in the system.
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
 * @property string $width
 * @property string $height
 * @property string|null $prefix
 * @property bool $student_photo
 * @property bool $barcode
 * @property bool $status
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 */
class PrintSetting extends Model
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
        'width',
        'height',
        'prefix',
        'student_photo',
        'barcode',
        'status',
    ];
}
