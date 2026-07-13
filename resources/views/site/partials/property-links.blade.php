@php
    $tabs = $tabs ?? [];
    $sectionId = $sectionId ?? 'property-links-' . uniqid();
    $variant = $variant ?? 'band';
    $sectionClass = $variant === 'embedded'
        ? 'mt-10'
        : 'bg-white border-y border-slate-100';
    $containerClass = $variant === 'embedded'
        ? 'rounded-2xl border border-slate-200 bg-white/85 px-5 shadow-sm ring-1 ring-white/80 sm:px-6'
        : 'max-w-7xl mx-auto px-4 md:px-6';
    $tabWrapClass = $variant === 'embedded'
        ? 'overflow-x-auto border-b border-slate-200/80'
        : 'overflow-x-auto border-b border-slate-200';
    $tabButtonClass = $variant === 'embedded'
        ? 'px-3 py-4'
        : 'px-4 py-5';
    $linkClass = $variant === 'embedded'
        ? 'rounded-xl border border-transparent px-3 py-2 hover:border-teal-100 hover:bg-teal-50/60'
        : '';
@endphp

@if(!empty($tabs))
    <section class="{{ $sectionClass }}" data-property-links-section>
        <div class="{{ $containerClass }}">
            <div class="{{ $tabWrapClass }}">
                <div class="flex min-w-max justify-between gap-3 md:gap-8">
                    @foreach($tabs as $tabKey => $tab)
                        @php($panelId = $sectionId . '-' . $tabKey)
                        <button type="button"
                            class="home-link-tab relative {{ $tabButtonClass }} text-xs sm:text-sm font-bold uppercase tracking-wide {{ $loop->first ? 'text-slate-950' : 'text-slate-500' }} transition-colors hover:text-slate-950 md:flex-1"
                            data-target="{{ $panelId }}"
                            aria-controls="{{ $panelId }}"
                            aria-selected="{{ $loop->first ? 'true' : 'false' }}">
                            {{ $tab['label'] }}
                            <span class="home-link-tab-line absolute inset-x-0 bottom-0 h-0.5 bg-amra-primary {{ $loop->first ? '' : 'hidden' }}"></span>
                        </button>
                    @endforeach
                </div>
            </div>

            <div class="{{ $variant === 'embedded' ? 'py-5 sm:py-6' : 'py-7 sm:py-8' }}">
                @foreach($tabs as $tabKey => $tab)
                    @php($panelId = $sectionId . '-' . $tabKey)
                    <div id="{{ $panelId }}" class="home-link-panel {{ $loop->first ? '' : 'hidden' }}">
                        <h2 class="text-base sm:text-lg font-extrabold text-slate-950 mb-5">{{ $tab['heading'] }}</h2>
                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-x-10 gap-y-3">
                            @foreach($tab['links'] as $link)
                                <a href="{{ $link['url'] }}" class="group flex items-center justify-between gap-3 text-sm font-semibold text-slate-600 hover:text-amra-primary transition-colors {{ $linkClass }}">
                                    <span>{{ $link['label'] }}</span>
                                    <i data-lucide="chevron-right" class="w-3.5 h-3.5 text-slate-300 group-hover:text-amra-primary transition-colors"></i>
                                </a>
                            @endforeach
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    @once
        <script>
            document.querySelectorAll('.home-link-tab').forEach((tab) => {
                tab.addEventListener('click', () => {
                    const targetId = tab.dataset.target;
                    const section = tab.closest('[data-property-links-section]') || document;

                    section.querySelectorAll('.home-link-tab').forEach((button) => {
                        const isActive = button === tab;
                        button.setAttribute('aria-selected', isActive ? 'true' : 'false');
                        button.classList.toggle('text-slate-950', isActive);
                        button.classList.toggle('text-slate-500', !isActive);
                        button.querySelector('.home-link-tab-line')?.classList.toggle('hidden', !isActive);
                    });

                    section.querySelectorAll('.home-link-panel').forEach((panel) => {
                        panel.classList.toggle('hidden', panel.id !== targetId);
                    });
                });
            });
        </script>
    @endonce
@endif
