<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;

/**
 * @class ComplainSource
 * @brief Model for managing complain sources.
 *
 * This model represents the 'complain_sources' table, defining
 * where a complaint originated from.
 *
 * @property int $id
 * @property string $title
 * @property string $slug
 * @property string|null $description
 * @property bool $status
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 */
class ComplainSource extends Model
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
     * Get the complains associated with this source.
     *
     * @return HasMany
     */
    public function complains(): HasMany
    {
        return $this->hasMany(Complain::class, 'source_id');
    }
}
