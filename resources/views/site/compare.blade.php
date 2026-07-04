@extends('layouts.site')

@section('title', 'Compare Properties - Amra Property')
@section('meta_description', 'Compare Amra Property listings by price, average price per square foot, possession, size, RERA status, amenities and location.')

@section('content')
<div class="pt-32 pb-24 bg-slate-50 min-h-screen">
    <div class="max-w-7xl mx-auto px-4 md:px-6">
        <div class="mb-10 text-left">
            <p class="text-amra-primary font-bold uppercase tracking-[0.25em] text-xs mb-3">Project Comparison</p>
            <h1 class="text-3xl sm:text-4xl md:text-5xl font-serif font-extrabold text-slate-900 leading-tight">Compare Properties</h1>
            <p class="text-slate-500 text-sm mt-2">Core buying signals, pricing, layout options, and project specifications side-by-side.</p>
        </div>

        @if($properties->isEmpty())
            <div class="bg-white border border-slate-100 rounded-3xl p-10 text-center shadow-sm max-w-xl mx-auto">
                <i data-lucide="columns-3" class="w-12 h-12 text-amra-primary mx-auto mb-4 animate-pulse"></i>
                <h2 class="text-xl font-serif font-extrabold text-slate-900 mb-2">No properties selected</h2>
                <p class="text-sm text-slate-500 mb-6">Use the Compare buttons on property cards to build your comparison.</p>
                <a href="{{ route('site.property') }}" class="inline-flex bg-amra-primary hover:bg-teal-600 text-white px-6 py-3 rounded-xl text-sm font-bold shadow-sm transition-colors">Browse Properties</a>
            </div>
        @else
            <!-- Horizontal Scroll Cards Track -->
            <div class="flex flex-row overflow-x-auto gap-4 items-stretch mb-12 pb-4 snap-x snap-mandatory scrollbar-thin">
                @foreach($properties as $property)
                    <!-- Comparison Card -->
                    <div class="bg-white rounded-3xl border border-slate-100 shadow-sm flex flex-col p-4 relative group hover:shadow-md transition-shadow min-w-[310px] w-[310px] snap-start shrink-0">
                        <!-- Image Container with Delete Button -->
                        <div class="relative h-32 rounded-2xl overflow-hidden mb-3 shrink-0 bg-slate-100">
                            <img src="{{ $property->featured_image ? asset($property->featured_image) : asset('assets/images/clean_hero.png') }}" alt="{{ $property->title }}" class="w-full h-full object-cover card-zoom-img group-hover:scale-105 transition-transform duration-500">
                            <button type="button" onclick="removeCompare('{{ $property->id }}')" class="absolute top-2 right-2 w-7 h-7 bg-slate-900/60 hover:bg-slate-950 text-white rounded-full flex items-center justify-center backdrop-blur-sm transition-colors shadow-md z-20" aria-label="Remove property">
                                <i data-lucide="x" class="w-4 h-4"></i>
                            </button>
                        </div>

                        <!-- Content Details -->
                        <div class="flex flex-col flex-grow text-left">
                            <p class="text-[9px] font-bold text-teal-600 uppercase tracking-wider mb-0.5">
                                {{ $property->formatted_possession ?: 'Ready to Move' }}
                            </p>
                            <h3 class="font-serif font-extrabold text-slate-800 text-base mb-0.5 line-clamp-1 leading-snug">
                                <a href="{{ route('site.property.show', $property->slug) }}" class="hover:text-teal-600 transition-colors">{{ $property->title }}</a>
                            </h3>
                            <p class="text-xs text-slate-400 font-semibold mb-3 line-clamp-1 flex items-center gap-1">
                                <i data-lucide="map-pin" class="w-3.5 h-3.5 text-slate-400"></i>
                                {{ $property->city }}
                            </p>

                            <div class="h-px bg-slate-100 w-full mb-3"></div>

                            <!-- BHK Type Display -->
                            <div class="mb-2 text-left">
                                <p class="text-[9px] text-slate-400 font-bold uppercase tracking-wider mb-0.5">BHK Type</p>
                                <div class="w-full bg-slate-50 border border-slate-100 rounded-xl px-3 py-1.5 text-xs font-bold text-slate-700">
                                    {{ $property->configuration ?: 'Apartment' }}
                                </div>
                            </div>

                            <!-- Carpet Size Display -->
                            <div class="mb-2 text-left">
                                <p class="text-[9px] text-slate-400 font-bold uppercase tracking-wider mb-0.5">Carpet Size</p>
                                <div class="w-full bg-slate-50 border border-slate-100 rounded-xl px-3 py-1.5 text-xs font-bold text-slate-700">
                                    {{ $property->area ? number_format($property->area) . ' ' . str_replace('_', ' ', $property->area_unit) : 'N/A' }}
                                </div>
                            </div>

                            <!-- RERA Status Display -->
                            <div class="mb-2 text-left">
                                <p class="text-[9px] text-slate-400 font-bold uppercase tracking-wider mb-0.5">RERA Status</p>
                                <div class="w-full bg-slate-50 border border-slate-100 rounded-xl px-3 py-1.5 text-xs font-bold {{ $property->is_rera_approved ? 'text-emerald-600' : 'text-amber-600' }}">
                                    {{ $property->is_rera_approved ? 'Approved' : 'Not shown' }}
                                </div>
                            </div>

                            <!-- Average Price Display (If exists) -->
                            @if($property->formatted_avg_price)
                                <div class="mb-2 text-left">
                                    <p class="text-[9px] text-slate-400 font-bold uppercase tracking-wider mb-0.5">Avg. Rate</p>
                                    <div class="w-full bg-slate-50 border border-slate-100 rounded-xl px-3 py-1.5 text-xs font-bold text-slate-700">
                                        {{ $property->formatted_avg_price }}
                                    </div>
                                </div>
                            @endif

                            <!-- Key Amenities Display -->
                            <div class="mb-3 text-left">
                                <p class="text-[9px] text-slate-400 font-bold uppercase tracking-wider mb-0.5">Key Amenities</p>
                                <div class="flex flex-wrap gap-1">
                                    @if(is_array($property->amenities) && count($property->amenities))
                                        @foreach(array_slice($property->amenities, 0, 2) as $amenity)
                                            <span class="bg-slate-50 border border-slate-100 text-[10px] font-bold text-slate-600 px-2 py-1 rounded-lg">{{ $amenity }}</span>
                                        @endforeach
                                        @if(count($property->amenities) > 2)
                                            <span class="bg-slate-100 text-[9px] font-extrabold text-slate-500 px-1.5 py-1 rounded-lg">+{{ count($property->amenities) - 2 }}</span>
                                        @endif
                                    @else
                                        <span class="text-xs text-slate-400 font-medium pl-1">None specified</span>
                                    @endif
                                </div>
                            </div>

                            <div class="flex-grow"></div>

                            <!-- Price & Action Trigger -->
                            <div class="mt-2 pt-2 border-t border-slate-100 text-left">
                                <p class="text-[9px] text-slate-400 font-bold uppercase tracking-wider mb-0.5">Asking Price</p>
                                <h4 class="text-xl font-serif font-extrabold text-teal-600 mb-2">{{ $property->formatted_price }}</h4>
                                <button type="button" 
                                    data-endpoint="{{ route('site.property.enquiry', $property) }}" 
                                    data-property-title="{{ $property->title }}"
                                    data-action="whatsapp"
                                    class="property-contact-trigger w-full bg-white hover:bg-teal-500 text-teal-600 hover:text-white border border-teal-200 hover:border-teal-500 py-2 rounded-xl font-bold text-xs transition-all active:scale-95 flex items-center justify-center gap-1.5 shadow-sm">
                                    <i data-lucide="phone" class="w-3.5 h-3.5"></i> Contact
                                </button>
                            </div>
                        </div>
                    </div>
                @endforeach

                <!-- Add another project Card -->
                <button type="button" onclick="openAddPropertyModal()" class="bg-slate-50 rounded-3xl border-2 border-dashed border-slate-200 hover:border-teal-400 hover:bg-teal-50/10 transition-all flex flex-col items-center justify-center p-6 text-center group cursor-pointer min-h-[340px] min-w-[310px] w-[310px] snap-start shrink-0">
                    <div class="w-12 h-12 rounded-full bg-slate-100 group-hover:bg-teal-50 text-slate-400 group-hover:text-teal-600 flex items-center justify-center mb-4 transition-colors">
                        <i data-lucide="plus" class="w-6 h-6"></i>
                    </div>
                    <p class="text-sm font-bold text-slate-500 group-hover:text-teal-600 tracking-wide">+ Add another project</p>
                </button>
            </div>
        @endif
    </div>
