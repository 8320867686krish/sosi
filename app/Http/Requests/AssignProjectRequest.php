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
        return [
            //
            'user_id' => 'required',
            'assign_date' => 'required',
            'end_date' => 'required'
            // 'project.leb1LaboratoryResult1' => ['file', new PDFPasswordProtected()]
            // 'project.leb1LaboratoryResult2' => ['file', new PDFPasswordProtected()],
            // 'project.leb2LaboratoryResult1' => ['file', new PDFPasswordProtected()],
            // 'project.leb2LaboratoryResult2' => ['file', new PDFPasswordProtected()],
        ];
    }
}
