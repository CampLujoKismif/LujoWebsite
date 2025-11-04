<?php

namespace App\Http\Controllers;

use App\Models\CampInstance;
use Illuminate\Http\Request;

class PublicCampController extends Controller
{
    /**
     * Display the specified camp instance.
     */
    public function showSession(CampInstance $instance)
    {
        // Refresh the instance to ensure we have the latest data
        $instance->refresh();
        
        // Load the camp relationship
        $instance->load('camp');
        
        return view('camp-sessions.show', compact('instance'));
    }
} 