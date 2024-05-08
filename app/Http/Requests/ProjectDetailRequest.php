<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProjectDetailRequest extends FormRequest
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
        $rules  = [];
        if ($this->has('ship_name')) {
            $rules['ship_name'] = 'required';
        }
        if ($this->has('imo_number')) {
            $rules['imo_number'] = 'required';
        }
        // if ($this->has('ship_type')) {
        //     $rules['ship_type'] = 'required';
        // }

        $rules['image'] = 'image|mimes:jpeg,png,jpg|max:2048';
        return $rules;
    }
    public function messages(): array
    {
        return [
            'ship_name.required' => 'please enter ship name',
            'imo_number.required' => 'please enter ship imo number',
            // 'ship_type.required' => 'please enter ship type'
        ];
    }
}
