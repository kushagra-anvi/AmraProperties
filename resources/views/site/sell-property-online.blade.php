@extends('layouts.site')

@section('title', 'Sell property online | Free property listing & Advertise flat, Plot etc')
@section('meta_description', 'Sell property online with Amra Property. We offer Free property Listing and get ready to reach a vast and diverse audience. Advertise your property on Google & Meta.')

@section('seo_schema')
<link rel="canonical" href="{{ route('site.sell-property-online') }}">
<meta property="og:locale" content="en_GB">
<meta property="og:type" content="article">
<meta property="og:title" content="Sell property online | Free property listing & Advertise flat, Plot etc">
<meta property="og:description" content="Sell property online with Amra Property. We offer Free property Listing and get ready to reach a vast and diverse audience. Advertise your property on Google & Meta.">
<meta property="og:url" content="{{ route('site.sell-property-online') }}">
<meta property="article:publisher" content="https://www.facebook.com/propertyamra">
<meta property="og:image" content="https://www.amraproperty.com/wp-content/uploads/2026/02/Gemini_Generated_Image_ez4cd3ez4cd3ez4c-1-2.jpg">
<meta name="twitter:card" content="summary_large_image">
<meta name="twitter:site" content="@AmraProperty">
<script type="application/ld+json">
{
  "@@context": "https://schema.org",
  "@@type": "WebPage",
  "name": "Sell property online | Free property listing & Advertise flat, Plot etc",
  "description": "Sell property online with Amra Property. We offer Free property Listing and get ready to reach a vast and diverse audience. Advertise your property on Google & Meta.",
  "url": "{{ route('site.sell-property-online') }}"
}
</script>
@endsection

