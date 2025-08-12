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
 * @class Course
 * @brief Model for managing website courses.
 *
 * This model represents the 'courses' table (for web display),
 * storing information about courses offered, potentially linking
 * to internal academic programs.
 *
 * @property int $id
 * @property string $title
 * @property string $slug
 * @property string|null $faculty
 * @property string|null $semesters
 * @property string|null $credits
 * @property string|null $courses
 * @property string|null $duration
 * @property float|null $fee
 * @property string|null $description
 * @property string|null $attach
 * @property bool $status
 * @property int|null $created_by
 * @property int|null $updated_by
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property Carbon|null $deleted_at
 */
class Course extends Model implements Auditable
{
    use HasFactory, SoftDeletes, \OwenIt\Auditing\Auditable;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'courses';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'title',
        'slug',
        'faculty',
        'semesters',
        'credits',
        'courses',
        'duration',
        'fee',
        'description',
        'attach',
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
        'fee' => 'float',
        'status' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    /**
     * Get the user who created the course.
     *
     * @return BelongsTo
     */
    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Get the user who last updated the course.
     *
     * @return BelongsTo
     */
    public function updatedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    /**
     * Get the full URL for the attached image based on the storage disk.
     *
     * @return string|null
     */
    public function getImageUrlAttribute(): ?string
    {
        if (!$this->attach) {
            return null;
        }

        $disk = config('filesystems.default');
        $filePath = 'course/' . $this->attach;

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
     * Get the image URL with a specific disk override.
     *
     * @param string|null $disk The disk to use for URL generation
     * @return string|null
     */
    public function getImageUrl(?string $disk = null): ?string
    {
        if (!$this->attach) {
            return null;
        }

        $disk = $disk ?? config('filesystems.default');
        $filePath = 'course/' . $this->attach;

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
     * Scope a query to only include active courses.
     *
     * @param Builder $query
     * @return Builder
     */
    public function scopeActive(Builder $query): Builder
    {
        return $query->where('status', true);
    }
}
