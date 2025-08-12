<?php

namespace App\Models\System;

use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;
use OwenIt\Auditing\Contracts\Auditable;

/**
 * @class Currency
 * @brief Model for managing currencies.
 *
 * @property int $id
 * @property int $country_id
 * @property string $name
 * @property string $code
 * @property int $precision
 * @property string $symbol
 * @property string $symbol_native
 * @property bool $symbol_first
 * @property string $decimal_mark
 * @property string $thousands_separator
 * @property int|null $created_by
 * @property int|null $updated_by
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property Carbon|null $deleted_at
 *
 * @property-read Country $country
 */
class Currency extends \Nnjeim\World\Models\Currency
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'currencies';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'country_id',
        'name',
        'code',
        'precision',
        'symbol',
        'symbol_native',
        'symbol_first',
        'decimal_mark',
        'thousands_separator',
        'created_by',
        'updated_by',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'precision' => 'integer',
        'symbol_first' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    /**
     * Get the country that the currency belongs to.
     *
     * @return BelongsTo
     */
    public function country(): BelongsTo
    {
        return $this->belongsTo(Country::class);
    }
}
