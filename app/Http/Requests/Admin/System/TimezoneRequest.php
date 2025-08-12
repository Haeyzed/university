<?php

namespace App\Http\Requests\Admin\System;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

/**
 * @class TimezoneRequest
 * @brief Request for managing timezones.
 *
 * @property int $country_id The ID of the country the timezone is associated with.
 * @property string $name The name of the timezone (e.g., "America/New_York").
 */
class TimezoneRequest extends FormRequest
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
        $timezoneId = $this->route('timezone') ? $this->route('timezone') : null;

        return [
            /**
             * The ID of the country the timezone is associated with.
             * @var int $country_id
             * @example 1
             */
            'country_id' => 'required|integer|exists:countries,id',

            /**
             * The name of the timezone (e.g., "America/New_York").
             * @var string $name
             * @example "America/New_York"
             */
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('timezones', 'name')->ignore($timezoneId),
            ],
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
            'name.required' => 'The timezone name is required.',
            'name.unique' => 'This timezone name is already taken.',
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
            'name' => 'timezone name',
        ];
    }
}
