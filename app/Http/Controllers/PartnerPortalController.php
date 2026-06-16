<?php

namespace App\Http\Controllers;

use App\Models\B2CLead;
use App\Models\B2CLeadShare;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class PartnerPortalController extends Controller
{
    /**
     * Display the partner portal dashboard.
     */
    public function dashboard(): View
    {
        $user = Auth::user();
        $partner = $user->partner;

        if (!$partner) {
            abort(403, 'No partner profile associated with this user account.');
        }

        // Load shared B2C leads
        $shares = B2CLeadShare::with('lead')
            ->where('partner_id', $partner->id)
            ->orderByDesc('shared_at')
            ->paginate(15);

        $totalLeadsCount = B2CLeadShare::where('partner_id', $partner->id)->count();

        return view('partner.dashboard', compact('partner', 'shares', 'totalLeadsCount'));
    }

    /**
     * Display the specified B2C lead details.
     */
    public function showLead(B2CLead $lead): View
    {
        $user = Auth::user();
        $partner = $user->partner;

        if (!$partner) {
            abort(403, 'No partner profile associated with this user account.');
        }

        // Verify that this lead has indeed been shared with this partner
        $share = B2CLeadShare::where('b2_c_lead_id', $lead->id)
            ->where('partner_id', $partner->id)
            ->first();

        if (!$share) {
            abort(403, 'Unauthorized access. This lead is not shared with you.');
        }

        return view('partner.show', compact('lead', 'share', 'partner'));
    }
}
