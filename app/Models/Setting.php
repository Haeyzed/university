<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Storage;
use OwenIt\Auditing\Contracts\Auditable;

/**
 * @class Setting
 * @brief Model for managing general system settings.
 *
 * This model represents the 'settings' table, storing
 * global configurations for the application.
 *
 * @property int $id
 * @property string $title
 * @property string|null $academy_code
 * @property string|null $meta_title
 * @property string|null $meta_description
 * @property string|null $meta_keywords
 * @property string|null $logo_path
 * @property string|null $favicon_path
 * @property string|null $phone
 * @property string|null $email
 * @property string|null $fax
 * @property string|null $address
 * @property string|null $language
 * @property string|null $date_format
 * @property string|null $time_format
 * @property string|null $week_start
 * @property string|null $time_zone
 * @property string|null $currency
 * @property string|null $currency_symbol
 * @property int $decimal_place
 * @property string|null $copyright_text
 * @property bool $status
 * @property int|null $created_by
 * @property int|null $updated_by
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property Carbon|null $deleted_at
 */
class Setting extends Model implements Auditable
{
    use HasFactory, SoftDeletes, \OwenIt\Auditing\Auditable;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'settings';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'title',
        'academy_code',
        'meta_title',
        'meta_description',
        'meta_keywords',
        'logo_path',
        'favicon_path',
        'phone',
        'email',
        'fax',
        'address',
        'language',
        'date_format',
        'time_format',
        'week_start',
        'time_zone',
        'currency',
        'currency_symbol',
        'decimal_place',
        'copyright_text',
        'status',
        'created_by',
        'updated_by',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'status' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    /**
     * Get the user who created the setting.
     *
     * @return BelongsTo
     */
    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Get the user who last updated the setting.
     *
     * @return BelongsTo
     */
    public function updatedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    /**
     * Get the full URL for the logo image based on the storage disk.
     *
     * @return string|null
     */
    public function getLogoUrlAttribute(): ?string
    {
        if (!$this->logo_path) {
            return null;
        }

        $disk = config('filesystems.default');
        $filePath = 'settings/' . $this->logo_path;

        switch ($disk) {
            case 'public':
                return asset('storage/' . $filePath);
            case 's3':
                return Storage::disk('s3')->url($filePath);
            case 'local':
                return Storage::disk('local')->url($filePath);
            default:
                try {
                    return Storage::disk($disk)->url($filePath);
                } catch (\Exception $e) {
                    return asset('storage/' . $filePath);
                }
        }
    }

    /**
     * Get the full URL for the favicon image based on the storage disk.
     *
     * @return string|null
     */
    public function getFaviconUrlAttribute(): ?string
    {
        if (!$this->favicon_path) {
            return null;
        }

        $disk = config('filesystems.default');
        $filePath = 'settings/' . $this->favicon_path;

        switch ($disk) {
            case 'public':
                return asset('storage/' . $filePath);
            case 's3':
                return Storage::disk('s3')->url($filePath);
            case 'local':
                return Storage::disk('local')->url($filePath);
            default:
                try {
                    return Storage::disk($disk)->url($filePath);
                } catch (\Exception $e) {
                    return asset('storage/' . $filePath);
                }
        }
    }

    /**
     * Get the logo image URL with a specific disk override.
     *
     * @param string|null $disk The disk to use for URL generation
     * @return string|null
     */
    public function getLogoUrl(?string $disk = null): ?string
    {
        if (!$this->logo_path) {
            return null;
        }

        $disk = $disk ?? config('filesystems.default');
        $filePath = 'settings/' . $this->logo_path;

        switch ($disk) {
            case 'public':
                return asset('storage/' . $filePath);
            case 's3':
                return Storage::disk('s3')->url($filePath);
            case 'local':
                return Storage::disk('local')->url($filePath);
            default:
                try {
                    return Storage::disk($disk)->url($filePath);
                } catch (\Exception $e) {
                    return asset('storage/' . $filePath);
                }
        }
    }

    /**
     * Get the favicon image URL with a specific disk override.
     *
     * @param string|null $disk The disk to use for URL generation
     * @return string|null
     */
    public function getFaviconUrl(?string $disk = null): ?string
    {
        if (!$this->favicon_path) {
            return null;
        }

        $disk = $disk ?? config('filesystems.default');
        $filePath = 'settings/' . $this->favicon_path;

        switch ($disk) {
            case 'public':
                return asset('storage/' . $filePath);
            case 's3':
                return Storage::disk('s3')->url($filePath);
            case 'local':
                return Storage::disk('local')->url($filePath);
            default:
                try {
                    return Storage::disk($disk)->url($filePath);
                } catch (\Exception $e) {
                    return asset('storage/' . $filePath);
                }
        }
    }

    /**
     * Scope a query to only include active settings.
     *
     * @param Builder $query
     * @return Builder
     */
    public function scopeActive(Builder $query): Builder
    {
        return $query->where('status', true);
    }
}
