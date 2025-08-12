<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

/**
 * Class Leave
 *
 * Represents staff leave applications stored in the 'leaves' table.
 *
 * @property int $id
 * @property int $type_id
 * @property int $user_id
 * @property int|null $review_by
 * @property string $apply_date
 * @property string $from_date
 * @property string $to_date
 * @property string|null $reason
 * @property string|null $attach
 * @property string|null $note
 * @property int $pay_type
 * @property int $status
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 */
class Leave extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'type_id',
        'user_id',
        'review_by',
        'apply_date',
        'from_date',
        'to_date',
        'reason',
        'attach',
        'note',
        'pay_type',
        'status',
    ];

    /**
     * Get the number of paid leave days for the specified month/year.
     *
     * @param int $userId
     * @param int $month
     * @param int $year
     * @return int
     */
    public static function paidLeave(int $userId, int $month, int $year): int
    {
        return self::calculateLeave($userId, $month, $year, 1);
    }

    /**
     * Calculate the number of leave days of a specific type within a month/year.
     *
     * @param int $userId
     * @param int $month
     * @param int $year
     * @param int $payType
     * @return int
     */
    private static function calculateLeave(int $userId, int $month, int $year, int $payType): int
    {
        $startOfMonth = date("$year-$month-01");
        $endOfMonth = date("Y-m-t", strtotime($startOfMonth));
        $leaveDays = 0;

        $leaves = self::query()->where('user_id', $userId)
            ->where('status', 1)
            ->where(function ($query) use ($month, $year) {
                $query->whereMonth('from_date', $month)
                    ->whereYear('from_date', $year)
                    ->orWhereMonth('to_date', $month)
                    ->whereYear('to_date', $year);
            })
            ->get();

        foreach ($leaves as $leave) {
            if ($leave->pay_type !== $payType) {
                continue;
            }

            $from = max(strtotime($leave->from_date), strtotime($startOfMonth));
            $to = min(strtotime($leave->to_date), strtotime($endOfMonth));

            if ($to >= $from) {
                $leaveDays += (int)(($to - $from) / 86400) + 1;
            }
        }

        return $leaveDays;
    }

    /**
     * Get the number of unpaid leave days for the specified month/year.
     *
     * @param int $userId
     * @param int $month
     * @param int $year
     * @return int
     */
    public static function unpaidLeave(int $userId, int $month, int $year): int
    {
        return self::calculateLeave($userId, $month, $year, 2);
    }

    /**
     * Get the leave type for the application.
     *
     * @return BelongsTo
     */
    public function leaveType(): BelongsTo
    {
        return $this->belongsTo(LeaveType::class, 'type_id');
    }

    /**
     * Get the staff member who applied for leave.
     *
     * @return BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the staff member who reviewed the leave application.
     *
     * @return BelongsTo
     */
    public function reviewBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'review_by');
    }
}
