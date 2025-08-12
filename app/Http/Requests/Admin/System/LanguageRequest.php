<?php

namespace App\Http\Requests\Admin\System;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

/**
 * @class LanguageRequest
 * @brief Request for managing languages.
 *
 * @property string $code The ISO 639-1 code of the language.
 * @property string $name The English name of the language.
 * @property string $name_native The native name of the language.
 * @property string $dir The text direction (ltr or rtl).
 */
class LanguageRequest extends FormRequest
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
        $languageId = $this->route('language') ? $this->route('language') : null;

        return [
            /**
             * The ISO 639-1 code of the language.
             * @var string $code
             * @example "en"
             */
            'code' => [
                'required',
                'string',
                'max:2',
                Rule::unique('languages', 'code')->ignore($languageId),
            ],

            /**
             * The English name of the language.
             * @var string $name
             * @example "English"
             */
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('languages', 'name')->ignore($languageId),
            ],

            /**
             * The native name of the language.
             * @var string $name_native
             * @example "English"
             */
            'name_native' => 'required|string|max:255',

            /**
             * The text direction (ltr or rtl).
             * @var string $dir
             * @example "ltr"
             */
            'dir' => 'required|string|in:ltr,rtl',
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
            'code.required' => 'The language code is required.',
            'code.unique' => 'This language code is already taken.',
            'name.required' => 'The language name is required.',
            'name.unique' => 'This language name is already taken.',
            'name_native.required' => 'The native language name is required.',
            'dir.required' => 'The text direction is required.',
            'dir.in' => 'The text direction must be either ltr or rtl.',
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
            'code' => 'language code',
            'name' => 'language name',
            'name_native' => 'native name',
            'dir' => 'direction',
        ];
    }
}
