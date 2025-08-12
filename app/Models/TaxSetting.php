<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

/**
 * @class TaxSetting
 * @brief Model for managing tax settings.
 *
 * This model represents the 'tax_settings' table, defining
 * tax brackets and percentages for payroll calculations.
 *
 * @property int $id
 * @property float $min_amount
 * @property float $max_amount
 * @property float $percentange
 * @property float $max_no_taxable_amount
 * @property bool $status
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 */
class TaxSetting extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'min_amount',
        'max_amount',
        'percentange',
        'max_no_taxable_amount',
        'status',
    ];
}
