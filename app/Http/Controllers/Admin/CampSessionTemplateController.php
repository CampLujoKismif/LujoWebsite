<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Camp;
use App\Models\CampSessionDetailTemplate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\JsonResponse;

class CampSessionTemplateController extends Controller
{
    /**
     * Display a listing of all camps with their session templates.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $camps = Camp::with('sessionDetailTemplate')->orderBy('display_name')->get();
        
        return view('admin.camps.session-templates-index', compact('camps'));
    }

    /**
     * Display or edit the session detail template for a camp.
     *
     * @param Camp $camp
     * @return \Illuminate\View\View
     */
    public function show(Camp $camp)
    {
        // Get or create template for this camp
        $template = CampSessionDetailTemplate::getOrCreateForCamp($camp->id);
        
        return view('admin.camps.session-template', compact('camp', 'template'));
    }

    /**
     * Update the session detail template for a camp.
     *
     * @param Request $request
     * @param Camp $camp
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, Camp $camp)
    {
        $validated = $request->validate([
            'theme_description' => 'nullable|string',
            'schedule_data' => 'nullable|array',
            'schedule_data.*.time' => 'required_with:schedule_data|string',
            'schedule_data.*.activity' => 'required_with:schedule_data|string',
            'additional_info' => 'nullable|string',
            'theme_photos' => 'nullable|array',
            'theme_photos.*' => 'url',
            'meta_description' => 'nullable|string|max:255',
        ]);

        try {
            // Get or create template
            $template = CampSessionDetailTemplate::getOrCreateForCamp($camp->id);
            
            // Process schedule_data if provided - convert from array format to key-value pairs
            if (isset($validated['schedule_data']) && is_array($validated['schedule_data'])) {
                $scheduleData = [];
                foreach ($validated['schedule_data'] as $item) {
                    if (!empty($item['time']) && !empty($item['activity'])) {
                        $scheduleData[$item['time']] = $item['activity'];
                    }
                }
                $validated['schedule_data'] = !empty($scheduleData) ? $scheduleData : null;
            } else {
                $validated['schedule_data'] = $template->schedule_data;
            }

            // Update template
            $template->update([
                'theme_description' => $validated['theme_description'] ?? null,
                'schedule_data' => $validated['schedule_data'],
                'additional_info' => $validated['additional_info'] ?? null,
                'theme_photos' => $validated['theme_photos'] ?? null,
                'meta_description' => $validated['meta_description'] ?? null,
            ]);

            Log::info('Camp session template updated', [
                'camp_id' => $camp->id,
                'camp_name' => $camp->display_name,
                'user_id' => auth()->id(),
            ]);

            return redirect()->route('admin.camps.session-template', $camp)
                ->with('success', 'Session detail template updated successfully.');
        } catch (\Exception $e) {
            Log::error('Failed to update camp session template', [
                'camp_id' => $camp->id,
                'error' => $e->getMessage(),
            ]);

            return redirect()->route('admin.camps.session-template', $camp)
                ->with('error', 'Failed to update template. Please try again.');
        }
    }

    /**
     * Upload an image for use in session templates.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function uploadImage(Request $request): JsonResponse
    {
        $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:5120', // 5MB max
        ]);

        try {
            // Store the image in the public disk under camp-session-images
            $path = $request->file('image')->store('camp-session-images', 'public');
            
            // Get the public URL
            $url = Storage::disk('public')->url($path);
            
            Log::info('Camp session image uploaded', [
                'path' => $path,
                'url' => $url,
                'user_id' => auth()->id(),
            ]);

            return response()->json([
                'success' => true,
                'url' => $url,
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to upload camp session image', [
                'error' => $e->getMessage(),
                'user_id' => auth()->id(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to upload image. Please try again.',
            ], 500);
        }
    }
}
