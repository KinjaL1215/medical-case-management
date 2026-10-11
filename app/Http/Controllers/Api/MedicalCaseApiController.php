<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreMedicalCaseRequest;
use App\Http\Requests\UpdateMedicalCaseRequest;
use App\Models\MedicalCase;

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
    public function store(StoreMedicalCaseRequest $request)
    {
        $validated = $request->validated();
        $validated['created_by'] = $request->user()->id;

        $medicalCase = MedicalCase::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Medical case created successfully.',
            'data' => $medicalCase,
        ], 201);
    }

    // PUT /api/cases/{id}
    public function update(
        UpdateMedicalCaseRequest $request,
        string $id
    ) {
        $medicalCase = MedicalCase::find($id);

        if (! $medicalCase) {
            return response()->json([
                'success' => false,
                'message' => 'Medical case not found.',
            ], 404);
        }

        $medicalCase->update($request->validated());

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
