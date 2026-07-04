@if ($paginator->hasPages())
    <div class="flex justify-center items-center mt-16 select-none animate-fade-in">
        <nav role="navigation" aria-label="Pagination Navigation" class="bg-white/80 backdrop-blur-md border border-slate-100/80 px-6 py-3 rounded-2xl shadow-sm inline-flex items-center gap-2 select-none">
            {{-- Previous Page Link --}}
            @if ($paginator->onFirstPage())
                <span class="px-4 py-2 rounded-xl bg-gray-50 border border-gray-100 text-gray-300 cursor-not-allowed flex items-center justify-center h-10 gap-1.5 text-xs font-bold">
                    <i data-lucide="chevron-left" class="w-4 h-4"></i> Prev
                </span>
            @else
                <a href="{{ $paginator->appends(request()->query())->previousPageUrl() }}" class="px-4 py-2 rounded-xl bg-white border border-gray-200 text-slate-700 hover:bg-slate-50 hover:border-slate-300 hover:text-teal-600 active:scale-95 transition-all flex items-center justify-center h-10 gap-1.5 text-xs font-bold shadow-sm" aria-label="Previous Page">
                    <i data-lucide="chevron-left" class="w-4 h-4"></i> Prev
                </a>
            @endif

            {{-- Pagination Elements --}}
            @foreach ($elements as $element)
                {{-- "Three Dots" Separator --}}
                @if (is_string($element))
                    <span class="w-10 h-10 text-slate-400 hidden md:flex items-center justify-center cursor-default">
                        {{ $element }}
                    </span>
                @endif

                {{-- Array Of Links --}}
                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        @if ($page == $paginator->currentPage())
                            <span class="w-10 h-10 rounded-xl bg-teal-500 border border-teal-500 text-white font-bold text-sm shadow-md shadow-teal-500/25 flex items-center justify-center cursor-default">
                                {{ $page }}
                            </span>
                        @else
                            @php
                                $isNeighbor = abs($page - $paginator->currentPage()) <= 1;
                            @endphp
                            <a href="{{ $paginator->appends(request()->query())->url($page) }}" 
                               class="w-10 h-10 rounded-xl bg-white border border-gray-200 text-slate-700 font-bold text-sm hover:bg-slate-50 hover:border-slate-300 hover:text-teal-600 active:scale-95 transition-all {{ $isNeighbor ? 'flex' : 'hidden md:flex' }} items-center justify-center shadow-sm">
                                {{ $page }}
                            </a>
                        @endif
                    @endforeach
                @endif
            @endforeach

            {{-- Next Page Link --}}
            @if ($paginator->hasMorePages())
                <a href="{{ $paginator->appends(request()->query())->nextPageUrl() }}" class="px-4 py-2 rounded-xl bg-white border border-gray-200 text-slate-700 hover:bg-slate-50 hover:border-slate-300 hover:text-teal-600 active:scale-95 transition-all flex items-center justify-center h-10 gap-1.5 text-xs font-bold shadow-sm" aria-label="Next Page">
                    Next <i data-lucide="chevron-right" class="w-4 h-4"></i>
                </a>
            @else
                <span class="px-4 py-2 rounded-xl bg-gray-50 border border-gray-100 text-gray-300 cursor-not-allowed flex items-center justify-center h-10 gap-1.5 text-xs font-bold">
                    Next <i data-lucide="chevron-right" class="w-4 h-4"></i>
                </span>
            @endif
        </nav>
    </div>
@endif
