<?php

namespace App\Http\Requests;

use App\Rules\AtLeastOnePermissionRule;
use Illuminate\Foundation\Http\FormRequest;

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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'required|unique:roles,name,' . $this->id,
            'level' => 'required|unique:roles,level,' . $this->id,
            'permissions' => 'required|array|min:1',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Please enter a name.',
            'name.unique' => 'This role already exists.',
            'level.required' => 'Please enter a level.',
            'level.unique' => 'The level is associated with another role. Please choose a different level.',
            'permissions.required' => 'At least one permission is required for each role.',
        ];
    }
}
