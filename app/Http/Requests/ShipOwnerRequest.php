<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ShipOwnerRequest extends FormRequest
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
            'name' => 'required|max:254',
            'email' => 'required|email|unique:ship_owners,email,'.$this->id,
            'phone' => 'required|numeric|digits:10',
            'image' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
            'identification' => 'required|unique:ship_owners,identification,'.$this->id,
        ];
    }
}
