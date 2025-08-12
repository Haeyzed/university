<?php

namespace App\Http\Requests\Admin\System;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Support\Str;

/**
 * @class RoleRequest
 * @brief Request for managing roles.
 *
 * @property string $name The name of the role.
 * @property string|null $slug The URL-friendly slug for the role.
 * @property string $guard_name The guard name for the role.
 * @property array|null $permissions An array of permission IDs to assign to the role.
 */
class RoleRequest extends FormRequest
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
        $roleId = $this->route('role') ? $this->route('role') : null;

        return [
            /**
             * The name of the role.
             * @var string $name
             * @example "admin"
             */
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('roles', 'name')->ignore($roleId),
            ],

            /**
             * The URL-friendly slug for the role.
             * @var string|null $slug
             * @example "admin-role"
             */
            'slug' => [
                'sometimes',
                'string',
                'max:255',
                'regex:/^[a-z0-9]+(?:-[a-z0-9]+)*$/',
                Rule::unique('roles', 'slug')->ignore($roleId),
            ],

            /**
             * The guard name for the role.
             * @var string $guard_name
             * @example "web"
             */
            'guard_name' => 'required|string|max:50',

            /**
             * An array of permission IDs to assign to the role.
             * @var array|null $permissions
             * @example [1, 2, 3]
             */
            'permissions' => 'nullable|array',
            'permissions.*' => 'integer|exists:permissions,id',
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
            'name.required' => 'The role name is required.',
            'name.unique' => 'This role name is already taken.',
            'slug.unique' => 'This slug is already taken. Please choose a different one.',
            'slug.regex' => 'The slug format is invalid. Use only lowercase letters, numbers, and hyphens.',
            'guard_name.required' => 'The guard name is required.',
            'permissions.*.integer' => 'Each permission ID must be an integer.',
            'permissions.*.exists' => 'One or more selected permissions do not exist.',
        ];
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        // Generate slug from name if not provided
        if (!$this->has('slug') && $this->has('name')) {
            $this->merge([
                'slug' => Str::slug($this->name, '-'),
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
            'name' => 'role name',
            'slug' => 'role slug',
            'guard_name' => 'guard name',
            'permissions' => 'permissions',
        ];
    }
}
