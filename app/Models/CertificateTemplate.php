<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;

/**
 * @class CertificateTemplate
 * @brief Model for managing certificate templates.
 *
 * This model represents the 'certificate_templates' table, storing
 * designs and layouts for various certificates.
 *
 * @property int $id
 * @property string $title
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
 * @property bool $student_photo
 * @property bool $barcode
 * @property bool $status
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 */
class CertificateTemplate extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
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
        'student_photo',
        'barcode',
        'status',
    ];

    /**
     * Get the certificates generated from this template.
     *
     * @return HasMany
     */
    public function certificates(): HasMany
    {
        return $this->hasMany(Certificate::class, 'template_id');
    }
}
