<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

/**
 * @class ProgramSemesterSection
 * @brief Model for managing program-semester-section relationships.
 *
 * This model represents the 'program_semester_sections' table,
 * defining valid combinations of programs, semesters, and sections.
 *
 * @property int $id
 * @property int $program_id
 * @property int $semester_id
 * @property int $section_id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 */
class ProgramSemesterSection extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'program_id',
        'semester_id',
        'section_id',
    ];

    /**
     * Get the program.
     *
     * @return BelongsTo
     */
    public function program(): BelongsTo
    {
        return $this->belongsTo(Program::class);
    }

    /**
     * Get the semester.
     *
     * @return BelongsTo
     */
    public function semester(): BelongsTo
    {
        return $this->belongsTo(Semester::class);
    }

    /**
     * Get the section.
     *
     * @return BelongsTo
     */
    public function section(): BelongsTo
    {
        return $this->belongsTo(Section::class);
    }
}
