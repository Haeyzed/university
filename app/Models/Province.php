<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;

/**
 * @class Province
 * @brief Model for managing provinces/states.
 *
 * This model represents the 'provinces' table, organizing
 * geographical areas at the province/state level.
 *
 * @property int $id
 * @property string $title
 * @property string $slug
 * @property string|null $description
 * @property bool $status
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 */
class Province extends Model
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
     * Get the districts in this province.
     *
     * @return HasMany
     */
    public function districts(): HasMany
    {
        return $this->hasMany(District::class);
    }

    /**
     * Get the applications with present address in this province.
     *
     * @return HasMany
     */
    public function presentApplications(): HasMany
    {
        return $this->hasMany(Application::class, 'present_province');
    }

    /**
     * Get the applications with permanent address in this province.
     *
     * @return HasMany
     */
    public function permanentApplications(): HasMany
    {
        return $this->hasMany(Application::class, 'permanent_province');
    }

    /**
     * Get the outside users with present address in this province.
     *
     * @return HasMany
     */
    public function presentOutsideUsers(): HasMany
    {
        return $this->hasMany(OutsideUser::class, 'present_province');
    }

    /**
     * Get the outside users with permanent address in this province.
     *
     * @return HasMany
     */
    public function permanentOutsideUsers(): HasMany
    {
        return $this->hasMany(OutsideUser::class, 'permanent_province');
    }

    /**
     * Get the students with present address in this province.
     *
     * @return HasMany
     */
    public function presentStudents(): HasMany
    {
        return $this->hasMany(Student::class, 'present_province');
    }

    /**
     * Get the students with permanent address in this province.
     *
     * @return HasMany
     */
    public function permanentStudents(): HasMany
    {
        return $this->hasMany(Student::class, 'permanent_province');
    }

    /**
     * Get the users with present address in this province.
     *
     * @return HasMany
     */
    public function presentUsers(): HasMany
    {
        return $this->hasMany(User::class, 'present_province');
    }

    /**
     * Get the users with permanent address in this province.
     *
     * @return HasMany
     */
    public function permanentUsers(): HasMany
    {
        return $this->hasMany(User::class, 'permanent_province');
    }
}
