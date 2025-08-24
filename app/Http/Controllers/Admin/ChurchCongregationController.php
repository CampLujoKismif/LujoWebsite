<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ChurchCongregation;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ChurchCongregationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = ChurchCongregation::query();

        if ($request->filled('search')) {
            $query->search($request->search);
        }

        if ($request->filled('status')) {
            if ($request->status === 'active') {
                $query->active();
            } elseif ($request->status === 'inactive') {
                $query->where('is_active', false);
            }
        }

        $congregations = $query->orderBy('name')->paginate(15);

        return view('admin.church-congregations.index', compact('congregations'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.church-congregations.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:church_congregations',
            'address' => 'nullable|string|max:500',
            'city' => 'nullable|string|max:100',
            'state' => 'nullable|string|max:50',
            'zip_code' => 'nullable|string|max:20',
            'phone' => 'nullable|string|max:20',
            'website' => 'nullable|url|max:255',
            'contact_person' => 'nullable|string|max:255',
            'contact_email' => 'nullable|email|max:255',
            'is_active' => 'boolean',
        ]);

        ChurchCongregation::create($validated);

        return redirect()->route('admin.church-congregations.index')
            ->with('success', 'Church congregation created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(ChurchCongregation $churchCongregation)
    {
        $churchCongregation->load(['families' => function ($query) {
            $query->withCount('campers');
        }]);

        return view('admin.church-congregations.show', compact('churchCongregation'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ChurchCongregation $churchCongregation)
    {
        return view('admin.church-congregations.edit', compact('churchCongregation'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ChurchCongregation $churchCongregation)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255', Rule::unique('church_congregations')->ignore($churchCongregation)],
            'address' => 'nullable|string|max:500',
            'city' => 'nullable|string|max:100',
            'state' => 'nullable|string|max:50',
            'zip_code' => 'nullable|string|max:20',
            'phone' => 'nullable|string|max:20',
            'website' => 'nullable|url|max:255',
            'contact_person' => 'nullable|string|max:255',
            'contact_email' => 'nullable|email|max:255',
            'is_active' => 'boolean',
        ]);

        $churchCongregation->update($validated);

        return redirect()->route('admin.church-congregations.index')
            ->with('success', 'Church congregation updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ChurchCongregation $churchCongregation)
    {
        // Check if there are families associated with this congregation
        if ($churchCongregation->families()->count() > 0) {
            return back()->with('error', 'Cannot delete congregation that has associated families.');
        }

        $churchCongregation->delete();

        return redirect()->route('admin.church-congregations.index')
            ->with('success', 'Church congregation deleted successfully.');
    }

    /**
     * Get congregations for AJAX requests (e.g., dropdowns).
     */
    public function getCongregations(Request $request)
    {
        $query = ChurchCongregation::active()->orderBy('name');

        if ($request->filled('search')) {
            $query->search($request->search);
        }

        $congregations = $query->limit(20)->get(['id', 'name', 'city', 'state']);

        return response()->json($congregations);
    }
}