</div>

<!-- Add Property Search Select Modal -->
<div id="add-property-modal" class="fixed inset-0 z-[130] hidden items-center justify-center bg-slate-950/60 px-4 py-6 backdrop-blur-sm">
    <div class="w-full max-w-md rounded-3xl bg-white p-6 shadow-2xl relative text-left">
        <div class="mb-5 flex items-start justify-between gap-4">
            <div>
                <p class="text-[10px] font-bold uppercase tracking-[0.25em] text-teal-500 font-sans">Compare Listings</p>
                <h3 class="mt-2 text-xl font-serif font-extrabold leading-snug text-slate-900">Add Property to Compare</h3>
                <p class="mt-2 text-xs leading-relaxed text-slate-500 font-medium">Search and select properties to compare details side-by-side.</p>
            </div>
            <button type="button" onclick="closeAddPropertyModal()" class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full bg-slate-100 text-slate-500 hover:bg-slate-200">
                <i data-lucide="x" class="h-4 w-4"></i>
            </button>
        </div>

        <input type="text" id="modal-property-search" oninput="filterModalProperties()" placeholder="Search property name, city, BHK..." class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-xs outline-none focus:border-teal-500 focus:bg-white mb-4 font-semibold text-slate-800">
        
        <div id="modal-property-list" class="overflow-y-auto max-h-[300px] divide-y divide-slate-100 pr-1 selectbar-thin">
            @forelse($selectableProperties as $select)
                <div onclick="addCompare('{{ $select->id }}')" data-property-id="{{ $select->id }}" data-property-title="{{ strtolower($select->title) }} {{ strtolower($select->city) }} {{ strtolower($select->configuration) }}" class="modal-selectable-item py-3 px-1 cursor-pointer hover:bg-slate-50 transition-colors flex items-center justify-between rounded-lg">
                    <div class="flex-grow min-w-0 pr-4">
                        <p class="text-xs font-bold text-slate-800 truncate">{{ $select->title }}</p>
                        <p class="text-[9px] font-bold text-slate-400 mt-0.5 truncate uppercase tracking-wider">{{ $select->city }} • {{ $select->configuration ?: 'Apartment' }}</p>
                    </div>
                    <span class="text-teal-600 text-[10px] font-bold uppercase tracking-wider shrink-0 flex items-center gap-1">
                        <i data-lucide="plus" class="w-3.5 h-3.5"></i> Add
                    </span>
                </div>
            @empty
                <div class="py-12 text-center text-xs font-semibold text-slate-400">
                    No other properties available
                </div>
            @endforelse
        </div>
    </div>
