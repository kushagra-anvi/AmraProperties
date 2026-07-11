@extends('layouts.crm')

@section('title', 'Edit Property')

@section('styles')
<link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
<style>
    .ql-toolbar.ql-snow {
        border-color: #e2e8f0 !important;
        background-color: #f8fafc !important;
        border-top-left-radius: 0.75rem;
        border-top-right-radius: 0.75rem;
    }
    .ql-container.ql-snow {
        border-color: #e2e8f0 !important;
        background-color: #ffffff !important;
        border-bottom-left-radius: 0.75rem;
        border-bottom-right-radius: 0.75rem;
        color: #0f172a !important;
        font-family: inherit !important;
        font-size: 0.875rem !important;
        height: auto !important;
    }
    .ql-editor {
        min-height: 200px !important;
    }
</style>
@endsection

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    <!-- Back Link -->
    <a href="{{ route('crm.properties.index') }}" class="inline-flex items-center gap-1.5 text-xs text-slate-400 hover:text-white transition-colors">
        <i data-lucide="arrow-left" class="w-3.5 h-3.5"></i> Back to Properties Directory
    </a>

    <!-- Header Block -->
    <div>
        <h1 class="text-3xl font-serif font-extrabold text-white">Edit Property Listing</h1>
        <p class="text-sm text-slate-400 mt-1">Modify details for the property listing: {{ $property->title }}.</p>
    </div>

    <!-- Form Card -->
    <div class="bg-slate-900 border border-slate-800 rounded-3xl p-6 md:p-8 shadow-md">
        <form action="{{ route('crm.properties.update', $property->id) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf
            @method('PUT')

            <!-- Form Error alert -->
            @if ($errors->any())
                <div class="p-4 bg-rose-500/10 border border-rose-500/20 text-rose-300 text-xs rounded-2xl flex flex-col gap-1.5">
                    @foreach ($errors->all() as $error)
                        <div class="flex items-start gap-2">
                            <i data-lucide="alert-triangle" class="w-4 h-4 shrink-0 mt-0.5"></i>
                            <span>{{ $error }}</span>
                        </div>
                    @endforeach
                </div>
            @endif

            <!-- Section: General Info -->
            <div class="space-y-4">
                <h3 class="text-xs font-bold text-slate-400 uppercase tracking-widest border-b border-slate-800 pb-2">Property Details</h3>
                
                <div class="grid gap-6 md:grid-cols-2">
                    <!-- Title -->
                    <div class="space-y-1.5 md:col-span-2">
                        <label for="title" class="text-xs font-bold text-slate-400">Property Title <span class="text-rose-500">*</span></label>
                        <input id="title" name="title" type="text" required value="{{ old('title', $property->title) }}" placeholder="e.g. 3BHK Premium Sea View Apartment"
                            class="w-full bg-slate-950 border border-slate-800 focus:border-amra-primary rounded-xl px-4 py-3 outline-none text-sm text-slate-250 placeholder-slate-650">
                    </div>

                    <!-- Price (INR) -->
                    <div class="space-y-1.5">
                        <label for="price" class="text-xs font-bold text-slate-400">Price (INR) <span class="text-rose-500">*</span></label>
                        <input id="price" name="price" type="number" required value="{{ old('price', $property->price) }}" placeholder="e.g. 15000000"
                            class="w-full bg-slate-950 border border-slate-800 focus:border-amra-primary rounded-xl px-4 py-3 outline-none text-sm text-slate-250 placeholder-slate-650">
                    </div>

                    <div class="space-y-1.5">
                        <label for="avg_price_per_sqft" class="text-xs font-bold text-slate-400">Avg. Price / sq.ft</label>
                        <input id="avg_price_per_sqft" name="avg_price_per_sqft" type="number" step="0.01" value="{{ old('avg_price_per_sqft', $property->avg_price_per_sqft) }}" placeholder="e.g. 10230"
                            class="w-full bg-slate-950 border border-slate-800 focus:border-amra-primary rounded-xl px-4 py-3 outline-none text-sm text-slate-250 placeholder-slate-650">
                    </div>

                    <div class="space-y-1.5">
                        <label for="possession_date" class="text-xs font-bold text-slate-400">Possession Date</label>
                        <input id="possession_date" name="possession_date" type="date" value="{{ old('possession_date', optional($property->possession_date)->format('Y-m-d')) }}"
                            class="w-full bg-slate-950 border border-slate-800 focus:border-amra-primary rounded-xl px-4 py-3 outline-none text-sm text-slate-250">
                    </div>

                    <div class="space-y-1.5">
                        <label for="possession_status" class="text-xs font-bold text-slate-400">Possession Status</label>
                        <select id="possession_status" name="possession_status"
                            class="w-full bg-slate-950 border border-slate-800 focus:border-amra-primary rounded-xl px-4 py-3 outline-none text-sm text-slate-250">
                            @php($selectedPossessionStatus = old('possession_status', $property->possession_status))
                            <option value="" {{ $selectedPossessionStatus ? '' : 'selected' }}>Select possession status</option>
                            <option value="Ready to Move" {{ $selectedPossessionStatus === 'Ready to Move' ? 'selected' : '' }}>Ready to Move</option>
                            <option value="Under Construction" {{ $selectedPossessionStatus === 'Under Construction' ? 'selected' : '' }}>Under Construction</option>
                        </select>
                    </div>

                    <!-- Configuration -->
                    <div class="space-y-1.5">
                        <label for="configuration" class="text-xs font-bold text-slate-400">Configuration <span class="text-rose-500">*</span></label>
                        <select id="configuration" name="configuration" required class="w-full bg-slate-950 border border-slate-800 focus:border-amra-primary rounded-xl px-4 py-3 outline-none text-sm text-slate-250">
                            <option value="1BHK" {{ old('configuration', $property->configuration) === '1BHK' ? 'selected' : '' }}>1 BHK Flat</option>
                            <option value="2BHK" {{ old('configuration', $property->configuration) === '2BHK' ? 'selected' : '' }}>2 BHK Flat</option>
                            <option value="3BHK" {{ old('configuration', $property->configuration) === '3BHK' ? 'selected' : '' }}>3 BHK Flat</option>
                            <option value="4BHK" {{ old('configuration', $property->configuration) === '4BHK' ? 'selected' : '' }}>4 BHK Flat</option>
                            <option value="Villa" {{ old('configuration', $property->configuration) === 'Villa' ? 'selected' : '' }}>Villa / House</option>
                            <option value="Plot" {{ old('configuration', $property->configuration) === 'Plot' ? 'selected' : '' }}>Plot / Land</option>
                        </select>
                    </div>

                    <!-- Bedrooms -->
                    <div class="space-y-1.5">
                        <label for="bedrooms" class="text-xs font-bold text-slate-400">Bedrooms</label>
                        <input id="bedrooms" name="bedrooms" type="number" value="{{ old('bedrooms', $property->bedrooms) }}" placeholder="e.g. 3"
                            class="w-full bg-slate-950 border border-slate-800 focus:border-amra-primary rounded-xl px-4 py-3 outline-none text-sm text-slate-250">
                    </div>

                    <!-- Bathrooms -->
                    <div class="space-y-1.5">
                        <label for="bathrooms" class="text-xs font-bold text-slate-400">Bathrooms</label>
                        <input id="bathrooms" name="bathrooms" type="number" value="{{ old('bathrooms', $property->bathrooms) }}" placeholder="e.g. 3"
                            class="w-full bg-slate-950 border border-slate-800 focus:border-amra-primary rounded-xl px-4 py-3 outline-none text-sm text-slate-250">
                    </div>

                    <!-- Area -->
                    <div class="space-y-1.5">
                        <label for="area" class="text-xs font-bold text-slate-400">Area</label>
                        <input id="area" name="area" type="number" value="{{ old('area', $property->area) }}" placeholder="e.g. 1800"
                            class="w-full bg-slate-950 border border-slate-800 focus:border-amra-primary rounded-xl px-4 py-3 outline-none text-sm text-slate-250">
                    </div>

                    <!-- Area Unit -->
                    <div class="space-y-1.5">
                        <label for="area_unit" class="text-xs font-bold text-slate-400">Area Unit <span class="text-rose-500">*</span></label>
                        <select id="area_unit" name="area_unit" required class="w-full bg-slate-950 border border-slate-800 focus:border-amra-primary rounded-xl px-4 py-3 outline-none text-sm text-slate-250">
                            <option value="sq_ft" {{ old('area_unit', $property->area_unit) === 'sq_ft' ? 'selected' : '' }}>Square Feet (sq_ft)</option>
                            <option value="sq_mt" {{ old('area_unit', $property->area_unit) === 'sq_mt' ? 'selected' : '' }}>Square Meters (sq_mt)</option>
                            <option value="sq_yd" {{ old('area_unit', $property->area_unit) === 'sq_yd' ? 'selected' : '' }}>Square Yards (sq_yd)</option>
                            <option value="acre" {{ old('area_unit', $property->area_unit) === 'acre' ? 'selected' : '' }}>Acre</option>
                        </select>
                    </div>

                    <!-- Developer Name -->
                    <div class="space-y-1.5">
                        <label for="developer_name" class="text-xs font-bold text-slate-400">Developer / Builder Name</label>
                        <input id="developer_name" name="developer_name" type="text" value="{{ old('developer_name', $property->developer_name) }}" placeholder="e.g. Hiranandani Developers"
                            class="w-full bg-slate-950 border border-slate-800 focus:border-amra-primary rounded-xl px-4 py-3 outline-none text-sm text-slate-250">
                    </div>

                    <div class="space-y-1.5">
                        <label for="partner_id" class="text-xs font-bold text-slate-400">Seller / Partner Owner</label>
                        <select id="partner_id" name="partner_id" class="w-full bg-slate-950 border border-slate-800 focus:border-amra-primary rounded-xl px-4 py-3 outline-none text-sm text-slate-250">
                            <option value="">Amra Concierge / Direct Listing</option>
                            @foreach (($partners ?? collect()) as $partner)
                                <option value="{{ $partner->id }}" {{ (string) old('partner_id', $property->partner_id) === (string) $partner->id ? 'selected' : '' }}>{{ $partner->company_name }} ({{ ucfirst($partner->type) }})</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="space-y-1.5 md:col-span-2">
                        <label class="text-xs font-bold text-slate-400 block mb-2">Assigned Co-agents / Partners (Enquiries will be randomly routed among them)</label>
                        <div class="grid gap-4 sm:grid-cols-2 md:grid-cols-3">
                            @foreach (($partners ?? collect()) as $partner)
                                <label class="flex items-center gap-2 rounded-lg border border-slate-850 p-3 text-xs text-slate-200 cursor-pointer hover:bg-slate-850/20 transition-colors">
                                    <input type="checkbox" name="partners[]" value="{{ $partner->id }}" 
                                        class="rounded bg-slate-950 border-slate-800 text-teal-500 focus:ring-teal-500/20"
                                        {{ is_array(old('partners', $property->partners->pluck('id')->toArray())) && in_array($partner->id, old('partners', $property->partners->pluck('id')->toArray())) ? 'checked' : '' }}>
                                    {{ $partner->company_name }} ({{ ucfirst($partner->type) }})
                                </label>
                            @endforeach
                        </div>
                    </div>

                    <div class="space-y-1.5 md:col-span-2">
                        <label class="text-xs font-bold text-slate-400 block mb-2">Property Tags</label>
                        <div class="grid gap-3 sm:grid-cols-2 md:grid-cols-3">
                            @php($selectedTagIds = old('tag_ids', $property->tags->pluck('id')->toArray()))
                            @foreach (($tags ?? collect()) as $tag)
                                <label class="flex items-center gap-2 rounded-lg border border-slate-850 p-3 text-xs text-slate-200 cursor-pointer hover:bg-slate-850/20 transition-colors">
                                    <input type="checkbox" name="tag_ids[]" value="{{ $tag->id }}"
                                        class="rounded bg-slate-950 border-slate-800 text-teal-500 focus:ring-teal-500/20"
                                        {{ is_array($selectedTagIds) && in_array($tag->id, $selectedTagIds) ? 'checked' : '' }}>
                                    {{ $tag->name }}
                                </label>
                            @endforeach
                        </div>
                        <div class="mt-4">
                            <label for="custom_tags" class="text-xs font-bold text-slate-400 block mb-1.5">Custom Tags</label>
                            <input id="custom_tags" name="custom_tags" type="text" value="{{ old('custom_tags') }}" placeholder="e.g. Sea View, Corner Plot, Near Airport"
                                class="w-full bg-slate-950 border border-slate-800 focus:border-amra-primary rounded-xl px-4 py-3 outline-none text-sm text-slate-250 placeholder-slate-650">
                            <p class="mt-1.5 text-[10px] text-slate-500">Separate tags with commas. New tags will be saved automatically.</p>
                        </div>
                    </div>

                    <div class="space-y-1.5">
                        <label for="contact_phone" class="text-xs font-bold text-slate-400">Contact Phone</label>
                        <input id="contact_phone" name="contact_phone" type="text" value="{{ old('contact_phone', $property->contact_phone) }}" placeholder="+91 9559992958"
                            class="w-full bg-slate-950 border border-slate-800 focus:border-amra-primary rounded-xl px-4 py-3 outline-none text-sm text-slate-250">
                    </div>

                    <div class="space-y-1.5">
                        <label for="contact_whatsapp" class="text-xs font-bold text-slate-400">WhatsApp Number</label>
                        <input id="contact_whatsapp" name="contact_whatsapp" type="text" value="{{ old('contact_whatsapp', $property->contact_whatsapp) }}" placeholder="+91 9559992958"
                            class="w-full bg-slate-950 border border-slate-800 focus:border-amra-primary rounded-xl px-4 py-3 outline-none text-sm text-slate-250">
                    </div>

                    <!-- RERA Number -->
                    <div class="space-y-1.5">
                        <label for="rera_number" class="text-xs font-bold text-slate-400">RERA Number</label>
                        <input id="rera_number" name="rera_number" type="text" value="{{ old('rera_number', $property->rera_number) }}" placeholder="e.g. P51800001234"
                            class="w-full bg-slate-950 border border-slate-800 focus:border-amra-primary rounded-xl px-4 py-3 outline-none text-sm text-slate-250">
                    </div>

                    <!-- City -->
                    <div class="space-y-1.5">
                        <label for="city" class="text-xs font-bold text-slate-400">City <span class="text-rose-500">*</span></label>
                        <input id="city" name="city" type="text" list="property-city-options" required value="{{ old('city', $property->city) }}" placeholder="e.g. Thane, Navi Mumbai, Lucknow"
                            class="w-full bg-slate-950 border border-slate-800 focus:border-amra-primary rounded-xl px-4 py-3 outline-none text-sm text-slate-250 placeholder-slate-650">
                        <datalist id="property-city-options">
                            <option value="Mumbai">
                            <option value="Navi Mumbai">
                            <option value="Thane">
                            <option value="Panvel">
                            <option value="Dombivli">
                            <option value="Lucknow">
                            <option value="Jaipur">
                            <option value="Dubai">
                            <option value="Nashik">
                            <option value="Varanasi">
                        </datalist>
                    </div>

                    <!-- State -->
                    <div class="space-y-1.5">
                        <label for="state" class="text-xs font-bold text-slate-400">State</label>
                        <input id="state" name="state" type="text" value="{{ old('state', $property->state) }}" placeholder="e.g. Maharashtra"
                            class="w-full bg-slate-950 border border-slate-800 focus:border-amra-primary rounded-xl px-4 py-3 outline-none text-sm text-slate-250">
                    </div>

                    <!-- Status -->
                    <div class="space-y-1.5">
                        <label for="status" class="text-xs font-bold text-slate-400">Publish Status <span class="text-rose-500">*</span></label>
                        <select id="status" name="status" required class="w-full bg-slate-950 border border-slate-800 focus:border-amra-primary rounded-xl px-4 py-3 outline-none text-sm text-slate-250">
                            <option value="publish" {{ old('status', $property->status) === 'publish' ? 'selected' : '' }}>Published (Visible on site)</option>
                            <option value="draft" {{ old('status', $property->status) === 'draft' ? 'selected' : '' }}>Draft (Hidden)</option>
                        </select>
                    </div>

                    <!-- Listing Type -->
                    <div class="space-y-1.5">
                        <label for="listing_type" class="text-xs font-bold text-slate-400">Listing Type <span class="text-rose-500">*</span></label>
                        <select id="listing_type" name="listing_type" required class="w-full bg-slate-950 border border-slate-800 focus:border-amra-primary rounded-xl px-4 py-3 outline-none text-sm text-slate-250">
                            <option value="sale" {{ old('listing_type', $property->listing_type) === 'sale' ? 'selected' : '' }}>For Sale</option>
                            <option value="rent" {{ old('listing_type', $property->listing_type) === 'rent' ? 'selected' : '' }}>For Rent</option>
                        </select>
                    </div>

                    <!-- Coordinates -->
                    <div class="grid grid-cols-2 gap-4">
                        <div class="space-y-1.5">
                            <label for="latitude" class="text-xs font-bold text-slate-400">Latitude</label>
                            <input id="latitude" name="latitude" type="number" step="0.00000001" value="{{ old('latitude', $property->latitude) }}" placeholder="e.g. 19.117"
                                class="w-full bg-slate-950 border border-slate-800 focus:border-amra-primary rounded-xl px-3 py-3 outline-none text-sm text-slate-250">
                        </div>
                        <div class="space-y-1.5">
                            <label for="longitude" class="text-xs font-bold text-slate-400">Longitude</label>
                            <input id="longitude" name="longitude" type="number" step="0.00000001" value="{{ old('longitude', $property->longitude) }}" placeholder="e.g. 72.906"
                                class="w-full bg-slate-950 border border-slate-800 focus:border-amra-primary rounded-xl px-3 py-3 outline-none text-sm text-slate-250">
                        </div>
                    </div>

                    <!-- Featured Image -->
                    <div class="space-y-1.5 md:col-span-2 grid md:grid-cols-2 gap-4">
                        <div>
                            <label for="featured_image" class="text-xs font-bold text-slate-400">Upload Featured Image</label>
                            <input id="featured_image" name="featured_image" type="file" accept="image/*"
                                class="w-full mt-1 bg-slate-950 border border-slate-800 focus:border-amra-primary rounded-xl px-4 py-2.5 outline-none text-sm text-slate-250 file:mr-4 file:py-1 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-slate-800 file:text-teal-400 hover:file:bg-slate-700">
                            @if($property->featured_image)
                                <p class="text-[10px] text-slate-400 mt-1">Current Image: <a href="{{ $property->featured_image }}" target="_blank" class="text-teal-400 hover:underline font-semibold">{{ basename($property->featured_image) }}</a></p>
                            @endif
                        </div>
                        <div>
                            <label for="featured_image_url" class="text-xs font-bold text-slate-400">OR Featured Image URL</label>
                            <input id="featured_image_url" name="featured_image_url" type="text" value="{{ old('featured_image_url', $property->featured_image) }}" placeholder="https://example.com/image.jpg"
                                class="w-full mt-1 bg-slate-950 border border-slate-800 focus:border-amra-primary rounded-xl px-4 py-3 outline-none text-sm text-slate-250 placeholder-slate-650">
                        </div>
                    </div>

                    <!-- Gallery Images -->
                    <div class="space-y-1.5 md:col-span-2 grid md:grid-cols-2 gap-4">
                        <div>
                            <label for="gallery" class="text-xs font-bold text-slate-400">Upload Gallery Images</label>
                            <input id="gallery" name="gallery[]" type="file" accept="image/*" multiple
                                class="w-full mt-1 bg-slate-950 border border-slate-800 focus:border-amra-primary rounded-xl px-4 py-2.5 outline-none text-sm text-slate-250 file:mr-4 file:py-1 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-slate-800 file:text-teal-400 hover:file:bg-slate-700">
                        </div>
                        <div>
                            <label for="gallery_urls" class="text-xs font-bold text-slate-400">OR Enter Gallery Image URLs (One per line)</label>
                            <textarea id="gallery_urls" name="gallery_urls" rows="2" placeholder="https://example.com/gallery1.jpg&#10;https://example.com/gallery2.jpg"
                                class="w-full mt-1 bg-slate-950 border border-slate-800 focus:border-amra-primary rounded-xl px-4 py-2.5 outline-none text-sm text-slate-250 placeholder-slate-650">{{ old('gallery_urls', implode("\n", $property->gallery ?? [])) }}</textarea>
                        </div>
                    </div>

                    <!-- Address -->
                    <div class="space-y-1.5 md:col-span-2">
                        <label for="address" class="text-xs font-bold text-slate-400">Full Address</label>
                        <textarea id="address" name="address" rows="3" placeholder="e.g. Hiranandani Gardens, Powai, Mumbai, Maharashtra 400076"
                            class="w-full bg-slate-950 border border-slate-800 focus:border-amra-primary rounded-xl px-4 py-3 outline-none text-sm text-slate-250">{{ old('address', $property->address) }}</textarea>
                    </div>

                    <!-- Description -->
                    <div class="space-y-1.5 md:col-span-2">
                        <label for="description" class="text-xs font-bold text-slate-400">Property Description</label>
                        <div id="quill-editor" class="w-full">{!! old('description', $property->description) !!}</div>
                        <textarea id="description" name="description" class="hidden">{{ old('description', $property->description) }}</textarea>
                    </div>

                    <div class="space-y-1.5 md:col-span-2">
                        <label for="video_urls" class="text-xs font-bold text-slate-400">Embedded Video URLs</label>
                        <textarea id="video_urls" name="video_urls" rows="3" placeholder="One YouTube or MP4 URL per line"
                            class="w-full bg-slate-950 border border-slate-800 focus:border-amra-primary rounded-xl px-4 py-3 outline-none text-sm text-slate-250">{{ old('video_urls', implode("\n", $property->video_urls ?? [])) }}</textarea>
                    </div>
                </div>
            </div>

            <!-- Section: Configurations -->
            <div class="space-y-4">
                <div class="flex items-center justify-between border-b border-slate-800 pb-2">
                    <h3 class="text-xs font-bold text-slate-400 uppercase tracking-widest">Property Configurations / Unit Types</h3>
                    <button type="button" id="add-config-row" class="bg-teal-500/10 border border-teal-500/20 hover:bg-teal-500/20 text-teal-400 px-3 py-1.5 rounded-lg text-xs font-bold transition-all flex items-center gap-1">
                        <i data-lucide="plus" class="w-3.5 h-3.5"></i> Add Configuration
                    </button>
                </div>
                
                <div id="configurations-container" class="space-y-4">
                    <!-- Dynamic Rows Go Here -->
                </div>
            </div>

            <!-- Section: Amenities -->
            <div class="space-y-4">
                <h3 class="text-xs font-bold text-slate-400 uppercase tracking-widest border-b border-slate-800 pb-2">Amenities</h3>
                <div class="grid gap-4 sm:grid-cols-2 md:grid-cols-4">
                    @php
                        $availableAmenities = [
                            'Swimming Pool', 'Gymnasium', 'Club House', '24x7 Security',
                            'Car Parking', 'Power Backup', 'Landscape Garden', 'Kids Play Area',
                            'Intercom', 'Fire Fighting System', 'High Speed Elevators', 'Wi-Fi'
                        ];
                        $propertyAmenities = is_array($property->amenities) ? $property->amenities : [];
                    @endphp
                    @foreach ($availableAmenities as $amenity)
                        <label class="flex items-center gap-2 rounded-lg border border-slate-850 p-3 text-xs text-slate-200 cursor-pointer hover:bg-slate-850/20 transition-colors">
                            <input type="checkbox" name="amenities[]" value="{{ $amenity }}" 
                                class="rounded bg-slate-950 border-slate-800 text-teal-500 focus:ring-teal-500/20"
                                {{ in_array($amenity, old('amenities', $propertyAmenities)) ? 'checked' : '' }}>
                            {{ $amenity }}
                        </label>
                    @endforeach
                </div>
            </div>

            <!-- Section: Badges -->
            <div class="space-y-4">
                <h3 class="text-xs font-bold text-slate-400 uppercase tracking-widest border-b border-slate-800 pb-2">Listing Badges</h3>
                <div class="flex flex-col sm:flex-row gap-6">
                    <label class="flex items-center gap-2 text-xs text-slate-200 cursor-pointer">
                        <input type="checkbox" name="is_featured" value="1" 
                            class="rounded bg-slate-950 border-slate-800 text-teal-500 focus:ring-teal-500/20"
                            {{ old('is_featured', $property->is_featured) ? 'checked' : '' }}>
                        <span class="font-semibold">Mark as Featured Listing</span>
                    </label>

                    <label class="flex items-center gap-2 text-xs text-slate-200 cursor-pointer">
                        <input type="checkbox" name="is_rera_approved" value="1" 
                            class="rounded bg-slate-950 border-slate-800 text-teal-500 focus:ring-teal-500/20"
                            {{ old('is_rera_approved', $property->is_rera_approved) ? 'checked' : '' }}>
                        <span class="font-semibold">Mark as RERA Approved</span>
                    </label>
                </div>
            </div>

            <!-- Actions -->
            <div class="pt-6 border-t border-slate-800 flex justify-end gap-3">
                <a href="{{ route('crm.properties.index') }}" class="bg-slate-800 hover:bg-slate-755 text-slate-350 font-bold text-xs px-6 py-3.5 rounded-xl transition-all">
                    Cancel
                </a>
                <button type="submit" class="bg-amra-primary hover:bg-teal-400 text-slate-955 font-bold text-xs px-8 py-3.5 rounded-xl transition-all shadow-lg shadow-teal-500/10">
                    Update Property Listing
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://cdn.quilljs.com/1.3.6/quill.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Quill editor initialization
        var quill = new Quill('#quill-editor', {
            theme: 'snow',
            placeholder: 'Enter detailed property features, layouts, landmarks nearby...',
            modules: {
                toolbar: [
                    ['bold', 'italic', 'underline'],
                    [{ 'list': 'ordered'}, { 'list': 'bullet' }],
                    [{ 'header': [2, 3, false] }],
                    ['link'],
                    ['clean']
                ]
            }
        });

        var form = document.querySelector('form');
        form.addEventListener('submit', function() {
            var description = document.querySelector('#description');
            if(quill.root.innerHTML.trim() === '<p><br></p>') {
                description.value = '';
            } else {
                description.value = quill.root.innerHTML;
            }
        });

        // Dynamic configurations setup
        var configContainer = document.getElementById('configurations-container');
        var addConfigBtn = document.getElementById('add-config-row');
        var configIndex = 0;

        function addConfigRow(data = {}) {
            var index = configIndex++;
            var html = `
                <div class="config-row bg-slate-950/40 border border-slate-850 rounded-2xl p-5 relative grid gap-4 sm:grid-cols-2 md:grid-cols-4 pt-10">
                    <button type="button" class="remove-config-row absolute top-3 right-3 text-slate-500 hover:text-rose-500 transition-colors" title="Remove Configuration">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>
                    <div class="space-y-1.5">
                        <label class="text-[10px] font-bold text-slate-400 uppercase">Config Name *</label>
                        <input type="text" name="configurations[${index}][name]" required value="${data.name || ''}" placeholder="e.g. 2BHK, 3BHK" class="w-full bg-slate-900 border border-slate-800 focus:border-amra-primary rounded-lg px-3 py-2 text-xs text-slate-200">
                    </div>
                    <div class="space-y-1.5">
                        <label class="text-[10px] font-bold text-slate-400 uppercase">Price (INR)</label>
                        <input type="number" name="configurations[${index}][price]" value="${data.price || ''}" placeholder="e.g. 8500000" class="w-full bg-slate-900 border border-slate-800 focus:border-amra-primary rounded-lg px-3 py-2 text-xs text-slate-200">
                    </div>
                    <div class="space-y-1.5">
                        <label class="text-[10px] font-bold text-slate-400 uppercase">Area</label>
                        <input type="number" name="configurations[${index}][area]" value="${data.area || ''}" placeholder="e.g. 1250" class="w-full bg-slate-900 border border-slate-800 focus:border-amra-primary rounded-lg px-3 py-2 text-xs text-slate-200">
                    </div>
                    <div class="space-y-1.5">
                        <label class="text-[10px] font-bold text-slate-400 uppercase">Area Unit</label>
                        <select name="configurations[${index}][area_unit]" class="w-full bg-slate-900 border border-slate-800 focus:border-amra-primary rounded-lg px-3 py-2 text-xs text-slate-250">
                            <option value="sq_ft" ${data.area_unit === 'sq_ft' ? 'selected' : ''}>sq_ft</option>
                            <option value="sq_mt" ${data.area_unit === 'sq_mt' ? 'selected' : ''}>sq_mt</option>
                            <option value="sq_yd" ${data.area_unit === 'sq_yd' ? 'selected' : ''}>sq_yd</option>
                            <option value="acre" ${data.area_unit === 'acre' ? 'selected' : ''}>acre</option>
                        </select>
                    </div>
                    <div class="space-y-1.5">
                        <label class="text-[10px] font-bold text-slate-400 uppercase">Bedrooms</label>
                        <input type="number" name="configurations[${index}][bedrooms]" value="${data.bedrooms || ''}" placeholder="e.g. 2" class="w-full bg-slate-900 border border-slate-800 focus:border-amra-primary rounded-lg px-3 py-2 text-xs text-slate-200">
                    </div>
                    <div class="space-y-1.5">
                        <label class="text-[10px] font-bold text-slate-400 uppercase">Bathrooms</label>
                        <input type="number" name="configurations[${index}][bathrooms]" value="${data.bathrooms || ''}" placeholder="e.g. 2" class="w-full bg-slate-900 border border-slate-800 focus:border-amra-primary rounded-lg px-3 py-2 text-xs text-slate-200">
                    </div>
                    <div class="space-y-1.5">
                        <label class="text-[10px] font-bold text-slate-400 uppercase">Status</label>
                        <select name="configurations[${index}][status]" class="w-full bg-slate-900 border border-slate-800 focus:border-amra-primary rounded-lg px-3 py-2 text-xs text-slate-250">
                            <option value="available" ${data.status === 'available' ? 'selected' : ''}>Available</option>
                            <option value="sold_out" ${data.status === 'sold_out' ? 'selected' : ''}>Sold Out</option>
                        </select>
                    </div>
                </div>
            `;
            
            var div = document.createElement('div');
            div.innerHTML = html;
            var rowElement = div.firstElementChild;
            configContainer.appendChild(rowElement);

            rowElement.querySelector('.remove-config-row').addEventListener('click', function() {
                rowElement.remove();
            });
        }

        addConfigBtn.addEventListener('click', function() {
            addConfigRow();
        });

        @if(old('configurations'))
            @foreach(old('configurations') as $oldConfig)
                addConfigRow({
                    name: '{{ e($oldConfig['name'] ?? '') }}',
                    price: '{{ e($oldConfig['price'] ?? '') }}',
                    area: '{{ e($oldConfig['area'] ?? '') }}',
                    area_unit: '{{ e($oldConfig['area_unit'] ?? 'sq_ft') }}',
                    bedrooms: '{{ e($oldConfig['bedrooms'] ?? '') }}',
                    bathrooms: '{{ e($oldConfig['bathrooms'] ?? '') }}',
                    status: '{{ e($oldConfig['status'] ?? 'available') }}'
                });
            @endforeach
        @elseif($property->configurations->isNotEmpty())
            @foreach($property->configurations as $config)
                addConfigRow({
                    name: '{{ e($config->name) }}',
                    price: '{{ e($config->price) }}',
                    area: '{{ e($config->area) }}',
                    area_unit: '{{ e($config->area_unit) }}',
                    bedrooms: '{{ e($config->bedrooms) }}',
                    bathrooms: '{{ e($config->bathrooms) }}',
                    status: '{{ e($config->status) }}'
                });
            @endforeach
        @endif
    });
</script>
@endsection
