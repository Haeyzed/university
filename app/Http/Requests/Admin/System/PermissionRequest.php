<?php

namespace App\Http\Requests\Admin\System;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

/**
 * @class PermissionRequest
 * @brief Request for managing permissions.
 *
 * @property string $name The name of the permission.
 * @property string $group The group the permission belongs to.
 * @property string $title The display title of the permission.
 * @property string $guard_name The guard name for the permission.
 */
class PermissionRequest extends FormRequest
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
        $permissionId = $this->route('permission') ? $this->route('permission') : null;

        return [
            /**
             * The name of the permission.
             * @var string $name
             * @example "view-users"
             */
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('permissions', 'name')->ignore($permissionId),
            ],

            /**
             * The group the permission belongs to.
             * @var string $group
             * @example "User Management"
             */
            'group' => 'required|string|max:255',

            /**
             * The display title of the permission.
             * @var string $title
             * @example "View Users"
             */
            'title' => 'required|string|max:255',

            /**
             * The guard name for the permission.
             * @var string $guard_name
             * @example "web"
             */
            'guard_name' => 'required|string|max:50',
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
            'name.required' => 'The permission name is required.',
            'name.unique' => 'This permission name is already taken.',
            'group.required' => 'The permission group is required.',
            'title.required' => 'The permission title is required.',
            'guard_name.required' => 'The guard name is required.',
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
            'name' => 'permission name',
            'group' => 'permission group',
            'title' => 'permission title',
            'guard_name' => 'guard name',
        ];
    }
}
