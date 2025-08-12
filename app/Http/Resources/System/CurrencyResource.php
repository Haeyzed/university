<?php

namespace App\Http\Resources\System;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Carbon;

/**
 * Class CurrencyResource
 *
 * @property int $id The unique identifier for the currency.
 * @property int $country_id The ID of the country the currency is primarily used in.
 * @property string $name The name of the currency.
 * @property string $code The ISO 4217 code of the currency.
 * @property int $precision The number of decimal places.
 * @property string $symbol The symbol of the currency.
 * @property string $symbol_native The native symbol of the currency.
 * @property bool $symbol_first Whether the symbol appears before the amount.
 * @property string $decimal_mark The character used for the decimal point.
 * @property string $thousands_separator The character used for thousands separator.
 * @property int|null $created_by The ID of the user who created the record.
 * @property string|null $created_by_name The name of the user who created the record.
 * @property int|null $updated_by The ID of the user who last updated the record.
 * @property string|null $updated_by_name The name of the user who last updated the record.
 * @property string $created_at The timestamp when the record was created.
 * @property string $updated_at The timestamp when the record was last updated.
 * @property string $deleted_at The timestamp when the record was last deleted.
 *
 * @property-read CountryResource $country
 */
class CurrencyResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            /**
             * The unique identifier for the currency.
             * @var int $id
             * @example 1
             */
            'id' => $this->id,

            /**
             * The ID of the country the currency is primarily used in.
             * @var int $country_id
             * @example 1
             */
            'country_id' => $this->country_id,

            /**
             * The name of the currency.
             * @var string $name
             * @example "United States Dollar"
             */
            'name' => $this->name,

            /**
             * The ISO 4217 code of the currency.
             * @var string $code
             * @example "USD"
             */
            'code' => $this->code,

            /**
             * The number of decimal places.
             * @var int $precision
             * @example 2
             */
            'precision' => $this->precision,

            /**
             * The symbol of the currency.
             * @var string $symbol
             * @example "$"
             */
            'symbol' => $this->symbol,

            /**
             * The native symbol of the currency.
             * @var string $symbol_native
             * @example "$"
             */
            'symbol_native' => $this->symbol_native,

            /**
             * Whether the symbol appears before the amount.
             * @var bool $symbol_first
             * @example true
             */
            'symbol_first' => (bool)$this->symbol_first,

            /**
             * The character used for the decimal point.
             * @var string $decimal_mark
             * @example "."
             */
            'decimal_mark' => $this->decimal_mark,

            /**
             * The character used for thousands separator.
             * @var string $thousands_separator
             * @example ","
             */
            'thousands_separator' => $this->thousands_separator,

            /**
             * The associated country resource.
             * @var CountryResource $country
             */
            'country' => CountryResource::make($this->whenLoaded('country')),

            /**
             * The ID of the user who created the record.
             * @var int|null $created_by
             * @example 1
             */
            'created_by' => $this->created_by,

            /**
             * The name of the user who created the record.
             * @var string|null $created_by_name
             * @example "Admin User"
             */
            'created_by_name' => $this->whenLoaded('createdBy', fn() => $this->createdBy->name),

            /**
             * The ID of the user who last updated the record.
             * @var int|null $updated_by
             * @example 1
             */
            'updated_by' => $this->updated_by,

            /**
             * The name of the user who last updated the record.
             * @var string|null $updated_by_name
             * @example "Admin User"
             */
            'updated_by_name' => $this->whenLoaded('updatedBy', fn() => $this->updatedBy->name),

            /**
             * The timestamp when the record was created.
             * @var string $created_at
             * @example "2024-07-19 12:00:00"
             */
            'created_at' => Carbon::parse($this->created_at)->format('Y-m-d H:i:s'),

            /**
             * The timestamp when the record was last updated.
             * @var string $updated_at
             * @example "2024-07-19 12:30:00"
             */
            'updated_at' => Carbon::parse($this->updated_at)->format('Y-m-d H:i:s'),

            /**
             * The timestamp when the record was last deleted.
             * @var string|null $deleted_at
             * @example "2024-07-19 12:30:00"
             */
            'deleted_at' => $this->deleted_at ? Carbon::parse($this->deleted_at)->format('Y-m-d H:i:s') : null,
        ];
    }
}
