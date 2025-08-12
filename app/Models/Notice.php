<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Illuminate\Support\Carbon;

/**
 * @class Notice
 * @brief Model for managing notices.
 *
 * This model represents the 'notices' table, storing details
 * of announcements and notices for various groups.
 *
 * @property int $id
 * @property int|null $faculty_id
 * @property int|null $program_id
 * @property int|null $session_id
 * @property int|null $semester_id
 * @property int|null $section_id
 * @property int $category_id
 * @property string $notice_no
 * @property string $title
 * @property string|null $description
 * @property string $date
 * @property string|null $attach
 * @property bool $status
 * @property int|null $created_by
 * @property int|null $updated_by
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 */
class Notice extends Model
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
        'category_id',
        'notice_no',
        'title',
        'description',
        'date',
        'attach',
        'status',
        'created_by',
        'updated_by',
    ];

    /**
     * Get the notice category.
     *
     * @return BelongsTo
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(NoticeCategory::class, 'category_id');
    }

    /**
     * Get the faculty associated with the notice.
     *
     * @return BelongsTo
     */
    public function faculty(): BelongsTo
    {
        return $this->belongsTo(Faculty::class);
    }

    /**
     * Get the program associated with the notice.
     *
     * @return BelongsTo
     */
    public function program(): BelongsTo
    {
        return $this->belongsTo(Program::class);
    }

    /**
     * Get the session associated with the notice.
     *
     * @return BelongsTo
     */
    public function session(): BelongsTo
    {
        return $this->belongsTo(Session::class);
    }

    /**
     * Get the semester associated with the notice.
     *
     * @return BelongsTo
     */
    public function semester(): BelongsTo
    {
        return $this->belongsTo(Semester::class);
    }

    /**
     * Get the section associated with the notice.
     *
     * @return BelongsTo
     */
    public function section(): BelongsTo
    {
        return $this->belongsTo(Section::class);
    }

    /**
     * Get the user who created the notice.
     *
     * @return BelongsTo
     */
    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Get the user who last updated the notice.
     *
     * @return BelongsTo
     */
    public function updatedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    /**
     * Get the content associated with the notice.
     *
     * @return MorphOne
     */
    public function content(): MorphOne
    {
        return $this->morphOne(Content::class, 'contentable');
    }

    /**
     * Get the users associated with the notice.
     *
     * @return MorphToMany
     */
    public function users(): MorphToMany
    {
        return $this->morphedByMany(User::class, 'noticeable');
    }

    /**
     * Get the students associated with the notice.
     *
     * @return MorphToMany
     */
    public function students(): MorphToMany
    {
        return $this->morphedByMany(Student::class, 'noticeable');
    }
}
