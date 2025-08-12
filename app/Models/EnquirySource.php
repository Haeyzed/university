<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;

/**
 * @class EnquirySource
 * @brief Model for managing enquiry sources.
 *
 * This model represents the 'enquiry_sources' table, defining
 * where an enquiry originated from.
 *
 * @property int $id
 * @property string $title
 * @property string $slug
 * @property string|null $description
 * @property bool $status
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 */
class EnquirySource extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'title',
        'slug',
        'description',
        'status',
    ];

    /**
     * Get the enquiries associated with this source.
     *
     * @return HasMany
     */
    public function enquiries(): HasMany
    {
        return $this->hasMany(Enquiry::class, 'source_id');
    }
}
