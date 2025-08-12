<?php

namespace App\Http\Requests\Admin\System;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

/**
 * @class CountryRequest
 * @brief Request for managing countries.
 *
 * @property string $iso2 The ISO2 code of the country.
 * @property string $name The name of the country.
 * @property bool|null $status The status of the country.
 */
class CountryRequest extends FormRequest
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
        $countryId = $this->route('country') ? $this->route('country') : null;

        return [
            /**
             * The ISO2 code of the country.
             * @var string $iso2
             * @example "US"
             */
            'iso2' => [
                'required',
                'string',
                'max:2',
                Rule::unique('countries', 'iso2')->ignore($countryId),
            ],

            /**
             * The name of the country.
             * @var string $name
             * @example "United States"
             */
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('countries', 'name')->ignore($countryId),
            ],

            /**
             * The status of the country.
             * @var bool $status
             * @example true
             */
            'status' => 'sometimes|boolean',
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
            'iso2.required' => 'The ISO2 code is required.',
            'iso2.unique' => 'This ISO2 code is already taken.',
            'name.required' => 'The country name is required.',
            'name.unique' => 'This country name is already taken.',
        ];
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        if (!$this->has('status')) {
            $this->merge([
                'status' => true,
            ]);
        }
    }

    /**
     * Get custom attributes for validator errors.
     *
     * @return array<string, string>
     */
    public function attributes(): array
    {
        return [
            'iso2' => 'ISO2 code',
            'name' => 'country name',
            'status' => 'status',
        ];
    }
}