</div>

<!-- Enquiry Contact Modal (Unification) -->
<div id="property-contact-modal" class="fixed inset-0 z-[120] hidden items-center justify-center bg-slate-950/60 px-4 py-6 backdrop-blur-sm">
    <div class="w-full max-w-md rounded-3xl bg-white p-6 shadow-2xl relative text-left">
        <div class="mb-5 flex items-start justify-between gap-4">
            <div>
                <p class="text-[10px] font-bold uppercase tracking-[0.25em] text-teal-500">Unlock Contact</p>
                <h3 id="contact-modal-title" class="mt-2 text-xl font-serif font-extrabold leading-snug text-slate-900">Get property details</h3>
                <p class="mt-2 text-xs leading-relaxed text-slate-500">Share your details once. We will save your enquiry and reveal the seller contact.</p>
            </div>
            <button type="button" id="contact-modal-close" class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full bg-slate-100 text-slate-500 hover:bg-slate-200">
                <i data-lucide="x" class="h-4 w-4"></i>
            </button>
        </div>

        <form id="property-contact-form" class="space-y-3">
            @csrf
            <input type="hidden" id="contact-action" value="whatsapp">
            <input type="hidden" id="contact-endpoint" value="">
            <label class="block">
                <span class="mb-1 block text-[10px] font-bold uppercase tracking-wider text-slate-500">Name</span>
                <input id="contact-name" type="text" required class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm font-semibold outline-none focus:border-teal-500 focus:bg-white" placeholder="Your name">
            </label>
            <label class="block">
                <span class="mb-1 block text-[10px] font-bold uppercase tracking-wider text-slate-500">Phone</span>
                <input id="contact-phone" type="tel" required class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm font-semibold outline-none focus:border-teal-500 focus:bg-white" placeholder="Your phone number">
            </label>
            <label class="block">
                <span class="mb-1 block text-[10px] font-bold uppercase tracking-wider text-slate-500">Email</span>
                <input id="contact-email" type="email" class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm font-semibold outline-none focus:border-teal-500 focus:bg-white" placeholder="Optional">
            </label>
            <button id="contact-submit" type="submit" class="flex w-full items-center justify-center gap-2 rounded-xl bg-teal-500 px-5 py-3.5 text-sm font-extrabold text-white transition-all hover:bg-teal-600">
                <i data-lucide="send" class="h-4 w-4"></i>
                Save Enquiry & Show Contact
            </button>
        </form>

        <div id="contact-unlocked" class="mt-4 hidden rounded-2xl border border-emerald-100 bg-emerald-50 p-4">
            <p class="mb-3 text-xs font-bold text-emerald-800">Contact unlocked</p>
            <div class="flex flex-col gap-2 sm:flex-row">
                <a id="contact-unlocked-phone" href="#" class="flex flex-1 items-center justify-center gap-2 rounded-xl bg-white px-4 py-3 text-xs font-bold text-emerald-700">
                    <i data-lucide="phone" class="h-4 w-4"></i><span></span>
                </a>
                <a id="contact-unlocked-whatsapp" href="#" target="_blank" rel="noopener noreferrer" class="flex flex-1 items-center justify-center gap-2 rounded-xl bg-emerald-500 px-4 py-3 text-xs font-bold text-white">
                    <i data-lucide="send" class="h-4 w-4"></i> WhatsApp
                </a>
            </div>
        </div>
    </div>
