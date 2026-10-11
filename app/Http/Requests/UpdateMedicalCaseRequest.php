<?php

namespace App\Http\Requests;

use App\Models\MedicalCase;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateMedicalCaseRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->role === 'Admin';
    }

    public function rules(): array
    {
        $case = $this->route('medicalCase') ?? $this->route('id');

        $caseId = $case instanceof MedicalCase
            ? $case->id
            : $case;

        return [
            'case_number' => [
                'required',
                'string',
                'max:255',
                Rule::unique('cases', 'case_number')->ignore($caseId),
            ],
            'patient_reference' => [
                'required',
                'string',
                'max:255',
            ],
            'surgeon_name' => [
                'required',
                'string',
                'max:255',
            ],
            'implant_type' => [
                'required',
                'string',
                'max:255',
            ],
            'status' => [
                'required',
                'in:Draft,Planning,In Review,Approved,Completed,Cancelled',
            ],
            'priority' => [
                'required',
                'in:Low,Medium,High',
            ],
            'surgery_date' => [
                'nullable',
                'date',
            ],
        ];
    }
}
