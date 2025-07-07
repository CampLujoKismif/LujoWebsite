<?php

namespace App\Http\Controllers;

use App\Models\Camp;
use App\Models\CampInstance;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CampInstanceController extends Controller
{
    /**
     * Show the form for creating a new camp instance.
     */
    public function create(Camp $camp)
    {
        // Check if user has access to this camp
        if (!auth()->user()->canAccessCampData($camp->id)) {
            abort(403, 'You do not have permission to access this camp.');
        }

        return view('camps.instances.create', compact('camp'));
    }

    /**
     * Store a newly created camp instance.
     */
    public function store(Request $request, Camp $camp)
    {
        // Check if user has access to this camp
        if (!auth()->user()->canAccessCampData($camp->id)) {
            abort(403, 'You do not have permission to access this camp.');
        }

        $validated = $request->validate([
            'year' => 'required|integer|min:2020|max:2030',
            'name' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'theme_description' => 'nullable|string',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'is_active' => 'boolean',
            'max_capacity' => 'nullable|integer|min:1',
            'price' => 'nullable|numeric|min:0',
            'age_from' => 'nullable|integer|min:0|max:21',
            'age_to' => 'nullable|integer|min:0|max:21|gte:age_from',
            'grade_from' => 'nullable|integer|min:0|max:21',
            'grade_to' => 'nullable|integer|min:0|max:21|gte:grade_from',
            'registration_open_date' => 'nullable|date',
            'registration_close_date' => 'nullable|date|after:registration_open_date',
        ]);

        // Check if instance already exists for this year
        if ($camp->instances()->where('year', $validated['year'])->exists()) {
            return redirect()->back()
                ->withInput()
                ->withErrors(['year' => 'A camp instance already exists for this year.']);
        }

        $validated['camp_id'] = $camp->id;
        $validated['is_active'] = $request->has('is_active');

        // Set default name if not provided
        if (empty($validated['name'])) {
            $validated['name'] = $camp->display_name . ' ' . $validated['year'];
        }

        CampInstance::create($validated);

        return redirect()->route('camps.settings', $camp)
            ->with('success', 'Camp instance created successfully.');
    }

    /**
     * Show the form for editing the specified camp instance.
     */
    public function edit(Camp $camp, CampInstance $instance)
    {
        // Check if user has access to this camp
        if (!auth()->user()->canAccessCampData($camp->id)) {
            abort(403, 'You do not have permission to access this camp.');
        }

        // Verify the instance belongs to this camp
        if ($instance->camp_id !== $camp->id) {
            abort(404, 'Camp instance not found.');
        }

        return view('camps.instances.edit', compact('camp', 'instance'));
    }

    /**
     * Update the specified camp instance.
     */
    public function update(Request $request, Camp $camp, CampInstance $instance)
    {
        // Check if user has access to this camp
        if (!auth()->user()->canAccessCampData($camp->id)) {
            abort(403, 'You do not have permission to access this camp.');
        }

        // Verify the instance belongs to this camp
        if ($instance->camp_id !== $camp->id) {
            abort(404, 'Camp instance not found.');
        }

        $validated = $request->validate([
            'year' => 'required|integer|min:2020|max:2030',
            'name' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'theme_description' => 'nullable|string',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'is_active' => 'boolean',
            'max_capacity' => 'nullable|integer|min:1',
            'price' => 'nullable|numeric|min:0',
            'age_from' => 'nullable|integer|min:0|max:21',
            'age_to' => 'nullable|integer|min:0|max:21|gte:age_from',
            'grade_from' => 'nullable|integer|min:0|max:21',
            'grade_to' => 'nullable|integer|min:0|max:21|gte:grade_from',
            'registration_open_date' => 'nullable|date',
            'registration_close_date' => 'nullable|date|after:registration_open_date',
        ]);

        // Check if another instance already exists for this year (excluding current instance)
        if ($camp->instances()->where('year', $validated['year'])->where('id', '!=', $instance->id)->exists()) {
            return redirect()->back()
                ->withInput()
                ->withErrors(['year' => 'A camp instance already exists for this year.']);
        }

        $validated['is_active'] = $request->has('is_active');

        // Set default name if not provided
        if (empty($validated['name'])) {
            $validated['name'] = $camp->display_name . ' ' . $validated['year'];
        }

        $instance->update($validated);

        return redirect()->route('camps.settings', $camp)
            ->with('success', 'Camp instance updated successfully.');
    }

    /**
     * Remove the specified camp instance.
     */
    public function destroy(Camp $camp, CampInstance $instance)
    {
        // Check if user has access to this camp
        if (!auth()->user()->canAccessCampData($camp->id)) {
            abort(403, 'You do not have permission to access this camp.');
        }

        // Verify the instance belongs to this camp
        if ($instance->camp_id !== $camp->id) {
            abort(404, 'Camp instance not found.');
        }

        if ($instance->assignments()->count() > 0) {
            return redirect()->route('camps.settings', $camp)
                ->with('error', 'Cannot delete camp instance with staff assignments.');
        }

        $instance->delete();

        return redirect()->route('camps.settings', $camp)
            ->with('success', 'Camp instance deleted successfully.');
    }

    /**
     * Show the specified camp instance.
     */
    public function show(Camp $camp, CampInstance $instance)
    {
        // Check if user has access to this camp
        if (!auth()->user()->canAccessCampData($camp->id)) {
            abort(403, 'You do not have permission to access this camp.');
        }

        // Verify the instance belongs to this camp
        if ($instance->camp_id !== $camp->id) {
            abort(404, 'Camp instance not found.');
        }

        $instance->load(['assignedUsers.roles', 'campers', 'registrations']);

        return view('camps.instances.show', compact('camp', 'instance'));
    }
}
