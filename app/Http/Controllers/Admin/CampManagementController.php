<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Camp;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CampManagementController extends Controller
{
    // Middleware is applied at the route level, so no need for constructor middleware

    /**
     * Display a listing of camps.
     */
    public function index()
    {
        $camps = Camp::orderBy('created_at', 'desc')->paginate(10);
        
        return view('admin.camps.index', compact('camps'));
    }

    /**
     * Show the form for creating a new camp.
     */
    public function create()
    {
        return view('admin.camps.create');
    }

    /**
     * Store a newly created camp.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:camps',
            'display_name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'is_active' => 'boolean',
            'price' => 'nullable|numeric|min:0',
        ]);

        $validated['is_active'] = $request->has('is_active');

        Camp::create($validated);

        return redirect()->route('admin.camps.index')
            ->with('success', 'Camp created successfully.');
    }

    /**
     * Display the specified camp.
     */
    public function show(Camp $camp)
    {
        $camp->load(['staff', 'campers', 'registrations']);
        
        return view('admin.camps.show', compact('camp'));
    }

    /**
     * Show the form for editing the specified camp.
     */
    public function edit(Camp $camp)
    {
        return view('admin.camps.edit', compact('camp'));
    }

    /**
     * Update the specified camp.
     */
    public function update(Request $request, Camp $camp)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:camps,name,' . $camp->id,
            'display_name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'is_active' => 'boolean',
            'price' => 'nullable|numeric|min:0',
        ]);

        $validated['is_active'] = $request->has('is_active');

        $camp->update($validated);

        return redirect()->route('admin.camps.index')
            ->with('success', 'Camp updated successfully.');
    }

    /**
     * Remove the specified camp.
     */
    public function destroy(Camp $camp)
    {
        // Check if camp has any assignments or registrations
        if ($camp->assignments()->count() > 0) {
            return redirect()->route('admin.camps.index')
                ->with('error', 'Cannot delete camp with assigned staff.');
        }

        if ($camp->registrations()->count() > 0) {
            return redirect()->route('admin.camps.index')
                ->with('error', 'Cannot delete camp with registrations.');
        }

        $camp->delete();

        return redirect()->route('admin.camps.index')
            ->with('success', 'Camp deleted successfully.');
    }

    /**
     * Display a listing of soft deleted camps.
     */
    public function trashed()
    {
        $camps = Camp::onlyTrashed()->orderBy('deleted_at', 'desc')->paginate(10);
        
        return view('admin.camps.trashed', compact('camps'));
    }

    /**
     * Restore a soft deleted camp.
     */
    public function restore($id)
    {
        $camp = Camp::onlyTrashed()->findOrFail($id);
        $camp->restore();

        return redirect()->route('admin.camps.trashed')
            ->with('success', 'Camp restored successfully.');
    }

    /**
     * Permanently delete a camp.
     */
    public function forceDelete($id)
    {
        $camp = Camp::onlyTrashed()->findOrFail($id);
        
        // Check if camp has any assignments or registrations
        if ($camp->assignments()->withTrashed()->count() > 0) {
            return redirect()->route('admin.camps.trashed')
                ->with('error', 'Cannot permanently delete camp with assigned staff.');
        }

        if ($camp->registrations()->withTrashed()->count() > 0) {
            return redirect()->route('admin.camps.trashed')
                ->with('error', 'Cannot permanently delete camp with registrations.');
        }

        $camp->forceDelete();

        return redirect()->route('admin.camps.trashed')
            ->with('success', 'Camp permanently deleted.');
    }
} 