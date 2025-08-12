<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

/**
 * @class TransferCreadit
 * @brief Model for managing transfer credits.
 *
 * This model represents the 'transfer_creadits' table, storing
 * records of academic credits transferred by students from other institutions.
 *
 * @property int $id
 * @property int $student_id
 * @property int $program_id
 * @property int $session_id
 * @property int $semester_id
 * @property int $subject_id
 * @property float|null $marks
 * @property string|null $note
 * @property bool $status
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 */
class TransferCredit extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'student_id',
        'program_id',
        'session_id',
        'semester_id',
        'subject_id',
        'marks',
        'note',
        'status',
    ];

    /**
     * Get the student who transferred the credits.
     *
     * @return BelongsTo
     */
    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

    /**
     * Get the program to which the credits are transferred.
     *
     * @return BelongsTo
     */
    public function program(): BelongsTo
    {
        return $this->belongsTo(Program::class);
    }

    /**
     * Get the session in which the credits are transferred.
     *
     * @return BelongsTo
     */
    public function session(): BelongsTo
    {
        return $this->belongsTo(Session::class);
    }

    /**
     * Get the semester in which the credits are transferred.
     *
     * @return BelongsTo
     */
    public function semester(): BelongsTo
    {
        return $this->belongsTo(Semester::class);
    }

    /**
     * Get the subject for which the credits are transferred.
     *
     * @return BelongsTo
     */
    public function subject(): BelongsTo
    {
        return $this->belongsTo(Subject::class);
    }
}
