@extends('layouts.site')

@section('title', 'Privacy Policy - Amra Property')
@section('meta_description', 'Privacy Policy and data protection terms for Amra Property users.')

@section('content')
<div class="pt-32 pb-24">
<div class="max-w-4xl mx-auto px-6">
            
            <!-- Page Header -->
            <div class="text-center mb-12">
                <p class="text-amra-primary font-bold uppercase tracking-[0.25em] text-xs mb-3">Legal Documentation</p>
                <h1 class="text-4xl md:text-5xl font-serif font-bold text-amra-dark">Privacy <span class="text-amra-primary italic">Policy</span></h1>
                <p class="text-gray-400 text-sm mt-3">Last Updated: May 8, 2026</p>
            </div>

            <!-- Content Card -->
            <div class="bg-white p-8 md:p-12 rounded-3xl shadow-sm border border-gray-100 space-y-8 text-slate-600 leading-relaxed">
                
                <section>
                    <h2 class="text-2xl font-serif font-bold text-amra-dark mb-4 flex items-center gap-2">
                        <i data-lucide="shield-check" class="w-6 h-6 text-amra-primary"></i> 1. Introduction
                    </h2>
                    <p>
                        Welcome to Amra Property. Your privacy is of paramount importance to us. This Privacy Policy describes how Amra Property ("we", "us", "our") collects, uses, processes, and protects your personal information when you use our website and services in Mumbai, Lucknow, and nationally.
                    </p>
                    <p class="mt-2">
                        By accessing our portal or interacting with our listings, you consent to the data practices described in this policy.
                    </p>
                </section>

                <hr class="border-gray-100">

                <section>
                    <h2 class="text-2xl font-serif font-bold text-amra-dark mb-4 flex items-center gap-2">
                        <i data-lucide="database" class="w-6 h-6 text-amra-primary"></i> 2. Information We Collect
                    </h2>
                    <p class="mb-4">We collect personal information that you voluntarily provide to us when registering, inquiring about properties, or subscribing to our newsletters. This includes:</p>
                    <ul class="space-y-2.5 list-disc pl-5">
                        <li><strong>Contact Information:</strong> Full name, email address, phone number, and physical address.</li>
                        <li><strong>Property Requirements:</strong> Preferred locations, budget parameters, property types (flat, villa, plot), and transaction intents.</li>
                        <li><strong>Verification Details:</strong> Government-issued ID or developer license numbers where required for secure listings.</li>
                        <li><strong>Automatic Technical Data:</strong> IP address, browser type, device information, and pages visited via functional cookies.</li>
                    </ul>
                </section>

                <hr class="border-gray-100">

                <section>
                    <h2 class="text-2xl font-serif font-bold text-amra-dark mb-4 flex items-center gap-2">
                        <i data-lucide="settings" class="w-6 h-6 text-amra-primary"></i> 3. How We Use Your Data
                    </h2>
                    <p class="mb-4">We use the gathered data to deliver premium real estate services with uncompromising trust and seamless experiences. Key usages include:</p>
                    <ul class="space-y-2.5 list-disc pl-5">
                        <li>Facilitating direct inquiries between buyers, sellers, and verified developers.</li>
                        <li>Personalizing your home-hunting feed and recommendation engines.</li>
                        <li>Sending periodic property updates, market analysis reports, and newsletters.</li>
                        <li>Monitoring system traffic, resolving load issues, and improving platform performance.</li>
                        <li>Maintaining absolute regulatory and RERA-compliance protocols.</li>
                    </ul>
                </section>

                <hr class="border-gray-100">

                <section>
                    <h2 class="text-2xl font-serif font-bold text-amra-dark mb-4 flex items-center gap-2">
                        <i data-lucide="users" class="w-6 h-6 text-amra-primary"></i> 4. Sharing Your Information
                    </h2>
                    <p>
                        We do not sell, rent, or lease your personal information to third-party marketing companies. 
                    </p>
                    <p class="mt-3">
                        Your information is only shared with our verified partner builders (such as Shalimar Group, Eldeco, Lodha, Godrej, Rustomjee, etc.) and RERA-registered real estate advisors, and only when you explicitly submit a contact form requesting detailed consultation or site visits.
                    </p>
                </section>

                <hr class="border-gray-100">

                <section>
                    <h2 class="text-2xl font-serif font-bold text-amra-dark mb-4 flex items-center gap-2">
                        <i data-lucide="lock" class="w-6 h-6 text-amra-primary"></i> 5. Data Security
                    </h2>
                    <p>
                        We deploy industry-standard SSL encryption and firewall protocols to secure your transmission channels and database storage vaults. While no method of digital transmission is 100% secure, we apply rigorous administrative and technical safe-guards to mitigate risks of unauthorized breaches, leakage, or modification.
                    </p>
                </section>

                <hr class="border-gray-100">

                <section>
                    <h2 class="text-2xl font-serif font-bold text-amra-dark mb-4 flex items-center gap-2">
                        <i data-lucide="help-circle" class="w-6 h-6 text-amra-primary"></i> 6. Your Rights & Contacts
                    </h2>
                    <p>
                        You have the right to request access to the personal data we hold about you, request modification of inaccurate information, or opt-out of our subscription marketing databases.
                    </p>
                    <p class="mt-4">
                        If you have any questions or feedback regarding this Privacy Policy, please reach out to our legal officer at:
                    </p>
                    <div class="mt-4 p-5 bg-slate-50 rounded-2xl border border-slate-100 inline-block font-medium">
                        <p class="text-amra-dark">Amra Property Legal Officer</p>
                        <p class="text-sm">Email: <a href="mailto:legal@amraproperty.com" class="text-amra-primary hover:underline">legal@amraproperty.com</a></p>
                        <p class="text-sm">Address: Lucknow & Mumbai Central Corporate Hubs, India</p>
                    </div>
                </section>

            </div>

        </div>
</div>
@endsection
