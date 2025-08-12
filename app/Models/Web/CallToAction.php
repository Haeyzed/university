<?php

namespace App\Models\Web;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Storage;
use OwenIt\Auditing\Contracts\Auditable;

/**
 * @class CallToAction
 * @brief Model for managing Call To Action elements on the website.
 *
 * This model represents the 'call_to_actions' table, storing data
 * for various calls to action displayed on the website.
 *
 * @property int $id
 * @property string|null $title
 * @property string|null $sub_title
 * @property string|null $image
 * @property string|null $bg_image
 * @property string|null $button_text
 * @property string|null $button_link
 * @property string|null $video_id
 * @property bool $status
 * @property int|null $created_by
 * @property int|null $updated_by
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property Carbon|null $deleted_at
 */
class CallToAction extends Model implements Auditable
{
    use HasFactory, SoftDeletes, \OwenIt\Auditing\Auditable;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'call_to_actions';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'title',
        'sub_title',
        'image',
        'bg_image',
        'button_text',
        'button_link',
        'video_id',
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
            'status' => 'boolean',
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
            'deleted_at' => 'datetime',
        ];
    }

    /**
     * Get the user who created the call to action content.
     *
     * @return BelongsTo
     */
    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Get the user who last updated the call to action content.
     *
     * @return BelongsTo
     */
    public function updatedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    /**
     * Get the full URL for the main image based on the storage disk.
     *
     * @return string|null
     */
    public function getImageUrlAttribute(): ?string
    {
        if (!$this->image) {
            return null;
        }

        $disk = config('filesystems.default');
        $filePath = 'call-to-action/' . $this->image;

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
     * Get the full URL for the background image based on the storage disk.
     *
     * @return string|null
     */
    public function getBgImageUrlAttribute(): ?string
    {
        if (!$this->bg_image) {
            return null;
        }

        $disk = config('filesystems.default');
        $filePath = 'call-to-action/' . $this->bg_image;

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
}
