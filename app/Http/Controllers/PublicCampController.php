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
        // Load the camp relationship
        $instance->load('camp');
        
        return view('camp-sessions.show', compact('instance'));
    }
} 