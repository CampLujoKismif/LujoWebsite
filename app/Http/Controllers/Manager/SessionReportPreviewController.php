<?php

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;
use App\Models\CampInstance;
use App\Services\SessionReportBuilder;
use Illuminate\Http\Request;

class SessionReportPreviewController extends Controller
{
    /**
     * Display an HTML preview of the session report before generating the PDF.
     */
    public function __invoke(Request $request, CampInstance $campInstance)
    {
        $user = $request->user();

        $isAuthorized = $user->hasRole('system-admin')
            || $user->assignedCamps()->where('camps.id', $campInstance->camp_id)->exists()
            || $campInstance->assignedUsers()->where('user_id', $user->id)->exists();

        abort_unless($isAuthorized, 403);

        $reportData = SessionReportBuilder::build($campInstance);

        if (!$reportData) {
            return view('manager.session-report-preview-empty', [
                'campInstance' => $campInstance->loadMissing('camp'),
            ]);
        }

        return view('pdf.manager.session-report', $reportData + ['preview' => true]);
    }
}


