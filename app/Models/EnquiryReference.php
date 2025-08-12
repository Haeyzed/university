<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;

/**
 * @class EnquiryReference
 * @brief Model for managing enquiry references.
 *
 * This model represents the 'enquiry_references' table, defining
 * how an enquiry was referred.
 *
 * @property int $id
 * @property string $title
 * @property string $slug
 * @property string|null $description
 * @property bool $status
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 */
class EnquiryReference extends Model
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
     * Get the enquiries associated with this reference.
     *
     * @return HasMany
     */
    public function enquiries(): HasMany
    {
        return $this->hasMany(Enquiry::class, 'reference_id');
    }
}
