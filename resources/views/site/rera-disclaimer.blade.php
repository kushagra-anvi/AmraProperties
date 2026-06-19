@extends('layouts.site')

@section('title', 'RERA Disclaimer - Amra Property')
@section('meta_description', 'RERA Disclaimer for Amra Property - an online real estate marketing and property listing platform.')

@section('content')
<div class="pt-32 pb-24">
<div class="max-w-4xl mx-auto px-6">
            
            <!-- Page Header -->
            <div class="text-center mb-12">
                <p class="text-amra-primary font-bold uppercase tracking-[0.25em] text-xs mb-3">Legal Documentation</p>
                <h1 class="text-4xl md:text-5xl font-serif font-bold text-amra-dark">RERA <span class="text-amra-primary italic">Disclaimer</span></h1>
                <p class="text-gray-400 text-sm mt-3">Last Updated: March 10, 2026</p>
            </div>

            <!-- Content Card -->
            <div class="bg-white p-8 md:p-12 rounded-3xl shadow-sm border border-gray-100 space-y-8 text-slate-600 leading-relaxed">
                
                <section>
                    <h2 class="text-2xl font-serif font-bold text-amra-dark mb-4 flex items-center gap-2">
                        <i data-lucide="building-2" class="w-6 h-6 text-amra-primary"></i> Nature of Platform
                    </h2>
                    <p>
                        Amra Property is an online real estate marketing and property listing platform that allows developers, builders, agents, brokers, and property owners to advertise their properties.
                    </p>
                    <p class="mt-3">
                        We provide property listing, marketing, and lead generation services only.
                    </p>
                    <p class="mt-3 font-semibold text-amra-dark">
                        We do not act as a real estate broker or agent and do not participate in any property transactions.
                    </p>
                </section>

                <hr class="border-gray-100">

                <section>
                    <h2 class="text-2xl font-serif font-bold text-amra-dark mb-4 flex items-center gap-2">
                        <i data-lucide="info" class="w-6 h-6 text-amra-primary"></i> Property Information
                    </h2>
                    <p class="mb-4">All property details published on the platform are provided by the respective advertiser. This includes:</p>
                    <ul class="space-y-2.5 list-disc pl-5">
                        <li>Property location</li>
                        <li>Pricing</li>
                        <li>Carpet area or built-up area</li>
                        <li>Floor plans and specifications</li>
                        <li>Amenities</li>
                        <li>Possession timeline</li>
                        <li>Images and descriptions</li>
                    </ul>
                    <p class="mt-4">
                        Amra Property does not independently verify all listing details. Users should verify all property information directly with the advertiser or through official sources before making any real estate decision.
                    </p>
                </section>

                <hr class="border-gray-100">

                <section>
                    <h2 class="text-2xl font-serif font-bold text-amra-dark mb-4 flex items-center gap-2">
                        <i data-lucide="shield-check" class="w-6 h-6 text-amra-primary"></i> RERA Compliance
                    </h2>
                    <p>
                        All advertised real estate projects that require registration must be registered with the respective State Real Estate Regulatory Authority (RERA) as per the Real Estate (Regulation and Development) Act, 2016.
                    </p>
                    <p class="mt-3">
                        For projects located in Maharashtra, registration with MahaRERA is mandatory where applicable.
                    </p>
                    <p class="mt-3">
                        Advertisers are responsible for ensuring their projects comply with applicable RERA requirements and for providing valid RERA registration numbers.
                    </p>
                    <p class="mt-3">
                        Amra Property shall not be held responsible for verifying the legal compliance of all listings displayed on the platform.
                    </p>
                </section>

                <hr class="border-gray-100">

                <section>
                    <h2 class="text-2xl font-serif font-bold text-amra-dark mb-4 flex items-center gap-2">
                        <i data-lucide="ban" class="w-6 h-6 text-amra-primary"></i> No Brokerage or Transaction Role
                    </h2>
                    <p class="mb-4">Amra Property does not:</p>
                    <ul class="space-y-2.5 list-disc pl-5">
                        <li>Negotiate property deals</li>
                        <li>Collect booking amounts or payments</li>
                        <li>Participate in property transactions</li>
                        <li>Provide brokerage services</li>
                    </ul>
                    <p class="mt-4">
                        All property-related communication and transactions take place directly between the user and the property advertiser.
                    </p>
                </section>

                <hr class="border-gray-100">

                <section>
                    <h2 class="text-2xl font-serif font-bold text-amra-dark mb-4 flex items-center gap-2">
                        <i data-lucide="alert-triangle" class="w-6 h-6 text-amra-primary"></i> Limitation of Liability
                    </h2>
                    <p class="mb-4">Amra Property is not responsible for:</p>
                    <ul class="space-y-2.5 list-disc pl-5">
                        <li>Incorrect property information provided by advertisers</li>
                        <li>Disputes between buyers and sellers</li>
                        <li>Financial losses arising from property transactions</li>
                        <li>Delays in project delivery or possession</li>
                    </ul>
                    <p class="mt-4">
                        Users must perform their own due diligence before investing in any property.
                    </p>
                </section>

                <hr class="border-gray-100">

                <section>
                    <h2 class="text-2xl font-serif font-bold text-amra-dark mb-4 flex items-center gap-2">
                        <i data-lucide="gavel" class="w-6 h-6 text-amra-primary"></i> Governing Law
                    </h2>
                    <p>
                        This disclaimer shall be governed by the laws of India. Any disputes shall fall under the jurisdiction of courts located in Mumbai, Maharashtra.
                    </p>
                </section>

            </div>

        </div>
</div>
@endsection
