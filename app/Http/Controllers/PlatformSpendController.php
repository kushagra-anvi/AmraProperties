<?php

namespace App\Http\Controllers;

use App\Models\PlatformSpend;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class PlatformSpendController extends Controller
{
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'source_platform' => ['required', 'string', 'in:meta,google,website,manual,csv'],
            'amount' => ['required', 'numeric', 'min:0'],
            'spent_on' => ['required', 'date'],
            'notes' => ['nullable', 'string'],
        ]);

        PlatformSpend::create($validated);

        return back()->with('success', 'Platform spend recorded successfully.');
    }
}
