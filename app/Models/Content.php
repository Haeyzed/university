<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Illuminate\Support\Carbon;

/**
 * @class Content
 * @brief Model for managing generic content.
 *
 * This model represents the 'contents' table, storing various
 * types of content that can be associated polymorphically with other models.
 *
 * @property int $id
 * @property int|null $faculty_id
 * @property int|null $program_id
 * @property int|null $session_id
 * @property int|null $semester_id
 * @property int|null $section_id
 * @property int $type_id
 * @property string $title
 * @property string|null $description
 * @property string $date
 * @property string|null $url
 * @property string|null $attach
 * @property bool $status
 * @property int|null $created_by
 * @property int|null $updated_by
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 */
class Content extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'faculty_id',
        'program_id',
        'session_id',
        'semester_id',
        'section_id',
        'type_id',
        'title',
        'description',
        'date',
        'url',
        'attach',
        'status',
        'created_by',
        'updated_by',
    ];

    /**
     * Get the content type.
     *
     * @return BelongsTo
     */
    public function type(): BelongsTo
    {
        return $this->belongsTo(ContentType::class, 'type_id');
    }

    /**
     * Get the faculty associated with the content.
     *
     * @return BelongsTo
     */
    public function faculty(): BelongsTo
    {
        return $this->belongsTo(Faculty::class);
    }

    /**
     * Get the program associated with the content.
     *
     * @return BelongsTo
     */
    public function program(): BelongsTo
    {
        return $this->belongsTo(Program::class);
    }

    /**
     * Get the session associated with the content.
     *
     * @return BelongsTo
     */
    public function session(): BelongsTo
    {
        return $this->belongsTo(Session::class);
    }

    /**
     * Get the semester associated with the content.
     *
     * @return BelongsTo
     */
    public function semester(): BelongsTo
    {
        return $this->belongsTo(Semester::class);
    }

    /**
     * Get the section associated with the content.
     *
     * @return BelongsTo
     */
    public function section(): BelongsTo
    {
        return $this->belongsTo(Section::class);
    }

    /**
     * Get the user who created the content.
     *
     * @return BelongsTo
     */
    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Get the user who last updated the content.
     *
     * @return BelongsTo
     */
    public function updatedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    /**
     * Get the parent contentable model (e.g., Notice, News).
     *
     * @return MorphToMany
     */
    public function users(): MorphToMany
    {
        return $this->morphedByMany(User::class, 'contentable');
    }

    /**
     * Get the parent contentable model (e.g., Notice, News).
     *
     * @return MorphToMany
     */
    public function students(): MorphToMany
    {
        return $this->morphedByMany(Student::class, 'contentable');
    }
}
