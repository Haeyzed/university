<?php

namespace App\Http\Requests\Admin\System;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

/**
 * @class CurrencyRequest
 * @brief Request for managing currencies.
 *
 * @property int $country_id The ID of the country the currency is primarily used in.
 * @property string $name The name of the currency.
 * @property string $code The ISO 4217 code of the currency.
 * @property int $precision The number of decimal places.
 * @property string $symbol The symbol of the currency.
 * @property string $symbol_native The native symbol of the currency.
 * @property bool $symbol_first Whether the symbol appears before the amount.
 * @property string $decimal_mark The character used for the decimal point.
 * @property string $thousands_separator The character used for thousands separator.
 */
class CurrencyRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        $currencyId = $this->route('currency') ? $this->route('currency') : null;

        return [
            /**
             * The ID of the country the currency is primarily used in.
             * @var int $country_id
             * @example 1
             */
            'country_id' => 'required|integer|exists:countries,id',

            /**
             * The name of the currency.
             * @var string $name
             * @example "United States Dollar"
             */
            'name' => 'required|string|max:255',

            /**
             * The ISO 4217 code of the currency.
             * @var string $code
             * @example "USD"
             */
            'code' => [
                'required',
                'string',
                'max:3',
                Rule::unique('currencies', 'code')->ignore($currencyId),
            ],

            /**
             * The number of decimal places.
             * @var int $precision
             * @example 2
             */
            'precision' => 'required|integer|min:0|max:4',

            /**
             * The symbol of the currency.
             * @var string $symbol
             * @example "$"
             */
            'symbol' => 'required|string|max:10',

            /**
             * The native symbol of the currency.
             * @var string $symbol_native
             * @example "$"
             */
            'symbol_native' => 'required|string|max:10',

            /**
             * Whether the symbol appears before the amount.
             * @var bool $symbol_first
             * @example true
             */
            'symbol_first' => 'required|boolean',

            /**
             * The character used for the decimal point.
             * @var string $decimal_mark
             * @example "."
             */
            'decimal_mark' => 'required|string|max:1',

            /**
             * The character used for thousands separator.
             * @var string $thousands_separator
             * @example ","
             */
            'thousands_separator' => 'required|string|max:1',
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'country_id.required' => 'The country is required.',
            'country_id.exists' => 'The selected country does not exist.',
            'name.required' => 'The currency name is required.',
            'code.required' => 'The currency code is required.',
            'code.unique' => 'This currency code is already taken.',
            'precision.required' => 'The precision is required.',
            'precision.min' => 'The precision must be at least 0.',
            'symbol.required' => 'The symbol is required.',
            'symbol_native.required' => 'The native symbol is required.',
            'symbol_first.required' => 'Symbol first is required.',
            'decimal_mark.required' => 'The decimal mark is required.',
            'thousands_separator.required' => 'The thousands separator is required.',
        ];
    }

    /**
     * Get custom attributes for validator errors.
     *
     * @return array<string, string>
     */
    public function attributes(): array
    {
        return [
            'country_id' => 'country',
            'name' => 'currency name',
            'code' => 'currency code',
            'precision' => 'precision',
            'symbol' => 'symbol',
            'symbol_native' => 'native symbol',
            'symbol_first' => 'symbol first',
            'decimal_mark' => 'decimal mark',
            'thousands_separator' => 'thousands separator',
        ];
    }
}
