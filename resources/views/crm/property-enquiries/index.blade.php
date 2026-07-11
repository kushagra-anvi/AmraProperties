@extends('layouts.crm')

@section('title', 'Property Enquiries')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <span class="inline-flex items-center gap-1 bg-teal-500/10 border border-teal-500/20 text-teal-400 text-xs font-bold px-3 py-1 rounded-full uppercase tracking-wider mb-2">
                <i data-lucide="inbox" class="w-3.5 h-3.5"></i> Property Enquiries
            </span>
            <h1 class="text-3xl font-serif font-extrabold text-white">Property Enquiries</h1>
            <p class="text-sm text-slate-400">Buyer contact reveals and property-specific requests captured from the website.</p>
        </div>
    </div>

    <div class="bg-slate-900 border border-slate-800 rounded-3xl p-6 shadow-md">
        <form method="GET" action="{{ route('crm.property-enquiries.index') }}" class="grid gap-4 md:grid-cols-[1fr_180px_auto] items-end">
            <div>
                <label class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block mb-2">Search</label>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Buyer, phone, email, property..."
                    class="w-full bg-slate-950 border border-slate-800 focus:border-amra-primary rounded-xl px-4 py-3 outline-none text-xs text-slate-200">
            </div>
            <div>
                <label class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block mb-2">Status</label>
                <select name="status" class="w-full bg-slate-950 border border-slate-800 focus:border-amra-primary rounded-xl px-4 py-3 outline-none text-xs text-slate-200">
                    <option value="">All</option>
                    <option value="new" {{ request('status') === 'new' ? 'selected' : '' }}>New</option>
                    <option value="contacted" {{ request('status') === 'contacted' ? 'selected' : '' }}>Contacted</option>
                    <option value="closed" {{ request('status') === 'closed' ? 'selected' : '' }}>Closed</option>
                </select>
            </div>
            <button class="bg-slate-800 hover:bg-slate-700 text-white font-bold text-xs px-6 py-3 rounded-xl transition-all">Apply</button>
        </form>
    </div>

    <div class="bg-slate-900 border border-slate-800 rounded-3xl overflow-hidden shadow-md">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead class="bg-slate-50">
                    <tr class="border-b border-slate-200 text-[11px] font-extrabold text-slate-500 uppercase tracking-[0.12em]">
                        <th class="px-6 py-4">Buyer</th>
                        <th class="px-6 py-4">Property</th>
                        <th class="px-6 py-4">Seller</th>
                        <th class="px-6 py-4">Source</th>
                        <th class="px-6 py-4">Status</th>
                        <th class="px-6 py-4">Date</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-850">
                    @forelse ($enquiries as $enquiry)
                        <tr class="hover:bg-slate-850/20 transition-colors">
                            <td class="px-6 py-4">
                                <div class="font-bold text-white text-sm">{{ $enquiry->name }}</div>
                                <div class="text-xs text-slate-400">{{ $enquiry->phone }}</div>
                                @if($enquiry->email)<div class="text-xs text-slate-500">{{ $enquiry->email }}</div>@endif
                            </td>
                            <td class="px-6 py-4">
                                <div class="font-semibold text-slate-200 text-sm">
                                    {{ $enquiry->property?->title ?? ($enquiry->source === 'recommended_seller_contact' ? 'Seller contact request' : 'Deleted property') }}
                                </div>
                                <div class="text-xs text-slate-500">{{ $enquiry->property?->city ?? $enquiry->sellerPartner?->city }}</div>
                            </td>
                            <td class="px-6 py-4 text-xs text-slate-300">
                                {{ $enquiry->partner?->company_name ?? $enquiry->sellerPartner?->company_name ?? 'Amra Concierge' }}
                            </td>
                            <td class="px-6 py-4 text-xs text-slate-400">{{ ucfirst(str_replace('_', ' ', $enquiry->source)) }}</td>
                            <td class="px-6 py-4">
                                <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-xl text-[10px] font-bold bg-teal-500/10 text-teal-400 border border-teal-500/20 uppercase tracking-wide">
                                    {{ ucfirst($enquiry->status) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-xs text-slate-400">{{ $enquiry->created_at->format('d M Y, h:i A') }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="px-6 py-12 text-center text-slate-400">No property enquiries found.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if ($enquiries->hasPages())
            <div class="px-6 py-4 border-t border-slate-800 bg-slate-950/20">{{ $enquiries->links() }}</div>
        @endif
    </div>
</div>
@endsection
