<?php

namespace App\Http\Controllers\CRM;

use App\Http\Controllers\Controller;
use App\Models\TataCallLog;
use Illuminate\Http\Request;

class TataCallLogController extends Controller
{
    public function index(Request $request)
    {
        $query = TataCallLog::query()->latest('start_time');

        // Filter by Search Query (phone number)
        if ($request->filled('search')) {
            $search = preg_replace('/\D/', '', $request->search);
            $query->where('caller_number', 'like', "%{$search}%");
        }

        // Filter by Call Status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by TFN
        if ($request->filled('tfn')) {
            $query->where('tfn_number', $request->tfn);
        }

        $logs = $query->paginate(20)->withQueryString();

        // Get unique TFN numbers for filters
        $tfnNumbers = TataCallLog::select('tfn_number')->distinct()->pluck('tfn_number');

        return view('crm.tata-logs.index', compact('logs', 'tfnNumbers'));
    }

    public function updateDisposition(Request $request, TataCallLog $log)
    {
        $request->validate([
            'disposition' => 'required|string|max:255',
            'notes' => 'nullable|string',
        ]);

        $log->update([
            'disposition' => $request->disposition,
            'notes' => $request->notes,
        ]);

        if ($request->ajax()) {
            return response()->json([
                'status' => 'success',
                'message' => 'Disposition updated successfully.'
            ]);
        }

        return redirect()->back()->with('success', 'Disposition updated successfully.');
    }
}
