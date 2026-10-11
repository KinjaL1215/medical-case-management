<?php

namespace App\Http\Controllers;

use App\Models\MedicalCase;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class MedicalCaseController extends Controller
{
    public function index(Request $request)
    {
        $cases = MedicalCase::query()
            ->when($request->filled('search'), function ($query) use ($request) {
                $search = $request->search;

                $query->where(function ($q) use ($search) {
                    $q->where('case_number', 'like', "%{$search}%")
                      ->orWhere('surgeon_name', 'like', "%{$search}%");
                });
            })
            ->when($request->filled('status'), function ($query) use ($request) {
                $query->where('status', $request->status);
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        $totalCases = MedicalCase::count();
        $planningCases = MedicalCase::where('status', 'Planning')->count();
        $inReviewCases = MedicalCase::where('status', 'In Review')->count();
        $approvedCases = MedicalCase::where('status', 'Approved')->count();
        $completedCases = MedicalCase::where('status', 'Completed')->count();

        return view('cases.index', compact(
            'cases',
            'totalCases',
            'planningCases',
            'inReviewCases',
            'approvedCases',
            'completedCases'
        ));
    }

    public function create()
    {
        return view('cases.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'case_number' => ['required', 'string', 'max:255', 'unique:cases,case_number'],
            'patient_reference' => ['required', 'string', 'max:255'],
            'surgeon_name' => ['required', 'string', 'max:255'],
            'implant_type' => ['required', 'string', 'max:255'],
            'status' => ['required', Rule::in([
                'Draft', 'Planning', 'In Review',
                'Approved', 'Completed', 'Cancelled',
            ])],
            'priority' => ['required', Rule::in(['Low', 'Medium', 'High'])],
            'surgery_date' => ['nullable', 'date'],
        ]);

        $validated['created_by'] = $request->user()->id;
        $medicalCase = MedicalCase::create($validated);

        return redirect()
            ->route('cases.show', $medicalCase)
            ->with('success', 'Medical case created successfully.');
    }

    public function show(MedicalCase $medicalCase)
    {
        return view('cases.show', compact('medicalCase'));
    }

    public function edit(MedicalCase $medicalCase)
    {
        return view('cases.edit', compact('medicalCase'));
    }

    public function update(Request $request, MedicalCase $medicalCase)
    {
        $validated = $request->validate([
            'case_number' => [
                'required', 'string', 'max:255',
                Rule::unique('cases', 'case_number')->ignore($medicalCase->id),
            ],
            'patient_reference' => ['required', 'string', 'max:255'],
            'surgeon_name' => ['required', 'string', 'max:255'],
            'implant_type' => ['required', 'string', 'max:255'],
            'status' => ['required', Rule::in([
                'Draft', 'Planning', 'In Review',
                'Approved', 'Completed', 'Cancelled',
            ])],
            'priority' => ['required', Rule::in(['Low', 'Medium', 'High'])],
            'surgery_date' => ['nullable', 'date'],
        ]);

        $medicalCase->update($validated);

        return redirect()
            ->route('cases.show', $medicalCase)
            ->with('success', 'Medical case updated successfully.');
    }

    public function destroy(MedicalCase $medicalCase)
    {
        $medicalCase->delete();

        return redirect()
            ->route('cases.index')
            ->with('success', 'Medical case deleted successfully.');
    }
}
