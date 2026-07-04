@extends('layouts.crm')

@section('title', 'My Properties')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <span class="inline-flex items-center gap-1 bg-teal-500/10 border border-teal-500/20 text-teal-400 text-xs font-bold px-3 py-1 rounded-full uppercase tracking-wider mb-2">
                <i data-lucide="building-2" class="w-3.5 h-3.5"></i> Partner Properties
            </span>
            <h1 class="text-3xl font-serif font-extrabold text-amra-dark">My Properties</h1>
            <p class="text-sm text-slate-400">Submit listings for admin review and track property enquiries.</p>
        </div>
        <a href="{{ route('crm.partner.properties.create') }}" class="bg-amra-primary hover:bg-teal-600 text-white font-bold text-xs px-5 py-3.5 rounded-xl transition-all shadow-md flex items-center gap-1.5">
            <i data-lucide="plus-circle" class="w-4 h-4"></i> Post Property
        </a>
    </div>

    <div class="grid lg:grid-cols-2 gap-6">
        <div class="bg-white border border-slate-200 rounded-3xl overflow-hidden shadow-md">
            <div class="px-6 py-4 border-b border-slate-100 bg-slate-50">
                <h3 class="text-xs font-bold text-slate-500 uppercase tracking-widest">Submitted Listings</h3>
            </div>
            <div class="divide-y divide-slate-100">
                @forelse($properties as $property)
                    <div class="p-5">
                        <div class="font-bold text-slate-800">{{ $property->title }}</div>
                        <div class="text-xs text-slate-500">{{ $property->city }} • {{ $property->formatted_price }}</div>
                        <span class="mt-2 inline-flex rounded-full border px-2.5 py-1 text-[10px] font-bold {{ $property->status === 'publish' ? 'border-teal-200 bg-teal-50 text-teal-700' : 'border-amber-200 bg-amber-50 text-amber-700' }}">
                            {{ $property->status === 'publish' ? 'Published' : 'Pending Review' }}
                        </span>
                    </div>
                @empty
                    <div class="p-8 text-center text-sm text-slate-400">No properties submitted yet.</div>
                @endforelse
            </div>
            @if($properties->hasPages())<div class="p-4 border-t border-slate-100">{{ $properties->links() }}</div>@endif
        </div>

        <div class="bg-white border border-slate-200 rounded-3xl overflow-hidden shadow-md">
            <div class="px-6 py-4 border-b border-slate-100 bg-slate-50">
                <h3 class="text-xs font-bold text-slate-500 uppercase tracking-widest">Recent Property Enquiries</h3>
            </div>
            <div class="divide-y divide-slate-100">
                @forelse($enquiries as $enquiry)
                    <div class="p-5">
                        <div class="font-bold text-slate-800">{{ $enquiry->name }} <span class="text-slate-400 font-medium">({{ $enquiry->phone }})</span></div>
                        <div class="text-xs text-slate-500">{{ $enquiry->property?->title }} • {{ $enquiry->created_at->format('d M Y') }}</div>
                        @if($enquiry->message)<p class="mt-2 text-xs text-slate-500">{{ Str::limit($enquiry->message, 110) }}</p>@endif
                    </div>
                @empty
                    <div class="p-8 text-center text-sm text-slate-400">No enquiries yet.</div>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection
