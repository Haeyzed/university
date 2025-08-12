<?php

namespace App\Models\Web;

use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Storage;
use OwenIt\Auditing\Contracts\Auditable;

/**
 * @class AboutUs
 * @brief Model for managing 'About Us' content on the website.
 *
 * This model represents the 'about_us' table, storing information
 * about the institution's history, mission, and values.
 *
 * @property int $id
 * @property string|null $label
 * @property string|null $title
 * @property string|null $short_desc
 * @property string|null $description
 * @property array|null $features
 * @property string|null $attach
 * @property string|null $video_id
 * @property string|null $button_text
 * @property string|null $mission_title
 * @property string|null $mission_desc
 * @property string|null $mission_icon
 * @property string|null $mission_image
 * @property string|null $vision_title
 * @property string|null $vision_desc
 * @property string|null $vision_icon
 * @property string|null $vision_image
 * @property bool $status
 * @property int|null $created_by
 * @property int|null $updated_by
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property Carbon|null $deleted_at
 */
class AboutUs extends Model implements Auditable
{
    use HasFactory, SoftDeletes, \OwenIt\Auditing\Auditable;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'about_us';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'label',
        'title',
        'short_desc',
        'description',
        'features',
        'attach',
        'video_id',
        'button_text',
        'mission_title',
        'mission_desc',
        'mission_icon',
        'mission_image',
        'vision_title',
        'vision_desc',
        'vision_icon',
        'vision_image',
        'status',
        'created_by',
        'updated_by',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'features' => 'array',
            'status' => 'boolean',
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
        ];
    }

    /**
     * Get the user who created the about us content.
     *
     * @return BelongsTo
     */
    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Get the user who last updated the about us content.
     *
     * @return BelongsTo
     */
    public function updatedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    /**
     * Get the full URL for the main attached image based on the storage disk.
     *
     * @return string|null
     */
    public function getAttachImageUrlAttribute(): ?string
    {
        if (!$this->attach) {
            return null;
        }

        $disk = config('filesystems.default');
        $filePath = 'about-us/' . $this->attach;

        // Handle different storage disks
        switch ($disk) {
            case 'public':
                return asset('storage/' . $filePath);
            case 's3':
                return Storage::disk('s3')->url($filePath);
            case 'local':
                return Storage::disk('local')->url($filePath);
            default:
                // For any other disk, try to get the URL
                try {
                    return Storage::disk($disk)->url($filePath);
                } catch (\Exception $e) {
                    // Fallback to storage URL if disk doesn't support URL generation
                    return asset('storage/' . $filePath);
                }
        }
    }

    /**
     * Get the full URL for the mission image based on the storage disk.
     *
     * @return string|null
     */
    public function getMissionImageUrlAttribute(): ?string
    {
        if (!$this->mission_image) {
            return null;
        }

        $disk = config('filesystems.default');
        $filePath = 'about-us/mission/' . $this->mission_image;

        // Handle different storage disks
        switch ($disk) {
            case 'public':
                return asset('storage/' . $filePath);
            case 's3':
                return Storage::disk('s3')->url($filePath);
            case 'local':
                return Storage::disk('local')->url($filePath);
            default:
                // For any other disk, try to get the URL
                try {
                    return Storage::disk($disk)->url($filePath);
                } catch (\Exception $e) {
                    // Fallback to storage URL if disk doesn't support URL generation
                    return asset('storage/' . $filePath);
                }
        }
    }

    /**
     * Get the full URL for the vision image based on the storage disk.
     *
     * @return string|null
     */
    public function getVisionImageUrlAttribute(): ?string
    {
        if (!$this->vision_image) {
            return null;
        }

        $disk = config('filesystems.default');
        $filePath = 'about-us/vision/' . $this->vision_image;

        // Handle different storage disks
        switch ($disk) {
            case 'public':
                return asset('storage/' . $filePath);
            case 's3':
                return Storage::disk('s3')->url($filePath);
            case 'local':
                return Storage::disk('local')->url($filePath);
            default:
                // For any other disk, try to get the URL
                try {
                    return Storage::disk($disk)->url($filePath);
                } catch (\Exception $e) {
                    // Fallback to storage URL if disk doesn't support URL generation
                    return asset('storage/' . $filePath);
                }
        }
    }

    /**
     * Get the main image URL with a specific disk override.
     *
     * @param string|null $disk The disk to use for URL generation
     * @return string|null
     */
    public function getAttachImageUrl(?string $disk = null): ?string
    {
        if (!$this->attach) {
            return null;
        }

        $disk = $disk ?? config('filesystems.default');
        $filePath = 'about-us/' . $this->attach;

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
     * Get the mission image URL with a specific disk override.
     *
     * @param string|null $disk The disk to use for URL generation
     * @return string|null
     */
    public function getMissionImageUrl(?string $disk = null): ?string
    {
        if (!$this->mission_image) {
            return null;
        }

        $disk = $disk ?? config('filesystems.default');
        $filePath = 'about-us/mission/' . $this->mission_image;

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
     * Get the vision image URL with a specific disk override.
     *
     * @param string|null $disk The disk to use for URL generation
     * @return string|null
     */
    public function getVisionImageUrl(?string $disk = null): ?string
    {
        if (!$this->vision_image) {
            return null;
        }

        $disk = $disk ?? config('filesystems.default');
        $filePath = 'about-us/vision/' . $this->vision_image;

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
     * Get the YouTube embed URL from video ID.
     *
     * @return string|null
     */
    public function getYoutubeEmbedUrlAttribute(): ?string
    {
        if (!$this->video_id) {
            return null;
        }

        return "https://www.youtube.com/embed/{$this->video_id}";
    }

    /**
     * Get the YouTube thumbnail URL from video ID.
     *
     * @param string $quality The thumbnail quality (default, mqdefault, hqdefault, sddefault, maxresdefault)
     * @return string|null
     */
    public function getYoutubeThumbnailUrl(string $quality = 'hqdefault'): ?string
    {
        if (!$this->video_id) {
            return null;
        }

        return "https://img.youtube.com/vi/{$this->video_id}/{$quality}.jpg";
    }

    /**
     * Scope a query to only include active about us content.
     *
     * @param Builder $query
     * @return Builder
     */
    public function scopeActive(Builder $query): Builder
    {
        return $query->where('status', true);
    }

    /**
     * Scope a query to only include content with features.
     *
     * @param Builder $query
     * @return Builder
     */
    public function scopeWithFeatures(Builder $query): Builder
    {
        return $query->whereNotNull('features')
            ->where('features', '!=', '[]')
            ->where('features', '!=', 'null');
    }

    /**
     * Scope a query to only include content with mission section.
     *
     * @param Builder $query
     * @return Builder
     */
    public function scopeWithMission(Builder $query): Builder
    {
        return $query->whereNotNull('mission_title')
            ->where('mission_title', '!=', '');
    }

    /**
     * Scope a query to only include content with vision section.
     *
     * @param Builder $query
     * @return Builder
     */
    public function scopeWithVision(Builder $query): Builder
    {
        return $query->whereNotNull('vision_title')
            ->where('vision_title', '!=', '');
    }
}
