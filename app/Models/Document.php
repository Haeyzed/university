<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Illuminate\Support\Carbon;

/**
 * @class Document
 * @brief Model for managing documents.
 *
 * This model represents the 'documents' table, storing general
 * documents that can be attached to various other models polymorphically.
 *
 * @property int $id
 * @property string|null $title
 * @property string|null $attach
 * @property bool $status
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 */
class Document extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'title',
        'attach',
        'status',
    ];

    /**
     * Get the students that own this document.
     *
     * @return MorphToMany
     */
    public function students(): MorphToMany
    {
        return $this->morphedByMany(Student::class, 'docable');
    }

    /**
     * Get the users (staff) that own this document.
     *
     * @return MorphToMany
     */
    public function users(): MorphToMany
    {
        return $this->morphedByMany(User::class, 'docable');
    }
}
