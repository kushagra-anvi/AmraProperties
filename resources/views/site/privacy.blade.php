@extends('layouts.site')

@section('title', 'Privacy Policy - Amra Property')
@section('meta_description', 'At Amra Property, we respect your privacy and are committed to protecting the personal information you share with us.')

@section('content')
<div class="pt-32 pb-24">
<div class="max-w-4xl mx-auto px-6">
            
            <!-- Page Header -->
            <div class="text-center mb-12">
                <p class="text-amra-primary font-bold uppercase tracking-[0.25em] text-xs mb-3">Legal Documentation</p>
                <h1 class="text-4xl md:text-5xl font-serif font-bold text-amra-dark">Privacy <span class="text-amra-primary italic">Policy</span></h1>
                <p class="text-gray-400 text-sm mt-3">Last Updated: March 10, 2026</p>
            </div>

            <!-- Content Card -->
            <div class="bg-white p-8 md:p-12 rounded-3xl shadow-sm border border-gray-100 space-y-8 text-slate-600 leading-relaxed">
                
                <p>
                    At Amra Property, we respect your privacy and are committed to protecting the personal information you share with us. This Privacy Policy explains how we collect, use, and safeguard your data when you visit <a href="{{ route('site.home') }}" class="text-amra-primary hover:underline font-semibold">www.amraproperty.com</a>.
                </p>

                <hr class="border-gray-100">

                <section>
                    <h2 class="text-2xl font-serif font-bold text-amra-dark mb-4 flex items-center gap-2">
                        <i data-lucide="database" class="w-6 h-6 text-amra-primary"></i> Information We Collect
                    </h2>
                    <p class="mb-4">We may collect the following information when you use our platform:</p>
                    <ul class="space-y-2.5 list-disc pl-5">
                        <li><strong>Personal Information:</strong> Name, email address, phone number, and address when you submit inquiry forms or register on the platform.</li>
                        <li><strong>Property Preferences:</strong> Preferred locations, budget range, property type, and other search criteria.</li>
                        <li><strong>Usage Data:</strong> IP address, browser type, device information, and pages visited on our website.</li>
                        <li><strong>Cookies:</strong> We use cookies to improve your browsing experience and analyze website traffic.</li>
                    </ul>
                </section>

                <hr class="border-gray-100">

                <section>
                    <h2 class="text-2xl font-serif font-bold text-amra-dark mb-4 flex items-center gap-2">
                        <i data-lucide="settings" class="w-6 h-6 text-amra-primary"></i> How We Use Your Information
                    </h2>
                    <p class="mb-4">We use the information we collect for the following purposes:</p>
                    <ul class="space-y-2.5 list-disc pl-5">
                        <li>To provide and maintain our property listing services.</li>
                        <li>To share your inquiry with relevant property advertisers (developers, builders, brokers, agents).</li>
                        <li>To send property alerts, newsletters, and marketing communications.</li>
                        <li>To improve our website and user experience.</li>
                        <li>To comply with legal obligations.</li>
                    </ul>
                </section>

                <hr class="border-gray-100">

                <section>
                    <h2 class="text-2xl font-serif font-bold text-amra-dark mb-4 flex items-center gap-2">
                        <i data-lucide="users" class="w-6 h-6 text-amra-primary"></i> Sharing of Information
                    </h2>
                    <p>
                        We do not sell, rent, or lease your personal information to third-party marketing companies.
                    </p>
                    <p class="mt-3">
                        Your inquiry details may be shared with property advertisers (developers, builders, brokers, agents) to facilitate your property search. These advertisers may contact you directly regarding your inquiry.
                    </p>
                    <p class="mt-3">
                        We may also share your information with service providers who assist us in operating our website and conducting business.
                    </p>
                </section>

                <hr class="border-gray-100">

                <section>
                    <h2 class="text-2xl font-serif font-bold text-amra-dark mb-4 flex items-center gap-2">
                        <i data-lucide="lock" class="w-6 h-6 text-amra-primary"></i> Data Security
                    </h2>
                    <p>
                        We implement appropriate security measures to protect your personal information from unauthorized access, alteration, disclosure, or destruction.
                    </p>
                    <p class="mt-3">
                        However, no method of electronic transmission or storage is 100% secure. While we strive to protect your personal information, we cannot guarantee absolute security.
                    </p>
                </section>

                <hr class="border-gray-100">

                <section>
                    <h2 class="text-2xl font-serif font-bold text-amra-dark mb-4 flex items-center gap-2">
                        <i data-lucide="cookie" class="w-6 h-6 text-amra-primary"></i> Cookies
                    </h2>
                    <p>
                        Our website uses cookies to enhance your browsing experience. Cookies are small files stored on your device that help us understand how you use our website.
                    </p>
                    <p class="mt-3">
                        You can choose to disable cookies through your browser settings, but this may affect the functionality of our website.
                    </p>
                </section>

                <hr class="border-gray-100">

                <section>
                    <h2 class="text-2xl font-serif font-bold text-amra-dark mb-4 flex items-center gap-2">
                        <i data-lucide="external-link" class="w-6 h-6 text-amra-primary"></i> Third-Party Links
                    </h2>
                    <p>
                        Our website may contain links to third-party websites. We are not responsible for the privacy practices or content of these external sites. We encourage you to review the privacy policies of any third-party websites you visit.
                    </p>
                </section>

                <hr class="border-gray-100">

                <section>
                    <h2 class="text-2xl font-serif font-bold text-amra-dark mb-4 flex items-center gap-2">
                        <i data-lucide="help-circle" class="w-6 h-6 text-amra-primary"></i> Your Rights
                    </h2>
                    <p>
                        You have the right to access, correct, or delete your personal information. You may also opt out of receiving marketing communications from us at any time.
                    </p>
                    <p class="mt-4">
                        To exercise these rights, please contact us at:
                    </p>
                    <div class="mt-4 p-5 bg-slate-50 rounded-2xl border border-slate-100 inline-block font-medium">
                        <p class="text-amra-dark">Amra Property</p>
                        <p class="text-sm">Email: <a href="mailto:info@amraproperty.com" class="text-amra-primary hover:underline">info@amraproperty.com</a></p>
                        <p class="text-sm">Phone: <a href="tel:+919559992958" class="text-amra-primary hover:underline">+91 9559992958</a></p>
                        <p class="text-sm">Address: 310, Wing C, Rupa Solitaire, MBP, Navi Mumbai</p>
                    </div>
                </section>

                <hr class="border-gray-100">

                <section>
                    <h2 class="text-2xl font-serif font-bold text-amra-dark mb-4 flex items-center gap-2">
                        <i data-lucide="refresh-cw" class="w-6 h-6 text-amra-primary"></i> Changes to This Policy
                    </h2>
                    <p>
                        We may update this Privacy Policy from time to time. Any changes will be posted on this page. We encourage you to review this Privacy Policy periodically.
                    </p>
                </section>

                <hr class="border-gray-100">

                <section>
                    <h2 class="text-2xl font-serif font-bold text-amra-dark mb-4 flex items-center gap-2">
                        <i data-lucide="gavel" class="w-6 h-6 text-amra-primary"></i> Governing Law
                    </h2>
                    <p>
                        This Privacy Policy shall be governed by the laws of India. Any disputes shall fall under the jurisdiction of courts located in Mumbai, Maharashtra.
                    </p>
                </section>

            </div>

        </div>
</div>
@endsection
