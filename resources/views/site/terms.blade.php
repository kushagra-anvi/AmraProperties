@extends('layouts.site')

@section('title', 'Terms of Service - Amra Property')
@section('meta_description', 'Terms of service and listing usage policies for Amra Property users.')

@section('content')
<div class="pt-32 pb-24">
<div class="max-w-4xl mx-auto px-6">
            
            <!-- Page Header -->
            <div class="text-center mb-12">
                <p class="text-amra-primary font-bold uppercase tracking-[0.25em] text-xs mb-3">Legal Documentation</p>
                <h1 class="text-4xl md:text-5xl font-serif font-bold text-amra-dark">Terms of <span class="text-amra-primary italic">Service</span></h1>
                <p class="text-gray-400 text-sm mt-3">Last Updated: May 8, 2026</p>
            </div>

            <!-- Content Card -->
            <div class="bg-white p-8 md:p-12 rounded-3xl shadow-sm border border-gray-100 space-y-8 text-slate-600 leading-relaxed">
                
                <section>
                    <h2 class="text-2xl font-serif font-bold text-amra-dark mb-4 flex items-center gap-2">
                        <i data-lucide="scale" class="w-6 h-6 text-amra-primary"></i> 1. Acceptance of Terms
                    </h2>
                    <p>
                        By accessing, browsing, or using the real estate portal hosted on Amra Property ("Website", "Portal", "Services"), you acknowledge that you have read, understood, and agree to be bound by these Terms of Service. If you do not agree to these terms, you must immediately cease using our services.
                    </p>
                    <p class="mt-2">
                        We reserve the right to revise or update these terms at any time without prior notice. Continued use of our Portal following modifications constitutes your acceptance of the updated terms.
                    </p>
                </section>

                <hr class="border-gray-100">

                <section>
                    <h2 class="text-2xl font-serif font-bold text-amra-dark mb-4 flex items-center gap-2">
                        <i data-lucide="home" class="w-6 h-6 text-amra-primary"></i> 2. Listing Policy & Accuracy
                    </h2>
                    <p>
                        Amra Property is a premium connection platform. We cooperate exclusively with RERA-registered projects and verified builders (such as Shalimar, Eldeco, Lodha, Godrej, Rustomjee, Adani, Kalpataru).
                    </p>
                    <p class="mt-3">
                        While we strive to ensure 100% genuine listings and exclude duplicates or fake pricing:
                    </p>
                    <ul class="space-y-2 list-disc pl-5 mt-2">
                        <li>All price quotes, sizes, floorplans, and amenity details are indicative and subject to change without notice by the respective builder.</li>
                        <li>Images, render models, and simulated walkthroughs are artistic illustrations and do not represent contractual promises.</li>
                        <li>Users are strongly advised to verify the respective projects' official registrations on the state's RERA portals (MahaRERA or UP RERA) prior to booking.</li>
                    </ul>
                </section>

                <hr class="border-gray-100">

                <section>
                    <h2 class="text-2xl font-serif font-bold text-amra-dark mb-4 flex items-center gap-2">
                        <i data-lucide="user-check" class="w-6 h-6 text-amra-primary"></i> 3. User Conduct & Accounts
                    </h2>
                    <p class="mb-4">When using our Portal, you agree not to commit actions that damage our platform, such as:</p>
                    <ul class="space-y-2.5 list-disc pl-5">
                        <li>Submitting false contact coordinates, fake identities, or deceptive inquiries.</li>
                        <li>Scraping property lists or database structures using automatic crawler bots.</li>
                        <li>Posting unauthorized ad spaces, copyright infringement material, or malicious code.</li>
                        <li>Impersonating Amra Property agents, employees, or registered developers.</li>
                    </ul>
                </section>

                <hr class="border-gray-100">

                <section>
                    <h2 class="text-2xl font-serif font-bold text-amra-dark mb-4 flex items-center gap-2">
                        <i data-lucide="alert-triangle" class="w-6 h-6 text-amra-primary"></i> 4. Limitation of Liability
                    </h2>
                    <p>
                        To the maximum extent permitted by applicable laws, Amra Property, its directors, and advisors shall not be liable for any direct, indirect, incidental, or consequential damages resulting from your use of the website or transactions entered into with third-party developers listed on our portal.
                    </p>
                    <p class="mt-2 text-sm italic font-medium">
                        All property transactions are separate contracts signed directly between the prospective buyers and the developer. Amra Property serves as a marketing catalyst and connection facilitator and is not a signing counterparty to booking agreements.
                    </p>
                </section>

                <hr class="border-gray-100">

                <section>
                    <h2 class="text-2xl font-serif font-bold text-amra-dark mb-4 flex items-center gap-2">
                        <i data-lucide="gavel" class="w-6 h-6 text-amra-primary"></i> 5. Governing Law
                    </h2>
                    <p>
                        These Terms of Service are governed by and construed in accordance with the federal and state laws of India. Any litigation, conflicts, or disputes arising from these Terms or use of our real estate portal shall be subject to the exclusive jurisdiction of the competent courts in Lucknow or Mumbai, India.
                    </p>
                </section>

            </div>

        </div>
</div>
@endsection