@section('content')
@php
    $whatsappUrl = 'https://wa.me/919559992958?text=' . rawurlencode('Hi, I want to post my property ad on Amra Property.');
    $heroImage = 'https://www.amraproperty.com/wp-content/uploads/2026/02/Gemini_Generated_Image_ez4cd3ez4cd3ez4c-1-2.jpg';
    $listingVisual = asset('wp-content/uploads/2023/12/Free-Property-Listing-web-2-1-1.webp');
    $sellerVisual = asset('wp-content/uploads/2023/12/Free-Property-Listing-web-1-1-1.webp');
    $whatsappVisual = asset('wp-content/uploads/2023/12/WhatsApp-Image-2023-12-01-at-1.16.12-PM-768x430.webp');
    $freeListingBanner = asset('wp-content/uploads/2026/01/100-fREE-LISTING-1-1-768x243.png');
    $ctaVisual = asset('assets/images/cta_key.png');
    $benefits = [
        '100% Free Property Listing',
        'Get Access to 4 Lakh+ Active Buyers',
        'No Brokerage & No Hidden Charges',
        'Direct Owner-to-Buyer Contact',
        'Sell Faster with Premium Promotion',
        'No Agent or Broker Spam',
        'Quick Listing - Takes Less Than 2 Minutes',
        'Post Property via WhatsApp or Online Form',
        'Get Expert Advice on Market Trends & Pricing',
    ];
    $steps = [
        ['Post your property', 'Add basic details like price, location, photos, and possession status.', 'home'],
        ['Get Genuine Enquiries', 'Verified buyers & tenants contact you directly - no brokers in between.', 'messages-square'],
        ['Close Faster', 'Negotiate directly, finalize your deal, and save brokerage.', 'badge-check'],
    ];
    $plans = [
        ['name' => 'Free Plan', 'price' => '₹ 0', 'term' => 'Lifetime', 'features' => ['Unlimited Free Property Listing - Lifetime', 'No Brokerage | No Hidden Charges']],
        ['name' => 'Starter Plan', 'price' => '₹ 2,500', 'term' => '/Month', 'features' => ['Marketing on Facebook & Instagram', '2x Buyer Enquiry Opportunities', '1 Featured Listings']],
        ['name' => 'Growth Plan', 'price' => '₹ 4,500', 'term' => '/Month', 'features' => ['Marketing on Google, Facebook & Instagram', '2 Featured Listings', '3x Buyer Enquiry Opportunities', 'Dedicated Relationship Manager']],
        ['name' => 'Premium Plan', 'price' => '₹ 8,000', 'term' => '/ Month', 'features' => ['Marketing on Google, Facebook & Instagram (Full Funnel)', '2 Featured Listings', '5x Buyer Enquiry Opportunities', 'Dedicated Relationship Manager']],
    ];
    $pricingRows = [
        ['Free Plan', 'Totally free', 'Unlimited basic listings', '0 featured listings', '-'],
        ['Starter Plan', '₹2500 / mo  ₹27000 / year', 'Unlimited basic listings', '2 featured listings', '2'],
        ['Growth Plan', '₹4500 / mo  ₹45900 / year', 'Unlimited basic listings', '2 featured listings', '2'],
        ['Premium Plan', '₹7999 / mo  ₹81600 / year', 'Unlimited basic listings', '2 featured listings', '2'],
    ];
    $faqItems = [
        [
            'question' => '01. Can I sell property online for free?',
            'answer' => 'Yes. You can list your property online without brokerage or additional charges.',
            'points' => ['Works for flats, plots, row houses, and other property types', 'Basic listing on Amra Property is simple to start', 'Your listing can reach a large pool of potential buyers'],
        ],
        [
            'question' => '02. How does it work?',
            'answer' => 'Share your property details and our team verifies the listing before it goes live.',
            'points' => ['Submit property details online or via WhatsApp', 'We verify the information and publish the listing', 'Interested buyers share their name and phone number directly'],
        ],
        [
            'question' => '03. Is it safe to sell or list property online?',
            'answer' => 'Yes. Your details and property enquiries are handled securely.',
            'points' => ['Owner information is handled with care', 'Buyer leads are kept secure', 'Your enquiry data is not openly shared'],
        ],
        [
            'question' => '04. How can I get more inquiries about my property?',
            'answer' => 'Better details usually bring better quality leads.',
            'points' => ['Add accurate location, area, and price information', 'Write a complete description with amenities and features', 'Use clear, real property images'],
        ],
        [
            'question' => '05. Will my property also be displayed on social media?',
            'answer' => 'Yes, social media promotion is available with upgraded listing plans.',
            'points' => ['Premium promotion can include social channels', 'Campaigns help expand reach beyond organic listing views', 'Useful when you want faster and broader visibility'],
        ],
    ];
@endphp

