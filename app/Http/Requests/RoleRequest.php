<?php

namespace App\Http\Requests;

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
            'name' => 'required|unique:roles,name,'. $this->id,
            'level' => 'required|unique:roles,level,'. $this->id
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Please enter name',
            'name.unique' => 'Already exist this role',
            'level.required' => 'Please enter level',
            'level.unique' => 'The level is associated with another role. Please change level.',
        ];
    }
}
