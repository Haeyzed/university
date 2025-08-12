<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;

/**
 * @class BookCategory
 * @brief Model for managing book categories.
 *
 * This model represents the 'book_categories' table, used to
 * categorize books in the library.
 *
 * @property int $id
 * @property string $title
 * @property string $slug
 * @property string|null $description
 * @property bool $status
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 */
class BookCategory extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'title',
        'slug',
        'description',
        'status',
    ];

    /**
     * Get the books in this category.
     *
     * @return HasMany
     */
    public function books(): HasMany
    {
        return $this->hasMany(Book::class, 'category_id');
    }

    /**
     * Get the book requests for this category.
     *
     * @return HasMany
     */
    public function bookRequests(): HasMany
    {
        return $this->hasMany(BookRequest::class, 'category_id');
    }
}
