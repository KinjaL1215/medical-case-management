<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreMedicalCaseRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->role === 'Admin';
    }

    public function rules(): array
    {
        return [
            'case_number' => ['required', 'string', 'max:255', 'unique:cases,case_number'],
            'patient_reference' => ['required', 'string', 'max:255'],
            'surgeon_name' => ['required', 'string', 'max:255'],
            'implant_type' => ['required', 'string', 'max:255'],
            'status' => ['required', 'in:Draft,Planning,In Review,Approved,Completed,Cancelled'],
            'priority' => ['required', 'in:Low,Medium,High'],
            'surgery_date' => ['nullable', 'date'],
        ];
    }
}
