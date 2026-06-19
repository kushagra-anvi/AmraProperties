@extends('layouts.site')

@section('title', 'Advertiser Agreement - Amra Property')
@section('meta_description', 'This Advertiser Agreement governs the relationship between Amra Property and any developer, builder, broker, agent, or property owner who lists properties on the platform.')

@section('content')
<div class="pt-32 pb-24">
<div class="max-w-4xl mx-auto px-6">
            
            <!-- Page Header -->
            <div class="text-center mb-12">
                <p class="text-amra-primary font-bold uppercase tracking-[0.25em] text-xs mb-3">Legal Documentation</p>
                <h1 class="text-4xl md:text-5xl font-serif font-bold text-amra-dark">Advertiser <span class="text-amra-primary italic">Agreement</span></h1>
                <p class="text-gray-400 text-sm mt-3">Last Updated: March 10, 2026</p>
            </div>

            <!-- Content Card -->
            <div class="bg-white p-8 md:p-12 rounded-3xl shadow-sm border border-gray-100 space-y-8 text-slate-600 leading-relaxed">
                
                <p>
                    This Advertiser Agreement governs the relationship between <a href="{{ route('site.home') }}" class="text-amra-primary hover:underline font-semibold">Amra Property</a> and any developer, builder, broker, agent, or property owner ("Advertiser") who lists properties or advertises on the website.
                </p>
                <p>
                    By listing properties or purchasing advertising services on the platform, the Advertiser agrees to the following terms.
                </p>

                <hr class="border-gray-100">

                <section>
                    <h2 class="text-2xl font-serif font-bold text-amra-dark mb-4 flex items-center gap-2">
                        <i data-lucide="building-2" class="w-6 h-6 text-amra-primary"></i> 1. Nature of Platform
                    </h2>
                    <p>
                        Amra Property is an online real estate listing, marketing, and lead generation platform operating across multiple cities in India.
                    </p>
                    <p class="mt-3">
                        The platform enables developers, builders, brokers, agents, and property owners to advertise their properties and receive inquiries from potential buyers.
                    </p>
                    <p class="mt-3">
                        Amra Property provides advertising, marketing, and lead generation services only and does not act as a real estate broker or intermediary in property transactions.
                    </p>
                </section>

                <hr class="border-gray-100">

                <section>
                    <h2 class="text-2xl font-serif font-bold text-amra-dark mb-4 flex items-center gap-2">
                        <i data-lucide="user-check" class="w-6 h-6 text-amra-primary"></i> 2. Advertiser Eligibility
                    </h2>
                    <p class="mb-4">The following entities may advertise properties on the platform:</p>
                    <ul class="space-y-2.5 list-disc pl-5">
                        <li>Real estate developers</li>
                        <li>Builders</li>
                        <li>Real estate brokers</li>
                        <li>Real estate agents</li>
                        <li>Property owners</li>
                    </ul>
                    <p class="mt-4">
                        Advertisers must ensure they have the legal authority to market or advertise the property listed on the platform.
                    </p>
                </section>

                <hr class="border-gray-100">

                <section>
                    <h2 class="text-2xl font-serif font-bold text-amra-dark mb-4 flex items-center gap-2">
                        <i data-lucide="check-circle" class="w-6 h-6 text-amra-primary"></i> 3. Accuracy of Listings
                    </h2>
                    <p class="mb-4">
                        Advertisers are solely responsible for ensuring that all information provided on the platform is accurate and truthful. This includes:
                    </p>
                    <ul class="space-y-2.5 list-disc pl-5">
                        <li>Property location</li>
                        <li>Pricing</li>
                        <li>Carpet area or built-up area</li>
                        <li>Project specifications</li>
                        <li>Amenities</li>
                        <li>Possession timeline</li>
                        <li>Images and descriptions</li>
                    </ul>
                    <p class="mt-4">
                        Providing false, misleading, or fraudulent information is strictly prohibited. Amra Property reserves the right to remove any listing that violates this policy.
                    </p>
                </section>

                <hr class="border-gray-100">

                <section>
                    <h2 class="text-2xl font-serif font-bold text-amra-dark mb-4 flex items-center gap-2">
                        <i data-lucide="shield-check" class="w-6 h-6 text-amra-primary"></i> 4. RERA Compliance
                    </h2>
                    <p>
                        Advertisers must comply with the Real Estate (Regulation and Development) Act, 2016 and other applicable laws.
                    </p>
                    <p class="mt-3">
                        Projects that require registration must be registered with the respective State Real Estate Regulatory Authority (RERA). For projects located in Maharashtra, registration with MahaRERA is mandatory where applicable.
                    </p>
                    <p class="mt-3">
                        Advertisers must provide valid RERA registration numbers where required. Amra Property shall not be responsible for verifying the legal compliance of all listings.
                    </p>
                </section>

                <hr class="border-gray-100">

                <section>
                    <h2 class="text-2xl font-serif font-bold text-amra-dark mb-4 flex items-center gap-2">
                        <i data-lucide="megaphone" class="w-6 h-6 text-amra-primary"></i> 5. Marketing and Lead Generation
                    </h2>
                    <p class="mb-4">Amra Property may promote property listings through various marketing channels including:</p>
                    <ul class="space-y-2.5 list-disc pl-5">
                        <li>Website promotion</li>
                        <li>Google Ads</li>
                        <li>Social media platforms</li>
                        <li>Meta (Facebook and Instagram) advertising</li>
                    </ul>
                    <p class="mt-4">
                        Leads generated from these marketing campaigns will be shared directly with the advertiser. Amra Property does not guarantee a specific number of leads or inquiries.
                    </p>
                </section>

                <hr class="border-gray-100">

                <section>
                    <h2 class="text-2xl font-serif font-bold text-amra-dark mb-4 flex items-center gap-2">
                        <i data-lucide="phone-forwarded" class="w-6 h-6 text-amra-primary"></i> 6. Lead Handling Responsibility
                    </h2>
                    <p class="mb-4">
                        Advertisers are responsible for managing and responding to leads generated through the platform. Amra Property is not responsible for:
                    </p>
                    <ul class="space-y-2.5 list-disc pl-5">
                        <li>Follow-up communication</li>
                        <li>Site visit arrangements</li>
                        <li>Negotiations</li>
                        <li>Property transactions</li>
                    </ul>
                    <p class="mt-4">
                        All such interactions occur directly between the advertiser and the potential buyer.
                    </p>
                </section>

                <hr class="border-gray-100">

                <section>
                    <h2 class="text-2xl font-serif font-bold text-amra-dark mb-4 flex items-center gap-2">
                        <i data-lucide="credit-card" class="w-6 h-6 text-amra-primary"></i> 7. Subscription and Payments
                    </h2>
                    <p class="mb-4">
                        Amra Property operates on a subscription-based advertising model. Advertisers may purchase subscription plans that include:
                    </p>
                    <ul class="space-y-2.5 list-disc pl-5">
                        <li>Property listings</li>
                        <li>Marketing services</li>
                        <li>Lead generation services</li>
                    </ul>
                    <p class="mt-4">
                        Subscription fees are non-refundable, unless otherwise specified.
                    </p>
                </section>

                <hr class="border-gray-100">

                <section>
                    <h2 class="text-2xl font-serif font-bold text-amra-dark mb-4 flex items-center gap-2">
                        <i data-lucide="ban" class="w-6 h-6 text-amra-primary"></i> 8. Prohibited Activities
                    </h2>
                    <p class="mb-4">Advertisers are prohibited from:</p>
                    <ul class="space-y-2.5 list-disc pl-5">
                        <li>Posting fake listings</li>
                        <li>Misrepresenting property information</li>
                        <li>Advertising properties without authorization</li>
                        <li>Violating applicable laws or regulations</li>
                        <li>Using the platform for fraudulent activities</li>
                    </ul>
                    <p class="mt-4">
                        Violation of these rules may result in immediate suspension or removal of listings.
                    </p>
                </section>

                <hr class="border-gray-100">

                <section>
                    <h2 class="text-2xl font-serif font-bold text-amra-dark mb-4 flex items-center gap-2">
                        <i data-lucide="alert-triangle" class="w-6 h-6 text-amra-primary"></i> 9. Limitation of Liability
                    </h2>
                    <p class="mb-4">Amra Property shall not be liable for:</p>
                    <ul class="space-y-2.5 list-disc pl-5">
                        <li>Disputes between buyers and advertisers</li>
                        <li>Financial losses from property transactions</li>
                        <li>Inaccurate information provided by advertisers</li>
                        <li>Delays in lead delivery or marketing campaigns</li>
                    </ul>
                    <p class="mt-4">
                        Advertisers agree to indemnify and hold Amra Property harmless from any legal claims arising from property listings.
                    </p>
                </section>

                <hr class="border-gray-100">

                <section>
                    <h2 class="text-2xl font-serif font-bold text-amra-dark mb-4 flex items-center gap-2">
                        <i data-lucide="copyright" class="w-6 h-6 text-amra-primary"></i> 10. Intellectual Property
                    </h2>
                    <p class="mb-4">All content on the Amra Property website, including:</p>
                    <ul class="space-y-2.5 list-disc pl-5">
                        <li>Website design</li>
                        <li>Branding</li>
                        <li>Logos</li>
                        <li>Text and graphics</li>
                    </ul>
                    <p class="mt-4">
                        are the intellectual property of Amra Property. Advertisers may not reproduce or distribute website content without permission.
                    </p>
                </section>

                <hr class="border-gray-100">

                <section>
                    <h2 class="text-2xl font-serif font-bold text-amra-dark mb-4 flex items-center gap-2">
                        <i data-lucide="power-off" class="w-6 h-6 text-amra-primary"></i> 11. Suspension or Termination
                    </h2>
                    <p class="mb-4">Amra Property reserves the right to:</p>
                    <ul class="space-y-2.5 list-disc pl-5">
                        <li>Remove property listings</li>
                        <li>Suspend advertiser accounts</li>
                        <li>Terminate services</li>
                    </ul>
                    <p class="mt-4">
                        if the advertiser violates any terms of this agreement.
                    </p>
                </section>

                <hr class="border-gray-100">

                <section>
                    <h2 class="text-2xl font-serif font-bold text-amra-dark mb-4 flex items-center gap-2">
                        <i data-lucide="refresh-cw" class="w-6 h-6 text-amra-primary"></i> 12. Modification of Agreement
                    </h2>
                    <p>
                        Amra Property may update this Advertiser Agreement from time to time. Advertisers are responsible for reviewing the updated agreement periodically.
                    </p>
                </section>

                <hr class="border-gray-100">

                <section>
                    <h2 class="text-2xl font-serif font-bold text-amra-dark mb-4 flex items-center gap-2">
                        <i data-lucide="gavel" class="w-6 h-6 text-amra-primary"></i> 13. Governing Law
                    </h2>
                    <p>
                        This agreement shall be governed by the laws of India. Any disputes shall fall under the jurisdiction of courts located in Mumbai, Maharashtra.
                    </p>
                </section>

            </div>

        </div>
</div>
@endsection
