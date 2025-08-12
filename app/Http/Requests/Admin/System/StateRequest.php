<?php

namespace App\Http\Requests\Admin\System;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

/**
 * @class StateRequest
 * @brief Request for managing states.
 *
 * @property int $country_id The ID of the country the state belongs to.
 * @property string $name The name of the state.
 */
class StateRequest extends FormRequest
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
        $stateId = $this->route('state') ? $this->route('state') : null;

        return [
            /**
             * The ID of the country the state belongs to.
             * @var int $country_id
             * @example 1
             */
            'country_id' => 'required|integer|exists:countries,id',

            /**
             * The name of the state.
             * @var string $name
             * @example "California"
             */
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('states')->where(fn ($query) => $query->where('country_id', $this->country_id))->ignore($stateId),
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
            'name.required' => 'The state name is required.',
            'name.unique' => 'A state with this name already exists for the selected country.',
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
            'name' => 'state name',
        ];
    }
}
