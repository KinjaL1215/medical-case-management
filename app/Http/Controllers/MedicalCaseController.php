<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreMedicalCaseRequest;
use App\Http\Requests\UpdateMedicalCaseRequest;
use App\Models\MedicalCase;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class MedicalCaseController extends Controller
{
    public function index(Request $request)
    {
        Gate::authorize('viewAny', MedicalCase::class);

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
        Gate::authorize('create', MedicalCase::class);

        return view('cases.create');
    }

    public function store(StoreMedicalCaseRequest $request)
    {
        Gate::authorize('create', MedicalCase::class);

        $validated = $request->validated();
        $validated['created_by'] = $request->user()->id;

        $medicalCase = MedicalCase::create($validated);

        return redirect()
            ->route('cases.show', $medicalCase)
            ->with('success', 'Medical case created successfully.');
    }

    public function show(MedicalCase $medicalCase)
    {
        Gate::authorize('view', $medicalCase);

        return view('cases.show', compact('medicalCase'));
    }

    public function edit(MedicalCase $medicalCase)
    {
        Gate::authorize('update', $medicalCase);

        return view('cases.edit', compact('medicalCase'));
    }

    public function update(
        UpdateMedicalCaseRequest $request,
        MedicalCase $medicalCase
    ) {
        Gate::authorize('update', $medicalCase);

        $medicalCase->update($request->validated());

        return redirect()
            ->route('cases.show', $medicalCase)
            ->with('success', 'Medical case updated successfully.');
    }

    public function destroy(MedicalCase $medicalCase)
    {
        Gate::authorize('delete', $medicalCase);

        $medicalCase->delete();

        return redirect()
            ->route('cases.index')
            ->with('success', 'Medical case deleted successfully.');
    }
}
