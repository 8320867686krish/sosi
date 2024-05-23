<?php

namespace App\Http\Requests;

use App\Rules\PDFPasswordProtected;
use Illuminate\Foundation\Http\FormRequest;

class AssignProjectRequest extends FormRequest
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
        $rules = [];

        if (isset($this->type) && $this->type == "project") {
            $rules['user_id'] = 'required';
            $rules['assign_date'] = 'required';
            $rules['end_date'] = 'required';
        }

        return $rules;
    }
}
