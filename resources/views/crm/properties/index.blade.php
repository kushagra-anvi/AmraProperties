@extends('layouts.crm')

@section('title', 'Properties Management')

@section('content')
<div class="space-y-6">
    <!-- Header Block -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <span class="inline-flex items-center gap-1 bg-teal-500/10 border border-teal-500/20 text-teal-400 text-xs font-bold px-3 py-1 rounded-full uppercase tracking-wider mb-2">
                <i data-lucide="home" class="w-3.5 h-3.5"></i> Property Directory
            </span>
            <h1 class="text-3xl font-serif font-extrabold text-white">Properties Directory</h1>
            <p class="text-sm text-slate-400">Add, edit, or remove client-facing property listings on the site</p>
        </div>
        <div>
            <a href="{{ route('crm.properties.create') }}" class="bg-amra-primary hover:bg-teal-600 text-white font-bold text-xs px-5 py-3.5 rounded-xl transition-all shadow-md flex items-center gap-1.5 active:scale-95">
                <i data-lucide="plus-circle" class="w-4 h-4"></i> Add Property
            </a>
        </div>
    </div>

    <!-- Toolbar Filters -->
    <div class="bg-slate-900 border border-slate-800 rounded-3xl p-6 shadow-md">
        <form action="{{ route('crm.properties.index') }}" method="GET" class="grid gap-4 lg:grid-cols-[1fr_auto] items-end">
            <div class="relative w-full">
                <label class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block mb-2">Search Properties</label>
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center text-slate-500 pointer-events-none">
                        <i data-lucide="search" class="w-4 h-4"></i>
                    </span>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by title, city, developer, configuration..." 
                        class="w-full bg-slate-950 border border-slate-800 focus:border-amra-primary rounded-xl pl-10 pr-4 py-3 outline-none text-xs text-slate-200">
                </div>
            </div>
            <div>
                <button type="submit" class="bg-slate-800 hover:bg-slate-700 text-white font-bold text-xs px-6 py-3 rounded-xl transition-all">
                    Apply Filter
                </button>
            </div>
        </form>
    </div>

    <!-- Table Grid -->
    <div class="bg-slate-900 border border-slate-800 rounded-3xl overflow-hidden shadow-md">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead class="bg-slate-50">
                    <tr class="border-b border-slate-200 text-[11px] font-extrabold text-slate-500 uppercase tracking-[0.12em]">
                        <th class="px-6 py-4 whitespace-nowrap">Property</th>
                        <th class="px-6 py-4 whitespace-nowrap">Price</th>
                        <th class="px-6 py-4 whitespace-nowrap">BHK / Config</th>
                        <th class="px-6 py-4 whitespace-nowrap">Specs</th>
                        <th class="px-6 py-4 whitespace-nowrap">City</th>
                        <th class="px-6 py-4 whitespace-nowrap">Status</th>
                        <th class="px-6 py-4 whitespace-nowrap">Badges</th>
                        <th class="px-6 py-4 text-right whitespace-nowrap">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-850">
                    @forelse ($properties as $property)
                        <tr class="hover:bg-slate-850/20 transition-colors">
                            <td class="px-6 py-4">
                                <div class="font-bold text-white text-sm">
                                    {{ $property->title }}
                                </div>
                                <span class="text-[10px] font-bold text-slate-500 uppercase mt-0.5 block">
                                    {{ $property->developer_name ?? 'Individual Owner' }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-sm font-semibold text-slate-200">
                                {{ $property->formatted_price }}
                            </td>
                            <td class="px-6 py-4 text-sm text-slate-300">
                                {{ $property->configuration ?? 'N/A' }}
                            </td>
                            <td class="px-6 py-4 text-xs text-slate-400">
                                <span class="block">{{ $property->bedrooms }} Beds / {{ $property->bathrooms }} Baths</span>
                                <span class="text-[10px] text-slate-500">{{ $property->area }} {{ str_replace('_', ' ', $property->area_unit) }}</span>
                            </td>
                            <td class="px-6 py-4 text-sm text-slate-300">
                                {{ $property->city ?? 'N/A' }}
                            </td>
                            <td class="px-6 py-4">
                                @if ($property->status === 'publish')
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-xl text-[10px] font-bold bg-teal-500/10 text-teal-400 border border-teal-500/20 uppercase tracking-wide">
                                        Published
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-xl text-[10px] font-bold bg-slate-500/10 text-slate-400 border border-slate-500/20 uppercase tracking-wide">
                                        Draft
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 space-y-1">
                                @if ($property->is_featured)
                                    <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded text-[9px] font-bold bg-amber-500/10 text-amber-400 border border-amber-500/20 uppercase tracking-wide block w-fit">
                                        Featured
                                    </span>
                                @endif
                                @if ($property->is_rera_approved)
                                    <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded text-[9px] font-bold bg-indigo-500/10 text-indigo-400 border border-indigo-500/20 uppercase tracking-wide block w-fit">
                                        RERA Approved
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-right text-xs">
                                <div class="flex items-center justify-end gap-2">
                                    <a href="{{ route('crm.properties.edit', $property->id) }}" class="inline-flex items-center gap-1 bg-slate-100 hover:bg-teal-500 text-slate-600 hover:text-white border border-slate-200 px-3 py-1.5 rounded-lg font-bold text-xs transition-colors">
                                        <i data-lucide="edit" class="w-3.5 h-3.5"></i> Edit
                                    </a>
                                    <form action="{{ route('crm.properties.destroy', $property->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this property?');" class="inline-block">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="inline-flex items-center gap-1 bg-slate-100 hover:bg-rose-500 text-slate-600 hover:text-white border border-slate-200 px-3 py-1.5 rounded-lg font-bold text-xs transition-colors">
                                            <i data-lucide="trash-2" class="w-3.5 h-3.5"></i> Delete
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="px-6 py-12 text-center text-slate-400">
                                <div class="flex flex-col items-center justify-center gap-3">
                                    <i data-lucide="home" class="w-10 h-10 text-slate-300"></i>
                                    <p class="text-sm">No properties found. Click "Add Property" to create one.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if ($properties->hasPages())
            <div class="px-6 py-4 border-t border-slate-800 bg-slate-950/20">
                {{ $properties->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
