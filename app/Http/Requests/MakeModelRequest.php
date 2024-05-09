<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class MakeModelRequest extends FormRequest
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
            'hazmat_id' => 'required',
            'equipment' => 'required|max:254',
            'model' => 'required|max:254',
            'make' => 'required|max:254',
            'manufacturer' => 'required|max:254',
            'part' => 'required|max:254',
            'document1' => 'required',

            // `manufacturer`, `part`, `document1`, `document2`
        ];
    }

    public function messages(): array
    {
        return [
            'hazmat_id.required' => 'Please select hazmat',
            'equipment.required' => 'Plese enter equipment',
            'model.required' => 'Plese enter model',
            'make.required' => 'Plese enter make',
            'manufacturer.required' => 'Plese enter manufacturer',
            'part.required' => 'Plese enter part',
            'document1.required' => 'Plese select decument',
        ];
    }
}
