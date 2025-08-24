<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\UrlForward;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UrlForwardManagementController extends Controller
{
    /**
     * Display a listing of URL forwards.
     */
    public function index(Request $request)
    {
        // Redirect to the Livewire component
        return redirect()->route('dashboard.admin.url-forwards');
    }

    /**
     * Show the form for creating a new URL forward.
     */
    public function create()
    {
        // Redirect to the Livewire component
        return redirect()->route('dashboard.admin.url-forwards');
    }

    /**
     * Store a newly created URL forward.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'internal_url' => 'required|string|max:255|unique:url_forwards',
            'external_url' => 'required|url|max:500',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'is_active' => 'boolean',
        ]);

        $validated['created_by'] = Auth::id();
        $validated['is_active'] = $request->has('is_active');

        // Clean internal URL (remove leading slash)
        $validated['internal_url'] = ltrim($validated['internal_url'], '/');

        UrlForward::create($validated);

        return redirect()->route('admin.url-forwards.index')
            ->with('success', 'URL forward created successfully.');
    }

    /**
     * Display the specified URL forward.
     */
    public function show(UrlForward $urlForward)
    {
        // Redirect to the Livewire component
        return redirect()->route('dashboard.admin.url-forwards');
    }

    /**
     * Show the form for editing the specified URL forward.
     */
    public function edit(UrlForward $urlForward)
    {
        // Redirect to the Livewire component
        return redirect()->route('dashboard.admin.url-forwards');
    }

    /**
     * Update the specified URL forward.
     */
    public function update(Request $request, UrlForward $urlForward)
    {
        $validated = $request->validate([
            'internal_url' => 'required|string|max:255|unique:url_forwards,internal_url,' . $urlForward->id,
            'external_url' => 'required|url|max:500',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'is_active' => 'boolean',
        ]);

        $validated['is_active'] = $request->has('is_active');

        // Clean internal URL (remove leading slash)
        $validated['internal_url'] = ltrim($validated['internal_url'], '/');

        $urlForward->update($validated);

        return redirect()->route('admin.url-forwards.index')
            ->with('success', 'URL forward updated successfully.');
    }

    /**
     * Remove the specified URL forward.
     */
    public function destroy(UrlForward $urlForward)
    {
        $urlForward->delete();

        return redirect()->route('admin.url-forwards.index')
            ->with('success', 'URL forward deleted successfully.');
    }

    /**
     * Display soft deleted URL forwards.
     */
    public function trashed()
    {
        $urlForwards = UrlForward::onlyTrashed()
            ->with('creator')
            ->orderBy('deleted_at', 'desc')
            ->paginate(15);

        return view('admin.url-forwards.trashed', compact('urlForwards'));
    }

    /**
     * Restore a soft deleted URL forward.
     */
    public function restore($id)
    {
        $urlForward = UrlForward::onlyTrashed()->findOrFail($id);
        $urlForward->restore();

        return redirect()->route('admin.url-forwards.index')
            ->with('success', 'URL forward restored successfully.');
    }

    /**
     * Permanently delete a URL forward.
     */
    public function forceDelete($id)
    {
        $urlForward = UrlForward::onlyTrashed()->findOrFail($id);
        $urlForward->forceDelete();

        return redirect()->route('admin.url-forwards.trashed')
            ->with('success', 'URL forward permanently deleted.');
    }

    /**
     * Toggle the active status of a URL forward.
     */
    public function toggleStatus(UrlForward $urlForward)
    {
        $urlForward->update(['is_active' => !$urlForward->is_active]);

        $status = $urlForward->is_active ? 'activated' : 'deactivated';

        return redirect()->route('admin.url-forwards.index')
            ->with('success', "URL forward {$status} successfully.");
    }
}