<div class="bg-slate-50">
    <section class="relative min-h-[86vh] overflow-hidden pt-28 text-white">
        <img src="{{ $heroImage }}" alt="Sell Property online" class="absolute inset-0 h-full w-full object-cover">
        <div class="absolute inset-0 bg-slate-950/75"></div>
        <div class="absolute inset-x-0 bottom-0 z-10 w-full overflow-hidden leading-[0] transform translate-y-[1px]">
            <svg class="relative block w-full h-[40px] md:h-[80px]" viewBox="0 0 1200 120" preserveAspectRatio="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M0,120 L1200,0 L1200,120 Z" class="fill-slate-50"></path>
            </svg>
        </div>
        <div class="relative mx-auto flex min-h-[calc(86vh-7rem)] max-w-7xl flex-col justify-center px-6 pt-16 pb-28 md:pb-36">
            <div class="max-w-4xl">
                <p class="mb-4 text-[11px] font-extrabold uppercase tracking-[0.32em] text-amra-primary">Post Free Property Ad</p>
                <h1 class="max-w-4xl text-4xl font-serif font-extrabold leading-tight md:text-6xl lg:text-7xl">Post your property Ad to sell or rent online for Free!</h1>
                <p class="mt-6 max-w-2xl text-base leading-relaxed text-slate-200 md:text-lg">Sell property online with Amra Property. We offer Free property Listing and get ready to reach a vast and diverse audience. Advertise your property on Google & Meta.</p>
                <div class="mt-8 flex flex-col gap-3 sm:flex-row">
                    <a href="{{ $whatsappUrl }}" target="_blank" rel="noopener noreferrer" class="inline-flex items-center justify-center gap-2 rounded-xl bg-amra-primary px-6 py-3.5 text-sm font-extrabold text-slate-950 shadow-lg shadow-teal-500/20 transition-all hover:bg-teal-300">
                        <i data-lucide="send" class="h-4 w-4"></i>
                        Sell Property Online
                    </a>
                    <a href="tel:+919559992958" class="inline-flex items-center justify-center gap-2 rounded-xl border border-white/20 bg-white/10 px-6 py-3.5 text-sm font-extrabold text-white backdrop-blur transition-all hover:bg-white hover:text-slate-950">
                        <i data-lucide="phone" class="h-4 w-4"></i>
                        +91 9559992958
                    </a>
                </div>
            </div>
            <div class="mt-12 grid max-w-4xl grid-cols-1 gap-3 sm:grid-cols-3">
                @foreach ([
                    ['4 Lakh+', 'Active buyers'],
                    ['₹0', 'Free basic listing'],
                    ['24 hrs', 'Listing verification'],
                ] as $stat)
                    <div class="border-l border-white/20 bg-white/10 px-5 py-4 backdrop-blur-md">
                        <p class="text-2xl font-serif font-extrabold">{{ $stat[0] }}</p>
                        <p class="text-xs font-bold uppercase tracking-widest text-slate-300">{{ $stat[1] }}</p>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <section class="py-12">
        <div class="mx-auto max-w-7xl px-6">
            <div class="grid gap-3 rounded-2xl border border-slate-200 bg-white p-4 shadow-sm md:grid-cols-3">
                @foreach ($steps as $step)
                    <div class="flex gap-4 rounded-xl bg-slate-50 p-5">
                        <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-teal-50 text-amra-primary">
                            <i data-lucide="{{ $step[2] }}" class="h-5 w-5"></i>
                        </div>
                        <div>
                            <h2 class="font-serif text-base font-extrabold text-slate-900">{{ $step[0] }}</h2>
                            <p class="mt-1 text-sm leading-relaxed text-slate-500">{{ $step[1] }}</p>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <section class="pb-16">
        <div class="mx-auto grid max-w-7xl gap-8 px-6 lg:grid-cols-[0.9fr_1.1fr] lg:items-center">
            <div>
                <p class="mb-3 text-[11px] font-extrabold uppercase tracking-[0.28em] text-amra-primary">Sell Property Online</p>
                <h2 class="text-3xl font-serif font-extrabold leading-tight text-slate-900 md:text-5xl">Sell Property Online in 3 Simple Steps</h2>
                <p class="mt-4 text-sm leading-relaxed text-slate-500">Everything you need to sell faster, smarter & without brokerage</p>
                <div class="mt-7 overflow-hidden rounded-3xl border border-slate-200 bg-white shadow-sm">
                    <img src="{{ $listingVisual }}" alt="Free property listing process" class="h-52 w-full object-cover sm:h-60">
                    <div class="grid gap-2 border-t border-slate-100 bg-white p-3 sm:grid-cols-3">
                        <div class="rounded-2xl bg-slate-50 p-3">
                            <p class="font-serif text-lg font-extrabold text-slate-900">01</p>
                            <p class="text-[9px] font-bold uppercase tracking-wider text-slate-500">Share Details</p>
                        </div>
                        <div class="rounded-2xl bg-slate-50 p-3">
                            <p class="font-serif text-lg font-extrabold text-slate-900">02</p>
                            <p class="text-[9px] font-bold uppercase tracking-wider text-slate-500">Verify Listing</p>
                        </div>
                        <div class="rounded-2xl bg-slate-50 p-3">
                            <p class="font-serif text-lg font-extrabold text-slate-900">03</p>
                            <p class="text-[9px] font-bold uppercase tracking-wider text-slate-500">Get Leads</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="grid gap-3 sm:grid-cols-2">
                @foreach ($benefits as $point)
                    <div class="flex items-start gap-3 rounded-xl border border-slate-200 bg-white p-4 shadow-sm">
                        <span class="mt-0.5 flex h-6 w-6 shrink-0 items-center justify-center rounded-full bg-emerald-50 text-emerald-600">
                            <i data-lucide="check" class="h-3.5 w-3.5"></i>
                        </span>
                        <span class="text-sm font-semibold leading-relaxed text-slate-700">{{ $point }}</span>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <section class="bg-white py-16">
        <div class="mx-auto grid max-w-7xl gap-8 px-6 lg:grid-cols-[0.9fr_1.1fr] lg:items-center">
            <div>
                <p class="mb-3 text-[11px] font-extrabold uppercase tracking-[0.28em] text-amra-primary">Why Choose Us</p>
                <h2 class="text-3xl font-serif font-extrabold leading-tight text-slate-900 md:text-4xl">Why Choose Amra Property to Sell Property Online</h2>
                <div class="mt-7 overflow-hidden rounded-3xl border border-slate-200 bg-slate-50 shadow-sm">
                    <img src="{{ $sellerVisual }}" alt="Owner listing property online" class="h-72 w-full object-cover">
                </div>
            </div>
            <div class="grid gap-5 md:grid-cols-3">
                @foreach ([
                    ['Wide Exposure', 'We leverage the power of SEO to ensure your property gets maximum exposure across all your targeted locations and reaches genuine buyers.', 'search'],
                    ['Easy Listing Process', 'Listing your property is simple and hassle-free. Share your property details via WhatsApp or fill out our easy-to-use online form.', 'clipboard-list'],
                    ['Marketing & Promotion', 'We promote your property through targeted campaigns on Google and social media platforms like Facebook, Instagram, and Twitter. We focus on quality enquiries, not quantity.', 'megaphone'],
                ] as $item)
                    <div class="rounded-2xl border border-slate-200 bg-slate-50 p-6">
                        <i data-lucide="{{ $item[2] }}" class="mb-5 h-6 w-6 text-amra-primary"></i>
                        <h3 class="mb-2 font-serif text-lg font-extrabold text-slate-900">{{ $item[0] }}</h3>
                        <p class="text-sm leading-relaxed text-slate-500">{{ $item[1] }}</p>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <section class="py-16">
        <div class="mx-auto max-w-7xl px-6">
            <div class="mb-10 flex flex-col gap-4 md:flex-row md:items-end md:justify-between">
                <div class="max-w-3xl">
                    <p class="mb-3 text-[11px] font-extrabold uppercase tracking-[0.28em] text-amra-primary">Business</p>
                    <h2 class="text-3xl font-serif font-extrabold leading-tight text-slate-900 md:text-4xl">Affordable Packages to Sell Your Property Online</h2>
                    <p class="mt-3 text-sm text-slate-500">Choose a plan that fits your requirement and start receiving buyer inquiries immediately.</p>
                </div>
                <a href="tel:+919559992958" class="inline-flex items-center gap-2 rounded-xl bg-slate-900 px-5 py-3 text-xs font-extrabold text-white transition-all hover:bg-amra-primary hover:text-slate-950">
                    <i data-lucide="phone" class="h-4 w-4"></i>
                    For assistance call us at :+91 9559992958
                </a>
            </div>
            <div class="grid gap-5 md:grid-cols-2 lg:grid-cols-4">
                @foreach ($plans as $plan)
                    <div class="flex min-h-[300px] flex-col rounded-2xl border border-slate-200 bg-white p-6 shadow-sm transition-all hover:-translate-y-1 hover:shadow-xl">
                        <h3 class="font-serif text-xl font-extrabold text-slate-900">{{ $plan['name'] }}</h3>
                        <div class="my-5">
                            <span class="text-3xl font-serif font-extrabold text-amra-primary">{{ $plan['price'] }}</span>
                            <span class="text-xs font-bold text-slate-400">{{ $plan['term'] }}</span>
                        </div>
                        <ul class="mb-6 flex-grow space-y-3 text-sm text-slate-600">
                            @foreach ($plan['features'] as $feature)
                                <li class="flex gap-2">
                                    <i data-lucide="check" class="mt-0.5 h-4 w-4 shrink-0 text-emerald-500"></i>
                                    <span>{{ $feature }}</span>
                                </li>
                            @endforeach
                        </ul>
                        <a href="{{ $whatsappUrl }}" target="_blank" rel="noopener noreferrer" class="inline-flex items-center justify-center gap-2 rounded-xl border border-amra-primary px-4 py-3 text-xs font-extrabold text-amra-primary transition-all hover:bg-amra-primary hover:text-white">Sign up as agent</a>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <section class="bg-white py-16">
        <div class="mx-auto max-w-7xl px-6">
            <div class="mb-8">
                <p class="mb-3 text-[11px] font-extrabold uppercase tracking-[0.28em] text-amra-primary">Select your payment option</p>
                <h2 class="text-3xl font-serif font-extrabold text-slate-900 md:text-4xl">Billed monthly / annually</h2>
            </div>
            <div class="overflow-x-auto rounded-2xl border border-slate-200">
                <table class="min-w-full text-sm">
                    <thead class="bg-slate-900 text-left text-[11px] uppercase tracking-wider text-white">
                        <tr>
                            <th class="px-5 py-4">Plan</th>
                            <th class="px-5 py-4">Price</th>
                            <th class="px-5 py-4">Listings</th>
                            <th class="px-5 py-4">Featured listings</th>
                            <th class="px-5 py-4">Featured count</th>
                            <th class="px-5 py-4"></th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-200 bg-white">
                        @foreach ($pricingRows as $row)
                            <tr>
                                @foreach ($row as $cell)
                                    <td class="px-5 py-4 font-semibold text-slate-700">{{ $cell }}</td>
                                @endforeach
                                <td class="px-5 py-4">
                                    <a href="{{ $whatsappUrl }}" target="_blank" rel="noopener noreferrer" class="font-extrabold text-amra-primary">Sign up as agent</a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </section>

    <section class="py-16">
        <div class="mx-auto grid max-w-7xl gap-8 px-6 lg:grid-cols-[0.85fr_1.15fr]">
            <div class="overflow-hidden rounded-3xl bg-slate-900 text-white shadow-sm">
                <div class="p-8 md:p-10">
                    <p class="mb-3 text-[11px] font-extrabold uppercase tracking-[0.28em] text-amra-primary">List Your Property</p>
                    <h2 class="text-3xl font-serif font-extrabold leading-tight">Unlock Your Property's Potential with AMRA Property!</h2>
                    <p class="mt-4 text-sm leading-relaxed text-slate-300">So why wait? Experience the convenience and efficiency of selling your property online with Amra Property.</p>
                    <a href="{{ $whatsappUrl }}" target="_blank" rel="noopener noreferrer" class="mt-8 inline-flex items-center justify-center gap-2 rounded-xl bg-amra-primary px-6 py-3.5 text-sm font-extrabold text-slate-950 transition-all hover:bg-teal-300">List Your Property</a>
                </div>
                <img src="{{ $ctaVisual }}" alt="Property owner listing with Amra Property" class="h-64 w-full object-cover object-center">
            </div>
            <article class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm md:p-8">
                <div class="mb-6 border-b border-slate-100 pb-6">
                    <p class="mb-3 text-[11px] font-extrabold uppercase tracking-[0.24em] text-amra-primary">Why sellers use Amra</p>
                    <h2 class="max-w-2xl text-2xl font-serif font-extrabold leading-tight text-slate-900 md:text-3xl">Sell property online without expensive fees or complicated steps.</h2>
                    <p class="mt-3 text-sm leading-relaxed text-slate-500">
                        Amra Property helps owners list their property, reach genuine buyers, and manage enquiries with a simpler online process.
                    </p>
                </div>

                <div class="grid gap-3">
                    @foreach ([
                        ['Trusted online sales partner', 'Look no further than Amra Property. With us anyone sell property online very easily.', 'shield-check'],
                        ['Wide exposure', 'We leverage SEO so your property gets maximum exposure across all your targeted locations.', 'search'],
                        ['Easy listing process', 'Share your property details via WhatsApp or fill out our user-friendly forms.', 'clipboard-list'],
                        ['Marketing & promotion', 'We promote your property through targeted campaigns on Google, Facebook, Instagram, and Twitter.', 'megaphone'],
                    ] as $item)
                        <div class="flex gap-4 rounded-2xl border border-slate-100 bg-slate-50 p-4">
                            <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-white text-amra-primary shadow-sm">
                                <i data-lucide="{{ $item[2] }}" class="h-5 w-5"></i>
                            </span>
                            <div>
                                <h3 class="font-serif text-base font-extrabold text-slate-900">{{ $item[0] }}</h3>
                                <p class="mt-1 text-sm leading-relaxed text-slate-500">{{ $item[1] }}</p>
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="mt-6 flex flex-col gap-3 rounded-2xl bg-teal-50 p-5 sm:flex-row sm:items-center sm:justify-between">
                    <p class="text-sm font-semibold leading-relaxed text-slate-700">Join our growing community of satisfied sellers and let us help you make your property sale a resounding success.</p>
                    <a href="{{ $whatsappUrl }}" target="_blank" rel="noopener noreferrer" class="inline-flex shrink-0 items-center justify-center gap-2 rounded-xl bg-amra-primary px-5 py-3 text-xs font-extrabold text-slate-950 hover:bg-teal-300">
                        <i data-lucide="send" class="h-4 w-4"></i>
                        Start Listing
                    </a>
                </div>
            </article>
        </div>
    </section>

    <section class="bg-white py-16">
        <div class="mx-auto max-w-7xl px-6">
            <div class="mb-8 overflow-hidden rounded-3xl border border-teal-100 bg-teal-50 shadow-sm">
                <img src="{{ $freeListingBanner }}" alt="100 percent free listing on Amra Property" class="h-auto w-full object-cover">
            </div>
            <div class="grid gap-5 md:grid-cols-3">
                @foreach ([
                    ['01', 'By clicking on "List Your Property Online," a form will pop up.'],
                    ['02', 'Enter your property details, such as location, area, name, and an image you want to show your clients.'],
                    ['03', "After receiving your details, we'll verify them and publish your listing within 24 hours."],
                ] as $step)
                    <div class="rounded-2xl border border-slate-200 bg-slate-50 p-7">
                        <div class="mb-4 font-serif text-4xl font-extrabold text-amra-primary">{{ $step[0] }}</div>
                        <h3 class="text-base font-serif font-extrabold leading-snug text-slate-900">{{ $step[1] }}</h3>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <section class="py-16">
        <div class="mx-auto max-w-7xl px-6">
            <div class="grid gap-6 lg:grid-cols-2">
                <article class="overflow-hidden rounded-3xl border border-slate-200 bg-white shadow-sm">
                    <img src="{{ $whatsappVisual }}" alt="Share property details on WhatsApp" class="h-56 w-full object-cover">
                    <div class="p-8">
                        <h2 class="mb-2 text-2xl font-serif font-extrabold text-slate-900">Sell Property online with 3 simple steps</h2>
                        <h3 class="mb-6 text-base font-bold text-slate-700">Tips to Get Higher and Quality Leads for Your Property</h3>
                        <div class="grid gap-5 md:grid-cols-3 lg:grid-cols-1">
                            <div><h4 class="mb-2 font-bold text-slate-900">Provide Proper Details:</h4><p class="text-sm text-slate-500">Include all relevant information about the property, such as the project name, location, nearby landmarks, area, and price.</p></div>
                            <div><h4 class="mb-2 font-bold text-slate-900">Good Quality Images:</h4><p class="text-sm text-slate-500">Use high-quality and real images to attract potential buyers.</p></div>
                            <div><h4 class="mb-2 font-bold text-slate-900">Locality:</h4><p class="text-sm text-slate-500">Provide accurate details about the locality, including amenities and surrounding areas.</p></div>
                        </div>
                    </div>
                </article>
                <article class="overflow-hidden rounded-3xl border border-slate-200 bg-white shadow-sm">
                    <img src="{{ $listingVisual }}" alt="Property listing benefits" class="h-56 w-full object-cover">
                    <div class="p-8">
                        <h2 class="mb-6 text-2xl font-serif font-extrabold text-slate-900">Benefits of Listing Property Online</h2>
                        <div class="grid gap-5 md:grid-cols-3 lg:grid-cols-1">
                            <div><h3 class="mb-2 font-bold text-slate-900">Better Exposure:</h3><p class="text-sm text-slate-500">Gain greater visibility by showcasing your property to potential buyers. Nowadays, people often search for properties online.</p></div>
                            <div><h3 class="mb-2 font-bold text-slate-900">Affordability:</h3><p class="text-sm text-slate-500">Selling property online is much cheaper than traditional methods, allowing you to reach a large number of customers at a lower cost.</p></div>
                            <div><h3 class="mb-2 font-bold text-slate-900">Time-Saving:</h3><p class="text-sm text-slate-500">Online promotions enable you to reach a large number of buyers quickly and efficiently.</p></div>
                        </div>
                    </div>
                </article>
            </div>
        </div>
    </section>

    <section class="bg-white py-16 border-t border-slate-200">
        <div class="mx-auto max-w-7xl px-6">
            <div class="mb-10">
                <p class="mb-3 text-[11px] font-extrabold uppercase tracking-[0.28em] text-amra-primary">Market Guide</p>
                <h2 class="text-3xl font-serif font-extrabold leading-tight text-slate-900 md:text-4xl">Real Estate Insights & Overview</h2>
            </div>
            <div class="grid gap-6 md:grid-cols-3">
                <!-- Card 1: Lucknow Overview -->
                <div class="overflow-hidden rounded-3xl border border-slate-200 bg-slate-50 shadow-sm">
                    <img src="{{ asset('assets/images/prop1.webp') }}" alt="Lucknow property overview" class="h-44 w-full object-cover">
                    <div class="p-8">
                    <h3 class="mb-4 font-serif text-xl font-extrabold text-slate-900 leading-snug">Sell Property Online Easily & Faster With Amra Property</h3>
                    <h4 class="mb-4 text-xs font-bold uppercase tracking-wider text-slate-400">Best Property in Lucknow for sale</h4>
                    <div class="space-y-4 text-sm leading-relaxed text-slate-500">
                        <p>Are you looking to rent or buy a <a href="{{ route('site.property', ['location' => 'lucknow']) }}" class="font-bold text-amra-primary hover:underline">properties in Lucknow</a> ? <a href="{{ route('site.home') }}" class="font-bold text-amra-primary hover:underline">Amra Property</a> can help you find the perfect place to call home. With a wide range of options available, you are sure to find something that suits your preferences and budget.</p>
                        <p>If you are interested in purchasing a <a href="{{ route('site.property', ['location' => 'lucknow']) }}" class="font-bold text-amra-primary hover:underline">house for sale in Lucknow</a> , Amra Property has several options available. We have both new and old houses for sale in various localities of the city. From spacious independent houses to cozy row houses, we have something to cater to all your needs. Additionally, we also have <a href="{{ route('site.property', ['location' => 'lucknow', 'type' => 'villa']) }}" class="font-bold text-amra-primary hover:underline">villas for sale in Lucknow</a> that offer luxurious living with ample space and amenities.</p>
                    </div>
                    </div>
                </div>

                <!-- Card 2: Lucknow Flats & Plots -->
                <div class="overflow-hidden rounded-3xl border border-slate-200 bg-slate-50 shadow-sm">
                    <img src="{{ asset('assets/images/prop2.jpeg') }}" alt="Flats and plots listing" class="h-44 w-full object-cover">
                    <div class="p-8">
                    <h3 class="mb-4 font-serif text-xl font-extrabold text-slate-900 leading-snug">Amra Property - Buy house for sale in Lucknow</h3>
                    <h4 class="mb-4 text-xs font-bold uppercase tracking-wider text-slate-400">Flats & Plots in Lucknow</h4>
                    <div class="space-y-4 text-sm leading-relaxed text-slate-500">
                        <p>For those looking for flats, we have both <a href="{{ route('site.property', ['q' => '2BHK Lucknow']) }}" class="font-bold text-amra-primary hover:underline">2 BHK</a> and <a href="{{ route('site.property', ['q' => '3BHK Lucknow']) }}" class="font-bold text-amra-primary hover:underline">3 BHK flats in Lucknow</a> . Whether you are a small family or a large one, we have flats that cater to all your requirements. If you are interested in renting, we also have <a href="{{ route('site.property', ['q' => '2BHK Lucknow']) }}" class="font-bold text-amra-primary hover:underline">2 BHK flats in Lucknow</a> available for rent.</p>
                        <p>If you are looking for a plot of land to build your dream home, we have you covered. We have LDA approved plots in Lucknow in various sizes and locations. Our team of experts can help you choose the perfect plot that suits your needs and budget.</p>
                    </div>
                    </div>
                </div>

                <!-- Card 3: Mumbai & Market Context -->
                <div class="overflow-hidden rounded-3xl border border-slate-200 bg-slate-50 shadow-sm">
                    <img src="{{ asset('assets/images/prop3.png') }}" alt="Mumbai property market" class="h-44 w-full object-cover">
                    <div class="p-8">
                    <h3 class="mb-4 font-serif text-xl font-extrabold text-slate-900 leading-snug">Flat in Mumbai - Amra Property</h3>
                    <h4 class="mb-4 text-xs font-bold uppercase tracking-wider text-slate-400">Mumbai Properties & Market Context</h4>
                    <div class="space-y-4 text-sm leading-relaxed text-slate-500">
                        <p>One of the most sought-after property types in Mumbai is the flat, and Amra Property specializes in helping you find your ideal flat in this dynamic city. Whether you're looking for a cozy 1BHK flat in mumbai or a more spacious 2BHK flat in mumbai, our extensive listings cater to a wide range of preferences and budgets.</p>
                        <p>At Amra Property, we understand the importance of finding the perfect home. We strive to provide our clients with the best real estate solutions that meet their requirements. With our in-depth knowledge of the <a href="{{ route('site.home') }}" class="font-bold text-amra-primary hover:underline">Lucknow real estate</a> market, we can provide you with accurate information on Lucknow <a href="{{ route('site.property', ['location' => 'lucknow', 'type' => 'plot']) }}" class="font-bold text-amra-primary hover:underline">plot</a> rates, flat prices, and more.</p>
                    </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="bg-slate-50 py-16 border-t border-slate-200">
        <div class="mx-auto max-w-5xl px-6">
            <div class="mb-8 text-center">
                <p class="mb-3 text-[11px] font-extrabold uppercase tracking-[0.28em] text-amra-primary">Questions</p>
                <h2 class="text-3xl font-serif font-extrabold text-slate-900 md:text-4xl">Frequently Asked Questions</h2>
            </div>
            <div class="space-y-4">
                @foreach ($faqItems as $faq)
                    <details class="group rounded-2xl border border-slate-200 bg-white p-5 shadow-sm" @if($loop->first) open @endif>
                        <summary class="flex cursor-pointer items-center justify-between gap-4 font-serif font-extrabold text-slate-900">
                            {{ $faq['question'] }}
                            <i data-lucide="chevron-down" class="h-4 w-4 text-slate-400 transition-transform group-open:rotate-180"></i>
                        </summary>
                        <div class="mt-4 border-t border-slate-100 pt-4">
                            <p class="text-sm font-semibold leading-relaxed text-slate-700">{{ $faq['answer'] }}</p>
                            <div class="mt-4 grid gap-2">
                                @foreach($faq['points'] as $point)
                                    <div class="flex gap-3 rounded-xl bg-slate-50 p-3">
                                        <span class="mt-0.5 flex h-5 w-5 shrink-0 items-center justify-center rounded-full bg-teal-50 text-amra-primary">
                                            <i data-lucide="check" class="h-3 w-3"></i>
                                        </span>
                                        <p class="text-sm leading-relaxed text-slate-500">{{ $point }}</p>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </details>
                @endforeach
            </div>
        </div>
    </section>
</div>
@endsection
