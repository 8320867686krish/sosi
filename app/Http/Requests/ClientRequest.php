<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ClientRequest extends FormRequest
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
            'manager_name' => 'required|max:254',
            'manager_initials' => 'required|unique:clients,manager_initials,' . $this->id,
            // 'manager_email' => 'email|unique:clients,manager_email,' . $this->id,
            // 'manager_phone' => 'numeric|digits:10',
            // 'manager_contact_person_email' => 'email',
            // 'manager_contact_person_phone' => 'numeric|digits:10',
            // 'manager_logo' => 'image|mimes:jpeg,png,jpg|max:2048',
        ];
    }


    public function messages(): array
    {
        return [
            'manager_name.required' => 'please enter name',
            'manager_initials.required' => 'plese enter manager initials',
            'manager_initials.unique' => 'please enter unique manager initials',
        ];
    }
}
