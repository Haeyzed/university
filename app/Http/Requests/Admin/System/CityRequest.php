<?php

namespace App\Http\Requests\Admin\System;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

/**
 * @class CityRequest
 * @brief Request for managing cities.
 *
 * @property int $country_id The ID of the country the city belongs to.
 * @property int|null $state_id The ID of the state the city belongs to.
 * @property string $name The name of the city.
 */
class CityRequest extends FormRequest
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
        $cityId = $this->route('city') ? $this->route('city') : null;

        return [
            /**
             * The ID of the country the city belongs to.
             * @var int $country_id
             * @example 1
             */
            'country_id' => 'required|integer|exists:countries,id',

            /**
             * The ID of the state the city belongs to.
             * @var int|null $state_id
             * @example 1
             */
            'state_id' => 'nullable|integer|exists:states,id',

            /**
             * The name of the city.
             * @var string $name
             * @example "Los Angeles"
             */
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('cities')->where(fn ($query) => $query->where('country_id', $this->country_id)->where('state_id', $this->state_id))->ignore($cityId),
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
            'state_id.exists' => 'The selected state does not exist.',
            'name.required' => 'The city name is required.',
            'name.unique' => 'A city with this name already exists for the selected country and state.',
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
            'state_id' => 'state',
            'name' => 'city name',
        ];
    }
}
