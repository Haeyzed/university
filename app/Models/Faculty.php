<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;

/**
 * @class Faculty
 * @brief Model for managing academic faculties.
 *
 * This model represents the 'faculties' table, organizing
 * academic departments and programs.
 *
 * @property int $id
 * @property string $title
 * @property string $slug
 * @property string|null $shortcode
 * @property bool $status
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 */
class Faculty extends Model
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
        'shortcode',
        'status',
    ];

    /**
     * Get the programs belonging to this faculty.
     *
     * @return HasMany
     */
    public function programs(): HasMany
    {
        return $this->hasMany(Program::class);
    }

    /**
     * Get the assignments associated with this faculty.
     *
     * @return HasMany
     */
    public function assignments(): HasMany
    {
        return $this->hasMany(Assignment::class);
    }

    /**
     * Get the contents associated with this faculty.
     *
     * @return HasMany
     */
    public function contents(): HasMany
    {
        return $this->hasMany(Content::class);
    }

    /**
     * Get the email notifications associated with this faculty.
     *
     * @return HasMany
     */
    public function emailNotifies(): HasMany
    {
        return $this->hasMany(EmailNotify::class);
    }

    /**
     * Get the notices associated with this faculty.
     *
     * @return HasMany
     */
    public function notices(): HasMany
    {
        return $this->hasMany(Notice::class);
    }

    /**
     * Get the SMS notifications associated with this faculty.
     *
     * @return HasMany
     */
    public function smsNotifies(): HasMany
    {
        return $this->hasMany(SMSNotify::class);
    }

    /**
     * Get the fees masters associated with this faculty.
     *
     * @return HasMany
     */
    public function feesMasters(): HasMany
    {
        return $this->hasMany(FeesMaster::class);
    }
}
