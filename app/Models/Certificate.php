<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

/**
 * @class Certificate
 * @brief Model for managing generated certificates.
 *
 * This model represents the 'certificates' table, storing details
 * of certificates issued to users, linked to a specific template.
 *
 * @property int $id
 * @property int $template_id
 * @property int $student_id
 * @property string|null $serial_no
 * @property string $date
 * @property string|null $starting_year
 * @property string|null $ending_year
 * @property float $credits
 * @property float $point
 * @property string|null $barcode
 * @property bool $status
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 */
class Certificate extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'template_id',
        'student_id',
        'serial_no',
        'date',
        'starting_year',
        'ending_year',
        'credits',
        'point',
        'barcode',
        'status',
    ];

    /**
     * Get the student to whom the certificate was issued.
     *
     * @return BelongsTo
     */
    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

    /**
     * Get the certificate template used for this certificate.
     *
     * @return BelongsTo
     */
    public function template(): BelongsTo
    {
        return $this->belongsTo(CertificateTemplate::class, 'template_id');
    }
}
