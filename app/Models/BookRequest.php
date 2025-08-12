<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

/**
 * @class BookRequest
 * @brief Model for managing book requests.
 *
 * This model represents the 'book_requests' table, storing requests
 * made by users for specific books.
 *
 * @property int $id
 * @property int $category_id
 * @property string $title
 * @property string|null $isbn
 * @property string|null $code
 * @property string|null $author
 * @property string|null $publisher
 * @property string|null $edition
 * @property string|null $publish_year
 * @property string|null $language
 * @property float|null $price
 * @property int|null $quantity
 * @property string|null $request_by
 * @property string|null $phone
 * @property string|null $email
 * @property string|null $description
 * @property string|null $note
 * @property string|null $attach
 * @property int $status
 * @property int|null $created_by
 * @property int|null $updated_by
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 */
class BookRequest extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'category_id',
        'title',
        'isbn',
        'code',
        'author',
        'publisher',
        'edition',
        'publish_year',
        'language',
        'price',
        'quantity',
        'request_by',
        'phone',
        'email',
        'description',
        'note',
        'attach',
        'status',
        'created_by',
        'updated_by',
    ];

    /**
     * Get the book category associated with the book request.
     *
     * @return BelongsTo
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(BookCategory::class, 'category_id');
    }

    /**
     * Get the user who created the book request.
     *
     * @return BelongsTo
     */
    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Get the user who last updated the book request.
     *
     * @return BelongsTo
     */
    public function updatedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }
}
