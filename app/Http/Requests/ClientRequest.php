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
            'manager_email' => 'required|email|unique:clients,manager_email,' . $this->id,
            'manager_phone' => 'required|numeric|digits:10',
            'manager_contact_person_name' => 'required|max:254',
            'manager_contact_person_email' => 'required|email',
            'manager_contact_person_phone' => 'required|numeric|digits:10',
            'rpsl' => 'required|max:254',
            'manager_website' => 'required|url',
            'manager_tax_information' => 'required|max:254',
            'manager_address' => 'required',
            'manager_logo' => 'required|image|mimes:jpeg,png,jpg|max:2048',
            'manager_initials' => 'required|unique:clients,manager_initials,' . $this->id,
        ];
    }

    public function messages(): array
    {
        return [
            'manager_name.required' => 'please enter name',
            'manager_email.required' => 'please enter email',
            'manager_email.unique' => 'please enter unique email',
            'manager_phone.required' => 'please enter phone',
            'manager_contact_person_name.required' => 'please enter contact person name',
            'manager_contact_person_email.required' => 'please enter contact person email',
            'manager_contact_person_phone.required' => 'please enter contact person phone',
            'rpsl.required' => 'please enter rpsl',
            'manager_website.required' => 'please enter website',
            'manager_tax_information.required' => 'please enter tax information',
            'manager_address.required' => 'please enter address',
            'manager_logo.required' => 'please select logo',
            'manager_initials.required' => 'plese enter manager initials',
            'manager_initials.unique' => 'please enter unique manager initials'
        ];
    }
}
