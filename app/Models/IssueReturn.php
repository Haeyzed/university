<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

/**
 * @class IssueReturn
 * @brief Model for managing book issue and return records.
 *
 * This model represents the 'issue_returns' table, tracking
 * the borrowing and returning of books by library members.
 *
 * @property int $id
 * @property int $member_id
 * @property int $book_id
 * @property string|null $issue_date
 * @property string|null $due_date
 * @property string|null $return_date
 * @property float|null $penalty
 * @property int $status
 * @property int|null $issued_by
 * @property int|null $received_by
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 */
class IssueReturn extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'member_id',
        'book_id',
        'issue_date',
        'due_date',
        'return_date',
        'penalty',
        'status',
        'issued_by',
        'received_by',
    ];

    /**
     * Get the library member who issued/returned the book.
     *
     * @return BelongsTo
     */
    public function member(): BelongsTo
    {
        return $this->belongsTo(LibraryMember::class, 'member_id');
    }

    /**
     * Get the book that was issued/returned.
     *
     * @return BelongsTo
     */
    public function book(): BelongsTo
    {
        return $this->belongsTo(Book::class);
    }

    /**
     * Get the user who issued the book.
     *
     * @return BelongsTo
     */
    public function issuedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'issued_by');
    }

    /**
     * Get the user who received the returned book.
     *
     * @return BelongsTo
     */
    public function receivedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'received_by');
    }
}
