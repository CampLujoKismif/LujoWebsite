<?php

namespace App\Http\Controllers;

use App\Models\UrlForward;
use Illuminate\Http\Request;

class UrlForwardController extends Controller
{
    /**
     * Handle the URL forward and redirect to external URL.
     */
    public function forward(Request $request, string $internalUrl)
    {
        // Find the URL forward by internal URL
        $urlForward = UrlForward::active()
            ->where('internal_url', $internalUrl)
            ->first();

        if (!$urlForward) {
            abort(404, 'URL forward not found or inactive.');
        }

        // Increment click count
        $urlForward->incrementClickCount();

        // Redirect to external URL
        return redirect()->away($urlForward->external_url);
    }
}
