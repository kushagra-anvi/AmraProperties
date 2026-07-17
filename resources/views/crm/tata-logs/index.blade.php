@extends('layouts.crm')

@section('title', 'Call Logs & Dispositions')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <span class="inline-flex items-center gap-1 bg-amra-primary/10 border border-amra-primary/20 text-amra-primary text-xs font-bold px-3 py-1 rounded-full uppercase tracking-wider mb-2">
                <i data-lucide="phone-call" class="w-3.5 h-3.5"></i> Telephony Logs
            </span>
            <h1 class="text-3xl font-serif font-extrabold text-amra-dark">Tata Call Logs & Dispositions</h1>
            <p class="text-sm text-slate-500">Track all incoming and outgoing calls, listen to audio recordings, and manage call dispositions.</p>
        </div>
        <div>
            <button id="sync-now-btn" onclick="syncCallLogs()" class="inline-flex items-center gap-2 bg-amra-primary hover:bg-amra-primary/95 text-white font-bold text-xs px-5 py-3.5 rounded-xl transition-all shadow-sm">
                <i data-lucide="refresh-cw" class="w-4 h-4" id="sync-icon"></i>
                <span id="sync-text">Sync Calls</span>
            </button>
        </div>
    </div>

    <!-- Filters Section -->
    <div class="bg-white border border-slate-200 rounded-3xl p-6 shadow-md">
        <form method="GET" action="{{ route('crm.tata-logs.index') }}" class="grid gap-4 md:grid-cols-[1fr_180px_180px_auto] items-end">
            <div>
                <label class="text-[10px] font-bold text-slate-500 uppercase tracking-wider block mb-2">Search Caller Number</label>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="e.g. 955999..."
                    class="w-full bg-white border border-slate-200 focus:border-amra-primary rounded-xl px-4 py-3 outline-none text-xs text-amra-dark">
            </div>
            <div>
                <label class="text-[10px] font-bold text-slate-500 uppercase tracking-wider block mb-2">Call Status</label>
                <select name="status" class="w-full bg-white border border-slate-200 focus:border-amra-primary rounded-xl px-4 py-3 outline-none text-xs text-amra-dark">
                    <option value="">All Statuses</option>
                    <option value="Answered" {{ request('status') === 'Answered' ? 'selected' : '' }}>Answered</option>
                    <option value="Missed" {{ request('status') === 'Missed' ? 'selected' : '' }}>Missed</option>
                    <option value="Abandoned" {{ request('status') === 'Abandoned' ? 'selected' : '' }}>Abandoned</option>
                    <option value="Busy" {{ request('status') === 'Busy' ? 'selected' : '' }}>Busy</option>
                </select>
            </div>
            <div>
                <label class="text-[10px] font-bold text-slate-500 uppercase tracking-wider block mb-2">Toll-Free Number (TFN)</label>
                <select name="tfn" class="w-full bg-white border border-slate-200 focus:border-amra-primary rounded-xl px-4 py-3 outline-none text-xs text-amra-dark">
                    <option value="">All Numbers</option>
                    @foreach ($tfnNumbers as $tfn)
                        <option value="{{ $tfn }}" {{ request('tfn') === $tfn ? 'selected' : '' }}>{{ $tfn }}</option>
                    @endforeach
                </select>
            </div>
            <div class="flex gap-2">
                <button type="submit" class="bg-amra-primary hover:bg-amra-primary/95 text-white font-bold text-xs px-6 py-3.5 rounded-xl transition-all">Filter</button>
                <a href="{{ route('crm.tata-logs.index') }}" class="bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold text-xs px-6 py-3.5 rounded-xl transition-all flex items-center justify-center">Reset</a>
            </div>
        </form>
    </div>

    <!-- Call Logs Table -->
    <div class="bg-white border border-slate-200 rounded-3xl overflow-hidden shadow-md">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead class="bg-slate-50 border-b border-slate-200">
                    <tr class="text-[11px] font-extrabold text-slate-500 uppercase tracking-[0.12em]">
                        <th class="px-6 py-4">Caller Number</th>
                        <th class="px-6 py-4">Toll-Free (TFN)</th>
                        <th class="px-6 py-4">Timing</th>
                        <th class="px-6 py-4">Duration</th>
                        <th class="px-6 py-4">Call Status</th>
                        <th class="px-6 py-4">Recording</th>
                        <th class="px-6 py-4">Disposition Outcome</th>
                        <th class="px-6 py-4">Notes</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse ($logs as $log)
                        <tr class="hover:bg-slate-50/50 transition-colors">
                            <!-- Caller Number -->
                            <td class="px-6 py-4">
                                <div class="font-bold text-amra-dark text-sm">+91 {{ $log->caller_number }}</div>
                                <div class="text-[10px] text-slate-400 mt-0.5 uppercase font-bold tracking-wider">{{ $log->direction }}</div>
                            </td>

                            <!-- TFN Number -->
                            <td class="px-6 py-4 text-xs font-semibold text-slate-600">
                                {{ $log->tfn_number }}
                            </td>

                            <!-- Timing -->
                            <td class="px-6 py-4 text-xs text-slate-600">
                                <div class="font-medium">{{ $log->start_time->format('d M Y') }}</div>
                                <div class="text-slate-400 text-[10px] mt-0.5">{{ $log->start_time->format('h:i A') }}</div>
                            </td>

                            <!-- Duration -->
                            <td class="px-6 py-4 text-xs text-slate-600 font-medium">
                                @if ($log->duration_seconds >= 60)
                                    {{ floor($log->duration_seconds / 60) }}m {{ $log->duration_seconds % 60 }}s
                                @else
                                    {{ $log->duration_seconds }}s
                                @endif
                            </td>

                            <!-- Status Badge -->
                            <td class="px-6 py-4">
                                @if ($log->status === 'Answered')
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-xl text-[10px] font-bold bg-emerald-50 text-emerald-600 border border-emerald-100 uppercase tracking-wide">
                                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span> Answered
                                    </span>
                                @elseif ($log->status === 'Missed')
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-xl text-[10px] font-bold bg-rose-50 text-rose-600 border border-rose-100 uppercase tracking-wide">
                                        <span class="w-1.5 h-1.5 rounded-full bg-rose-500"></span> Missed
                                    </span>
                                @elseif ($log->status === 'Abandoned')
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-xl text-[10px] font-bold bg-amber-50 text-amber-600 border border-amber-100 uppercase tracking-wide">
                                        <span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span> Abandoned
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-xl text-[10px] font-bold bg-slate-100 text-slate-600 border border-slate-200 uppercase tracking-wide">
                                        {{ $log->status }}
                                    </span>
                                @endif
                            </td>

                            <!-- Recording Playback -->
                            <td class="px-6 py-4">
                                @if ($log->recording_url)
                                    <a href="{{ $log->recording_url }}" target="_blank" class="inline-flex items-center gap-1.5 text-xs text-amra-primary hover:underline font-bold">
                                        <i data-lucide="play-circle" class="w-4 h-4"></i> Listen
                                    </a>
                                @else
                                    <span class="text-xs text-slate-400">Not Available</span>
                                @endif
                            </td>

                            <!-- Disposition Outcome Dropdown -->
                            <td class="px-6 py-4">
                                <select id="disposition-{{ $log->id }}" onchange="updateCallDisposition({{ $log->id }})"
                                    class="bg-white border border-slate-200 focus:border-amra-primary rounded-xl px-3 py-1.5 text-xs text-amra-dark font-medium shadow-sm outline-none w-48">
                                    <option value="">-- Set Outcome --</option>
                                    <option value="Connected / Interested" {{ $log->disposition === 'Connected / Interested' ? 'selected' : '' }}>Connected / Interested</option>
                                    <option value="Connected / Not Interested" {{ $log->disposition === 'Connected / Not Interested' ? 'selected' : '' }}>Connected / Not Interested</option>
                                    <option value="Call Back Later" {{ $log->disposition === 'Call Back Later' ? 'selected' : '' }}>Call Back Later</option>
                                    <option value="No Answer" {{ $log->disposition === 'No Answer' ? 'selected' : '' }}>No Answer</option>
                                    <option value="Wrong Number" {{ $log->disposition === 'Wrong Number' ? 'selected' : '' }}>Wrong Number</option>
                                    <option value="Busy / Disconnected" {{ $log->disposition === 'Busy / Disconnected' ? 'selected' : '' }}>Busy / Disconnected</option>
                                </select>
                            </td>

                            <!-- Custom Notes -->
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-2">
                                    <input type="text" id="notes-{{ $log->id }}" value="{{ $log->notes }}"
                                        placeholder="Add note..." onblur="updateCallNotes({{ $log->id }})"
                                        class="bg-transparent border-b border-transparent hover:border-slate-200 focus:border-amra-primary text-xs text-slate-600 px-1 py-1 outline-none w-36 transition-colors">
                                    <span id="saved-{{ $log->id }}" class="hidden text-[10px] text-emerald-500 font-bold"><i data-lucide="check" class="w-3 h-3"></i></span>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="px-6 py-16 text-center text-slate-400">
                                <div class="flex flex-col items-center justify-center gap-2">
                                    <i data-lucide="phone-off" class="w-8 h-8 text-slate-300"></i>
                                    <span class="font-medium text-slate-500">No telephony call logs found.</span>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <!-- Pagination Links -->
        @if ($logs->hasPages())
            <div class="px-6 py-4 border-t border-slate-100 bg-slate-50/50">
                {{ $logs->links() }}
            </div>
        @endif
    </div>
