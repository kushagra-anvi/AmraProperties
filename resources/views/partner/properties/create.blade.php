@extends('layouts.crm')

@section('title', 'Post Property')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    <a href="{{ route('crm.partner.properties.index') }}" class="inline-flex items-center gap-1.5 text-xs text-slate-400 hover:text-white transition-colors">
        <i data-lucide="arrow-left" class="w-3.5 h-3.5"></i> Back to My Properties
    </a>
    <div>
        <h1 class="text-3xl font-serif font-extrabold text-white">Post Property</h1>
        <p class="text-sm text-slate-400 mt-1">Submit a listing for admin review. Published listings become visible on the public website.</p>
    </div>

    <div class="bg-slate-900 border border-slate-800 rounded-3xl p-6 md:p-8 shadow-md">
        <form action="{{ route('crm.partner.properties.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf
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

            <div class="space-y-4">
                <h3 class="text-xs font-bold text-slate-400 uppercase tracking-widest border-b border-slate-800 pb-2">Property Details</h3>
                
                <div class="grid gap-6 md:grid-cols-2">
                    <div class="md:col-span-2">
                        <label class="text-xs font-bold text-slate-400">Property Title <span class="text-rose-500">*</span></label>
                        <input name="title" value="{{ old('title') }}" required placeholder="e.g. 3BHK Premium Sea View Apartment" class="w-full mt-1 bg-slate-950 border border-slate-800 rounded-xl px-4 py-3 text-sm text-slate-200 focus:border-amra-primary outline-none">
                    </div>
                    
                    <div>
                        <label class="text-xs font-bold text-slate-400">Price (INR)</label>
                        <input name="price" type="number" value="{{ old('price') }}" placeholder="e.g. 15000000" class="w-full mt-1 bg-slate-950 border border-slate-800 rounded-xl px-4 py-3 text-sm text-slate-200 focus:border-amra-primary outline-none">
                    </div>
                    
                    <div>
                        <label class="text-xs font-bold text-slate-400">Avg. Price / sq.ft</label>
                        <input name="avg_price_per_sqft" type="number" step="0.01" value="{{ old('avg_price_per_sqft') }}" placeholder="e.g. 10230" class="w-full mt-1 bg-slate-950 border border-slate-800 rounded-xl px-4 py-3 text-sm text-slate-200 focus:border-amra-primary outline-none">
                    </div>

                    <div>
                        <label class="text-xs font-bold text-slate-400">Listing Type <span class="text-rose-500">*</span></label>
                        <select name="listing_type" required class="w-full mt-1 bg-slate-950 border border-slate-800 rounded-xl px-4 py-3 text-sm text-slate-250 focus:border-amra-primary outline-none">
                            <option value="sale" {{ old('listing_type') === 'sale' ? 'selected' : '' }}>For Sale</option>
                            <option value="rent" {{ old('listing_type') === 'rent' ? 'selected' : '' }}>For Rent</option>
                        </select>
                    </div>

                    <div>
                        <label class="text-xs font-bold text-slate-400">Configuration</label>
                        <input name="configuration" value="{{ old('configuration') }}" placeholder="e.g. 2BHK / Villa / Plot" class="w-full mt-1 bg-slate-950 border border-slate-800 rounded-xl px-4 py-3 text-sm text-slate-200 focus:border-amra-primary outline-none">
                    </div>

                    <div>
                        <label class="text-xs font-bold text-slate-400">Area</label>
                        <input name="area" type="number" value="{{ old('area') }}" placeholder="e.g. 1200" class="w-full mt-1 bg-slate-950 border border-slate-800 rounded-xl px-4 py-3 text-sm text-slate-200 focus:border-amra-primary outline-none">
                    </div>

                    <div>
                        <label class="text-xs font-bold text-slate-400">Area Unit <span class="text-rose-500">*</span></label>
                        <select name="area_unit" required class="w-full mt-1 bg-slate-950 border border-slate-800 rounded-xl px-4 py-3 text-sm text-slate-250 focus:border-amra-primary outline-none">
                            <option value="sq_ft" {{ old('area_unit') === 'sq_ft' ? 'selected' : '' }}>Square Feet (sq_ft)</option>
                            <option value="sq_mt" {{ old('area_unit') === 'sq_mt' ? 'selected' : '' }}>Square Meters (sq_mt)</option>
                            <option value="sq_yd" {{ old('area_unit') === 'sq_yd' ? 'selected' : '' }}>Square Yards (sq_yd)</option>
                            <option value="acre" {{ old('area_unit') === 'acre' ? 'selected' : '' }}>Acre</option>
                        </select>
                    </div>

                    <div>
                        <label class="text-xs font-bold text-slate-400">Bedrooms</label>
                        <input name="bedrooms" type="number" value="{{ old('bedrooms') }}" placeholder="e.g. 3" class="w-full mt-1 bg-slate-950 border border-slate-800 rounded-xl px-4 py-3 text-sm text-slate-200 focus:border-amra-primary outline-none">
                    </div>

                    <div>
                        <label class="text-xs font-bold text-slate-400">Bathrooms</label>
                        <input name="bathrooms" type="number" value="{{ old('bathrooms') }}" placeholder="e.g. 3" class="w-full mt-1 bg-slate-950 border border-slate-800 rounded-xl px-4 py-3 text-sm text-slate-200 focus:border-amra-primary outline-none">
                    </div>

                    <div>
                        <label class="text-xs font-bold text-slate-400">Possession Date</label>
                        <input name="possession_date" type="date" value="{{ old('possession_date') }}" class="w-full mt-1 bg-slate-950 border border-slate-800 rounded-xl px-4 py-3 text-sm text-slate-200 focus:border-amra-primary outline-none">
                    </div>

                    <div>
                        <label class="text-xs font-bold text-slate-400">Possession Status</label>
                        <input name="possession_status" value="{{ old('possession_status') }}" placeholder="e.g. Ready to Move" class="w-full mt-1 bg-slate-950 border border-slate-800 rounded-xl px-4 py-3 text-sm text-slate-200 focus:border-amra-primary outline-none">
                    </div>

                    <div>
                        <label class="text-xs font-bold text-slate-400">Developer / Builder Name</label>
                        <input name="developer_name" value="{{ old('developer_name') }}" placeholder="e.g. Lodha Group" class="w-full mt-1 bg-slate-950 border border-slate-800 rounded-xl px-4 py-3 text-sm text-slate-200 focus:border-amra-primary outline-none">
                    </div>

                    <div>
                        <label class="text-xs font-bold text-slate-400">RERA Number</label>
                        <input name="rera_number" value="{{ old('rera_number') }}" placeholder="e.g. PR0001923" class="w-full mt-1 bg-slate-950 border border-slate-800 rounded-xl px-4 py-3 text-sm text-slate-200 focus:border-amra-primary outline-none">
                    </div>

                    <div>
                        <label class="text-xs font-bold text-slate-400">Contact Phone</label>
                        <input name="contact_phone" value="{{ old('contact_phone', $partner->phone) }}" class="w-full mt-1 bg-slate-950 border border-slate-800 rounded-xl px-4 py-3 text-sm text-slate-200 focus:border-amra-primary outline-none">
                    </div>

                    <div>
                        <label class="text-xs font-bold text-slate-400">WhatsApp Number</label>
                        <input name="contact_whatsapp" value="{{ old('contact_whatsapp', $partner->phone) }}" class="w-full mt-1 bg-slate-950 border border-slate-800 rounded-xl px-4 py-3 text-sm text-slate-200 focus:border-amra-primary outline-none">
                    </div>

                    <div>
                        <label class="text-xs font-bold text-slate-400">City <span class="text-rose-500">*</span></label>
                        <input name="city" required value="{{ old('city', $partner->city) }}" class="w-full mt-1 bg-slate-950 border border-slate-800 rounded-xl px-4 py-3 text-sm text-slate-200 focus:border-amra-primary outline-none">
                    </div>

                    <div>
                        <label class="text-xs font-bold text-slate-400">State</label>
                        <input name="state" value="{{ old('state') }}" placeholder="e.g. Maharashtra" class="w-full mt-1 bg-slate-950 border border-slate-800 rounded-xl px-4 py-3 text-sm text-slate-200 focus:border-amra-primary outline-none">
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div class="space-y-1.5">
                            <label class="text-xs font-bold text-slate-400">Latitude</label>
                            <input name="latitude" type="number" step="0.00000001" value="{{ old('latitude') }}" placeholder="e.g. 19.117" class="w-full bg-slate-950 border border-slate-800 focus:border-amra-primary rounded-xl px-3 py-3 text-sm text-slate-200 outline-none">
                        </div>
                        <div class="space-y-1.5">
                            <label class="text-xs font-bold text-slate-400">Longitude</label>
                            <input name="longitude" type="number" step="0.00000001" value="{{ old('longitude') }}" placeholder="e.g. 72.906" class="w-full bg-slate-950 border border-slate-800 focus:border-amra-primary rounded-xl px-3 py-3 text-sm text-slate-200 outline-none">
                        </div>
                    </div>

                    <!-- Featured Image -->
                    <div class="md:col-span-2 grid md:grid-cols-2 gap-4">
                        <div>
                            <label class="text-xs font-bold text-slate-400">Upload Featured Image</label>
                            <input name="featured_image" type="file" accept="image/*" class="w-full mt-1 bg-slate-950 border border-slate-800 focus:border-amra-primary rounded-xl px-4 py-2.5 outline-none text-sm text-slate-250 file:mr-4 file:py-1 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-slate-800 file:text-teal-400 hover:file:bg-slate-700">
                        </div>
                        <div>
                            <label class="text-xs font-bold text-slate-400">OR Featured Image URL</label>
                            <input name="featured_image_url" type="text" value="{{ old('featured_image_url') }}" placeholder="https://example.com/image.jpg" class="w-full mt-1 bg-slate-950 border border-slate-800 focus:border-amra-primary rounded-xl px-4 py-3 outline-none text-sm text-slate-250 placeholder-slate-650">
                        </div>
                    </div>

                    <!-- Gallery Images -->
                    <div class="md:col-span-2 grid md:grid-cols-2 gap-4">
                        <div>
                            <label class="text-xs font-bold text-slate-400">Upload Gallery Images</label>
                            <input name="gallery[]" type="file" accept="image/*" multiple class="w-full mt-1 bg-slate-950 border border-slate-800 focus:border-amra-primary rounded-xl px-4 py-2.5 outline-none text-sm text-slate-250 file:mr-4 file:py-1 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-slate-800 file:text-teal-400 hover:file:bg-slate-700">
                        </div>
                        <div>
                            <label class="text-xs font-bold text-slate-400">OR Enter Gallery Image URLs (One per line)</label>
                            <textarea name="gallery_urls" rows="2" placeholder="https://example.com/gallery1.jpg&#10;https://example.com/gallery2.jpg" class="w-full mt-1 bg-slate-950 border border-slate-800 focus:border-amra-primary rounded-xl px-4 py-2.5 outline-none text-sm text-slate-250 placeholder-slate-650">{{ old('gallery_urls') }}</textarea>
                        </div>
                    </div>

                    <div class="md:col-span-2">
                        <label class="text-xs font-bold text-slate-400">Full Address</label>
                        <textarea name="address" rows="3" placeholder="e.g. Hiranandani Gardens, Powai, Mumbai" class="w-full mt-1 bg-slate-950 border border-slate-800 rounded-xl px-4 py-3 text-sm text-slate-200 focus:border-amra-primary outline-none">{{ old('address') }}</textarea>
                    </div>

                    <div class="md:col-span-2">
                        <label class="text-xs font-bold text-slate-400">Property Description</label>
                        <textarea name="description" rows="5" placeholder="Enter detailed property description..." class="w-full mt-1 bg-slate-950 border border-slate-800 rounded-xl px-4 py-3 text-sm text-slate-200 focus:border-amra-primary outline-none">{{ old('description') }}</textarea>
                    </div>

                    <div class="md:col-span-2">
                        <label class="text-xs font-bold text-slate-400">Embedded Video URLs</label>
                        <textarea name="video_urls" rows="3" placeholder="One YouTube or MP4 URL per line" class="w-full mt-1 bg-slate-950 border border-slate-800 rounded-xl px-4 py-3 text-sm text-slate-200 focus:border-amra-primary outline-none">{{ old('video_urls') }}</textarea>
                    </div>
                </div>
            </div>

            <!-- Section: Configurations -->
            <div class="space-y-4">
                <div class="flex items-center justify-between border-b border-slate-800 pb-2">
                    <h3 class="text-xs font-bold text-slate-400 uppercase tracking-widest font-serif">Property Configurations / Unit Types</h3>
                    <button type="button" id="add-config-row" class="bg-teal-500/10 border border-teal-500/20 hover:bg-teal-500/20 text-teal-400 px-3 py-1.5 rounded-lg text-xs font-bold transition-all flex items-center gap-1">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg> Add Configuration
                    </button>
                </div>
                
                <div id="configurations-container" class="space-y-4">
                    <!-- Dynamic Rows Go Here -->
                </div>
            </div>

            <!-- Amenities -->
            <div class="space-y-4">
                <h3 class="text-xs font-bold text-slate-400 uppercase tracking-widest border-b border-slate-800 pb-2">Amenities</h3>
                <div class="grid gap-4 sm:grid-cols-2 md:grid-cols-4">
                    @php
                        $availableAmenities = [
                            'Swimming Pool', 'Gymnasium', 'Club House', '24x7 Security',
                            'Car Parking', 'Power Backup', 'Landscape Garden', 'Kids Play Area',
                            'Intercom', 'Fire Fighting System', 'High Speed Elevators', 'Wi-Fi'
                        ];
                    @endphp
                    @foreach ($availableAmenities as $amenity)
                        <label class="flex items-center gap-2 rounded-lg border border-slate-850 p-3 text-xs text-slate-200 cursor-pointer hover:bg-slate-850/20 transition-colors">
                            <input type="checkbox" name="amenities[]" value="{{ $amenity }}" 
                                class="rounded bg-slate-950 border-slate-800 text-teal-500 focus:ring-teal-500/20"
                                {{ is_array(old('amenities')) && in_array($amenity, old('amenities')) ? 'checked' : '' }}>
                            {{ $amenity }}
                        </label>
                    @endforeach
                </div>
            </div>

            <div class="md:col-span-2 flex justify-end gap-3 border-t border-slate-800 pt-6">
                <a href="{{ route('crm.partner.properties.index') }}" class="bg-slate-800 hover:bg-slate-700 text-slate-300 font-bold text-xs px-6 py-3.5 rounded-xl">Cancel</a>
                <button type="submit" class="bg-amra-primary hover:bg-teal-400 text-slate-955 font-bold text-xs px-8 py-3.5 rounded-xl transition-all shadow-lg shadow-teal-500/10">Submit for Review</button>
            </div>
        </form>
    </div>
