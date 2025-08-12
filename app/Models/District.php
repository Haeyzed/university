<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;

/**
 * @class District
 * @brief Model for managing districts/cities.
 *
 * This model represents the 'districts' table, organizing
 * geographical areas within provinces.
 *
 * @property int $id
 * @property int $province_id
 * @property string $title
 * @property string|null $description
 * @property bool $status
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 */
class District extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'province_id',
        'title',
        'description',
        'status',
    ];

    /**
     * Get the province that owns the district.
     *
     * @return BelongsTo
     */
    public function province(): BelongsTo
    {
        return $this->belongsTo(Province::class);
    }

    /**
     * Get students whose present address is in this district.
     *
     * @return HasMany
     */
    public function studentPresentDistrict(): HasMany
    {
        return $this->hasMany(Student::class, 'present_district');
    }

    /**
     * Get students whose permanent address is in this district.
     *
     * @return HasMany
     */
    public function studentPermanentDistrict(): HasMany
    {
        return $this->hasMany(Student::class, 'permanent_district');
    }

    /**
     * Get staff whose present address is in this district.
     *
     * @return HasMany
     */
    public function staffPresentDistrict(): HasMany
    {
        return $this->hasMany(User::class, 'present_district');
    }

    /**
     * Get staff whose permanent address is in this district.
     *
     * @return HasMany
     */
    public function staffPermanentDistrict(): HasMany
    {
        return $this->hasMany(User::class, 'permanent_district');
    }
}