</div>
@endsection

@section('scripts')
<script>
    async function updateCallDisposition(logId) {
        const dispositionSelect = document.getElementById(`disposition-${logId}`);
        const notesInput = document.getElementById(`notes-${logId}`);
        const savedBadge = document.getElementById(`saved-${logId}`);

        try {
            const response = await fetch(`/crm/tata-logs/${logId}/disposition`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    disposition: dispositionSelect.value,
                    notes: notesInput.value
                })
            });

            const result = await response.json();
            if (result.status === 'success') {
                showSavedCheck(savedBadge);
            }
        } catch (error) {
            console.error('Failed to update call disposition:', error);
        }
    }

    async function updateCallNotes(logId) {
        const dispositionSelect = document.getElementById(`disposition-${logId}`);
        const notesInput = document.getElementById(`notes-${logId}`);
        const savedBadge = document.getElementById(`saved-${logId}`);

        try {
            const response = await fetch(`/crm/tata-logs/${logId}/disposition`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    disposition: dispositionSelect.value || 'None',
                    notes: notesInput.value
                })
            });

            const result = await response.json();
            if (result.status === 'success') {
                showSavedCheck(savedBadge);
            }
        } catch (error) {
            console.error('Failed to update call notes:', error);
        }
    }

    function showSavedCheck(badge) {
        badge.classList.remove('hidden');
        setTimeout(() => {
            badge.classList.add('hidden');
        }, 1500);
    }

    async function syncCallLogs() {
        const btn = document.getElementById('sync-now-btn');
        const icon = document.getElementById('sync-icon');
        const text = document.getElementById('sync-text');

        // Loading state
        btn.disabled = true;
        btn.classList.add('opacity-75', 'cursor-not-allowed');
        icon.classList.add('animate-spin');
        text.innerText = 'Syncing...';

        try {
            const response = await fetch('/crm/tata-logs/sync', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            });
            const result = await response.json();
            if (result.status === 'success') {
                window.location.reload();
            } else {
                alert('Sync failed. Please try again.');
            }
        } catch (error) {
            console.error('Sync failed:', error);
            alert('Failed to connect to the server.');
        } finally {
            btn.disabled = false;
            btn.classList.remove('opacity-75', 'cursor-not-allowed');
            icon.classList.remove('animate-spin');
            text.innerText = 'Sync Calls';
        }
    }

    // Silent sync helper
    async function silentSync() {
        try {
            const response = await fetch('/crm/tata-logs/sync', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            });
            const result = await response.json();
            if (result.status === 'success' && result.new_count > 0) {
                window.location.reload();
            }
        } catch (error) {
            console.error('Silent sync error:', error);
        }
    }

    // Auto sync on load and every 10 seconds in background
    document.addEventListener('DOMContentLoaded', () => {
        // Run silent sync immediately on load
        silentSync();

        // Poll every 10 seconds
        setInterval(silentSync, 10000);
    });
</script>
@endsection
