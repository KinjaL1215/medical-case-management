<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\MedicalCase;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class MedicalCaseApiController extends Controller
{
    // GET /api/cases
    public function index()
    {
        $cases = MedicalCase::latest()->paginate(10);

        return response()->json([
            'success' => true,
            'message' => 'Medical cases retrieved successfully.',
            'data' => $cases,
        ], 200);
    }

    // GET /api/cases/{id}
    public function show(string $id)
    {
        $medicalCase = MedicalCase::find($id);

        if (! $medicalCase) {
            return response()->json([
                'success' => false,
                'message' => 'Medical case not found.',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'message' => 'Medical case retrieved successfully.',
            'data' => $medicalCase,
        ], 200);
    }

    // POST /api/cases
    public function store(Request $request)
    {
        $validated = $request->validate([
            'case_number' => [
                'required', 'string', 'max:255',
                'unique:cases,case_number',
            ],
            'patient_reference' => ['required', 'string', 'max:255'],
            'surgeon_name' => ['required', 'string', 'max:255'],
            'implant_type' => ['required', 'string', 'max:255'],
            'status' => ['required', Rule::in([
                'Draft', 'Planning', 'In Review',
                'Approved', 'Completed', 'Cancelled',
            ])],
            'priority' => ['required', Rule::in([
                'Low', 'Medium', 'High',
            ])],
            'surgery_date' => ['nullable', 'date'],
        ]);

        $validated['created_by'] = $request->user()->id;

        $medicalCase = MedicalCase::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Medical case created successfully.',
            'data' => $medicalCase,
        ], 201);
    }

    // PUT /api/cases/{id}
    public function update(Request $request, string $id)
    {
        $medicalCase = MedicalCase::find($id);

        if (! $medicalCase) {
            return response()->json([
                'success' => false,
                'message' => 'Medical case not found.',
            ], 404);
        }

        $validated = $request->validate([
            'case_number' => [
                'required', 'string', 'max:255',
                Rule::unique('cases', 'case_number')
                    ->ignore($medicalCase->id),
            ],
            'patient_reference' => ['required', 'string', 'max:255'],
            'surgeon_name' => ['required', 'string', 'max:255'],
            'implant_type' => ['required', 'string', 'max:255'],
            'status' => ['required', Rule::in([
                'Draft', 'Planning', 'In Review',
                'Approved', 'Completed', 'Cancelled',
            ])],
            'priority' => ['required', Rule::in([
                'Low', 'Medium', 'High',
            ])],
            'surgery_date' => ['nullable', 'date'],
        ]);

        $medicalCase->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Medical case updated successfully.',
            'data' => $medicalCase->fresh(),
        ], 200);
    }

    // DELETE /api/cases/{id}
    public function destroy(string $id)
    {
        $medicalCase = MedicalCase::find($id);

        if (! $medicalCase) {
            return response()->json([
                'success' => false,
                'message' => 'Medical case not found.',
            ], 404);
        }

        $medicalCase->delete();

        return response()->json([
            'success' => true,
            'message' => 'Medical case deleted successfully.',
        ], 200);
    }
}