</div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
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
                        <input type="text" name="configurations[${index}][name]" required value="${data.name || ''}" placeholder="e.g. 2BHK, 3BHK" class="w-full bg-slate-900 border border-slate-800 focus:border-amra-primary rounded-lg px-3 py-2 text-xs text-slate-200 outline-none">
                    </div>
                    <div class="space-y-1.5">
                        <label class="text-[10px] font-bold text-slate-400 uppercase">Price (INR)</label>
                        <input type="number" name="configurations[${index}][price]" value="${data.price || ''}" placeholder="e.g. 8500000" class="w-full bg-slate-900 border border-slate-800 focus:border-amra-primary rounded-lg px-3 py-2 text-xs text-slate-200 outline-none">
                    </div>
                    <div class="space-y-1.5">
                        <label class="text-[10px] font-bold text-slate-400 uppercase">Area</label>
                        <input type="number" name="configurations[${index}][area]" value="${data.area || ''}" placeholder="e.g. 1250" class="w-full bg-slate-900 border border-slate-800 focus:border-amra-primary rounded-lg px-3 py-2 text-xs text-slate-200 outline-none">
                    </div>
                    <div class="space-y-1.5">
                        <label class="text-[10px] font-bold text-slate-400 uppercase">Area Unit</label>
                        <select name="configurations[${index}][area_unit]" class="w-full bg-slate-900 border border-slate-800 focus:border-amra-primary rounded-lg px-3 py-2 text-xs text-slate-250 outline-none">
                            <option value="sq_ft" ${data.area_unit === 'sq_ft' ? 'selected' : ''}>sq_ft</option>
                            <option value="sq_mt" ${data.area_unit === 'sq_mt' ? 'selected' : ''}>sq_mt</option>
                            <option value="sq_yd" ${data.area_unit === 'sq_yd' ? 'selected' : ''}>sq_yd</option>
                            <option value="acre" ${data.area_unit === 'acre' ? 'selected' : ''}>acre</option>
                        </select>
                    </div>
                    <div class="space-y-1.5">
                        <label class="text-[10px] font-bold text-slate-400 uppercase">Bedrooms</label>
                        <input type="number" name="configurations[${index}][bedrooms]" value="${data.bedrooms || ''}" placeholder="e.g. 2" class="w-full bg-slate-900 border border-slate-800 focus:border-amra-primary rounded-lg px-3 py-2 text-xs text-slate-200 outline-none">
                    </div>
                    <div class="space-y-1.5">
                        <label class="text-[10px] font-bold text-slate-400 uppercase">Bathrooms</label>
                        <input type="number" name="configurations[${index}][bathrooms]" value="${data.bathrooms || ''}" placeholder="e.g. 2" class="w-full bg-slate-900 border border-slate-800 focus:border-amra-primary rounded-lg px-3 py-2 text-xs text-slate-200 outline-none">
                    </div>
                    <div class="space-y-1.5">
                        <label class="text-[10px] font-bold text-slate-400 uppercase">Status</label>
                        <select name="configurations[${index}][status]" class="w-full bg-slate-900 border border-slate-800 focus:border-amra-primary rounded-lg px-3 py-2 text-xs text-slate-250 outline-none">
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
        @endif
    });
</script>
@endsection
