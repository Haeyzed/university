<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

/**
 * @class UserProgram
 * @brief Model for managing user-program associations.
 *
 * This model represents the 'user_program' pivot table, linking
 * users (e.g., teachers) to specific academic programs.
 *
 * @property int $user_id
 * @property int $program_id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 */
class UserProgram extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'user_program';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'program_id',
    ];

    /**
     * Get the user associated with the pivot record.
     *
     * @return BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the program associated with the pivot record.
     *
     * @return BelongsTo
     */
    public function program(): BelongsTo
    {
        return $this->belongsTo(Program::class);
    }
}
