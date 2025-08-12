<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

/**
 * @class Field
 * @brief Model for managing custom fields visibility.
 *
 * This model represents the 'fields' table, controlling the
 * visibility status of various fields across the system.
 *
 * @property int $id
 * @property string $slug
 * @property bool $status
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 */
class Field extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'slug',
        'status',
    ];

    /**
     * Retrieve a field record by its slug.
     *
     * @param string $slug
     * @return Field|null
     */
    public static function field(string $slug): ?self
    {
        return self::query()->where('slug', $slug)->first();
    }
}