</div>

<script>
    function openAddPropertyModal() {
        const modal = document.getElementById('add-property-modal');
        if (modal) {
            modal.classList.remove('hidden');
            modal.classList.add('flex');
            document.body.classList.add('overflow-hidden');
            setTimeout(() => document.getElementById('modal-property-search').focus(), 150);
            if (window.lucide) window.lucide.createIcons();
        }
    }

    function closeAddPropertyModal() {
        const modal = document.getElementById('add-property-modal');
        if (modal) {
            modal.classList.add('hidden');
            modal.classList.remove('flex');
            document.body.classList.remove('overflow-hidden');
        }
    }

    function filterModalProperties() {
        const query = document.getElementById('modal-property-search').value.toLowerCase();
        document.querySelectorAll('.modal-selectable-item').forEach(item => {
            const searchPayload = item.dataset.propertyTitle;
            if (searchPayload.includes(query)) {
                item.classList.remove('hidden');
            } else {
                item.classList.add('hidden');
            }
        });
    }

    function addCompare(id) {
        const selected = JSON.parse(localStorage.getItem('amra_compare_properties') || '[]');
        if (selected.length >= 20) {
            alert('You can compare up to 20 properties at a time.');
            return;
        }
        const idStr = String(id);
        if (!selected.includes(idStr)) {
            selected.push(idStr);
        }
        localStorage.setItem('amra_compare_properties', JSON.stringify(selected));
        const query = selected.map(item => `properties[]=${encodeURIComponent(item)}`).join('&');
        window.location.href = `{{ route('site.compare') }}?${query}`;
    }

    function removeCompare(id) {
        const selected = JSON.parse(localStorage.getItem('amra_compare_properties') || '[]');
        const filtered = selected.filter(item => String(item) !== String(id));
        localStorage.setItem('amra_compare_properties', JSON.stringify(filtered));
        
        if (filtered.length === 0) {
            window.location.href = '{{ route('site.property') }}';
        } else {
            const query = filtered.map(item => `properties[]=${encodeURIComponent(item)}`).join('&');
            window.location.href = `{{ route('site.compare') }}?${query}`;
        }
    }

    const contactModal = document.getElementById('property-contact-modal');
    const contactModalClose = document.getElementById('contact-modal-close');
    const contactForm = document.getElementById('property-contact-form');
    const contactAction = document.getElementById('contact-action');
    const contactEndpoint = document.getElementById('contact-endpoint');
    const contactTitle = document.getElementById('contact-modal-title');
    const contactSubmit = document.getElementById('contact-submit');
    const contactUnlocked = document.getElementById('contact-unlocked');
    const contactUnlockedPhone = document.getElementById('contact-unlocked-phone');
    const contactUnlockedWhatsapp = document.getElementById('contact-unlocked-whatsapp');

    function closeContactModal() {
        contactModal.classList.add('hidden');
        contactModal.classList.remove('flex');
        document.body.classList.remove('overflow-hidden');
    }

    document.addEventListener('DOMContentLoaded', () => {
        const savedName = localStorage.getItem('amra_enquiry_name');
        const savedPhone = localStorage.getItem('amra_enquiry_phone');
        const savedEmail = localStorage.getItem('amra_enquiry_email');

        if (savedName && savedPhone) {
            if (document.getElementById('contact-name')) document.getElementById('contact-name').value = savedName;
            if (document.getElementById('contact-phone')) document.getElementById('contact-phone').value = savedPhone;
            if (savedEmail && document.getElementById('contact-email')) document.getElementById('contact-email').value = savedEmail;
        }
    });

    document.querySelectorAll('.property-contact-trigger').forEach((button) => {
        button.addEventListener('click', () => {
            contactAction.value = button.dataset.action || 'whatsapp';
            contactEndpoint.value = button.dataset.endpoint;
            contactTitle.textContent = button.dataset.propertyTitle || 'Get property details';
            contactUnlocked.classList.add('hidden');
            contactSubmit.disabled = false;
            contactSubmit.innerHTML = '<i data-lucide="send" class="h-4 w-4"></i> Save Enquiry & Show Contact';
            contactModal.classList.remove('hidden');
            contactModal.classList.add('flex');
            document.body.classList.add('overflow-hidden');
            if (window.lucide) window.lucide.createIcons();

            // Auto-submit if pre-filled details exist
            const savedName = localStorage.getItem('amra_enquiry_name');
            const savedPhone = localStorage.getItem('amra_enquiry_phone');
            if (savedName && savedPhone) {
                contactForm.dispatchEvent(new Event('submit', { cancelable: true }));
            }
        });
    });

    if (contactModalClose) contactModalClose.addEventListener('click', closeContactModal);
    if (contactModal) {
        contactModal.addEventListener('click', (event) => {
            if (event.target === contactModal) closeContactModal();
        });
    }

    if (contactForm) {
        contactForm.addEventListener('submit', (event) => {
            event.preventDefault();

            contactSubmit.disabled = true;
            contactSubmit.textContent = 'Saving...';

            const action = contactAction.value || 'whatsapp';
            const nameVal = document.getElementById('contact-name').value;
            const phoneVal = document.getElementById('contact-phone').value;
            const emailVal = document.getElementById('contact-email').value;

            fetch(contactEndpoint.value, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                },
                body: JSON.stringify({
                    name: nameVal,
                    phone: phoneVal,
                    email: emailVal,
                    source: `${action}_listing_card`,
                    intent: action,
                }),
            })
                .then((response) => response.json())
                .then((data) => {
                    if (!data.success) throw new Error(data.message || 'Unable to save enquiry.');

                    localStorage.setItem('amra_enquiry_name', nameVal);
                    localStorage.setItem('amra_enquiry_phone', phoneVal);
                    localStorage.setItem('amra_enquiry_email', emailVal);

                    contactUnlockedPhone.href = `tel:${data.phone}`;
                    contactUnlockedPhone.querySelector('span').textContent = data.phone;
                    contactUnlockedWhatsapp.href = data.whatsapp_url;

                    contactSubmit.disabled = false;
                    contactSubmit.innerHTML = '<i data-lucide="check" class="h-4 w-4"></i> Saved Successfully';
                    contactUnlocked.classList.remove('hidden');
                    if (window.lucide) window.lucide.createIcons();
                })
                .catch((err) => {
                    contactSubmit.disabled = false;
                    contactSubmit.innerHTML = '<i data-lucide="send" class="h-4 w-4"></i> Save Enquiry & Show Contact';
                    alert(err.message || 'Something went wrong.');
                });
        });
    }
</script>
@endsection
