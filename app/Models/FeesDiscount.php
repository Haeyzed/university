<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Carbon;

/**
 * @class FeesDiscount
 * @brief Model for managing fees discounts.
 *
 * This model represents the 'fees_discounts' table, defining
 * various discounts that can be applied to fees.
 *
 * @property int $id
 * @property string $title
 * @property string $start_date
 * @property string $end_date
 * @property float $amount
 * @property int $type
 * @property bool $status
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 */
class FeesDiscount extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'title',
        'start_date',
        'end_date',
        'amount',
        'type',
        'status',
    ];

    /**
     * Determine if a student is eligible for this discount based on their statuses.
     *
     * @param int $discountId The ID of the discount.
     * @param int $studentId The ID of the student.
     * @return Student|null The student if eligible, null otherwise.
     */
    public static function checkAvailability(int $discountId, int $studentId): ?Student
    {
        $discount = self::query()->where('id', $discountId)
            ->where('status', true)
            ->with('statusTypes')
            ->first();

        if (!$discount) {
            return null;
        }

        foreach ($discount->statusTypes as $statusType) {
            $eligibleStudent = Student::query()->where('id', $studentId)
                ->whereHas('statuses', function ($query) use ($statusType) {
                    $query->where('status_type_id', $statusType->id);
                })
                ->with('statuses')
                ->first();

            if ($eligibleStudent) {
                return $eligibleStudent;
            }
        }

        return null;
    }

    /**
     * The fees categories this discount applies to.
     *
     * @return BelongsToMany
     */
    public function feesCategories(): BelongsToMany
    {
        return $this->belongsToMany(FeesCategory::class, 'fees_category_fees_discount', 'fees_discount_id', 'fees_category_id');
    }

    /**
     * The status types this discount applies to.
     *
     * @return BelongsToMany
     */
    public function statusTypes(): BelongsToMany
    {
        return $this->belongsToMany(StatusType::class, 'fees_discount_status_type', 'fees_discount_id', 'status_type_id');
    }
}
