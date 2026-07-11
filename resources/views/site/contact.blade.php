@extends('layouts.site')

@section('title', 'Contact Us - Amra Property')
@section('meta_description', 'Get in touch with Amra Property for verified flats, villas, plots and commercial properties across leading cities.')

@section('content')
    <div class="pt-20 pb-10 sm:pt-24 sm:pb-20 relative">
        <!-- Ambient Glowing Core -->
        <div class="absolute inset-0 bg-[radial-gradient(ellipse_at_top,_var(--tw-gradient-stops))] from-teal-500/5 via-slate-50/0 to-slate-50/0 pointer-events-none"></div>

        <div class="max-w-7xl mx-auto px-4 md:px-6 pt-8 sm:pt-16 relative z-10">
            <!-- Header Block -->
            <div class="text-center mb-8 sm:mb-16">
                <span class="inline-flex items-center gap-1.5 bg-teal-500/10 text-teal-700 text-xs font-bold px-4 py-2 rounded-full uppercase tracking-wider mb-3 sm:mb-4">
                    <i data-lucide="phone-call" class="w-3.5 h-3.5" aria-hidden="true"></i> Concierge Desk
                </span>
                <h1 class="text-2xl sm:text-3.5xl md:text-5xl font-serif font-extrabold text-amra-dark mb-4">
                    Get in <span class="text-amra-primary italic">Touch</span>
                </h1>
                <p class="text-slate-500 max-w-md mx-auto text-xs sm:text-sm leading-relaxed">
                    Have questions about premium luxury property options? Our dedicated consulting partners are available 24/7.
                </p>
            </div>

            <!-- Content Split Grid -->
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 items-start">
                
                <!-- Column 1: Send Message Form Block -->
                <div class="lg:col-span-7 bg-white p-5 sm:p-8 md:p-10 rounded-3xl border border-slate-100 shadow-sm relative overflow-hidden">
                    <div id="form-container">
                        <h2 class="text-xl sm:text-2xl font-serif font-bold text-slate-800 mb-6">Send A Message</h2>
                        
                        <form id="contact-form" class="flex flex-col gap-6 text-left">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label for="first-name" class="block text-xs font-bold text-slate-600 uppercase tracking-wider mb-2">First Name</label>
                                    <input type="text" id="first-name" name="first_name" autocomplete="given-name" required
                                        class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3.5 outline-none focus:border-amra-primary focus:ring-4 focus:ring-teal-500/10 transition-all font-medium text-sm text-slate-800 placeholder-slate-400" placeholder="John">
                                </div>
                                <div>
                                    <label for="last-name" class="block text-xs font-bold text-slate-600 uppercase tracking-wider mb-2">Last Name</label>
                                    <input type="text" id="last-name" name="last_name" autocomplete="family-name" required
                                        class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3.5 outline-none focus:border-amra-primary focus:ring-4 focus:ring-teal-500/10 transition-all font-medium text-sm text-slate-800 placeholder-slate-400" placeholder="Doe">
                                </div>
                            </div>
                            
                            <div>
                                <label for="email" class="block text-xs font-bold text-slate-600 uppercase tracking-wider mb-2">Email Address</label>
                                <input type="email" id="email" name="email" autocomplete="email" required spellcheck="false"
                                    class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3.5 outline-none focus:border-amra-primary focus:ring-4 focus:ring-teal-500/10 transition-all font-medium text-sm text-slate-800 placeholder-slate-400" placeholder="john@example.com">
                            </div>
                            
                            <div>
                                <label for="message" class="block text-xs font-bold text-slate-600 uppercase tracking-wider mb-2">Detailed Message</label>
                                <textarea id="message" name="message" rows="5" required
                                    class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3.5 outline-none focus:border-amra-primary focus:ring-4 focus:ring-teal-500/10 transition-all font-medium text-sm text-slate-800 placeholder-slate-400" placeholder="Specify locations, BHK configuration, or general questions..."></textarea>
                            </div>
                            
                            <button type="submit" id="submit-btn"
                                class="w-full bg-amra-primary text-white py-4 rounded-xl font-bold text-sm hover:bg-teal-600 active:scale-[0.99] transition-all duration-300 shadow-lg shadow-teal-500/20 flex items-center justify-center gap-2">
                                <i data-lucide="send" class="w-4 h-4" aria-hidden="true"></i> Send Message
                            </button>
                        </form>
                    </div>
                </div>

                <!-- Column 2: Direct Contact Information Cards -->
                <div class="lg:col-span-5 flex flex-col gap-6">
                    
                    <!-- Direct Hotline Card -->
                    <div class="bg-white p-8 rounded-3xl border border-slate-100 shadow-sm flex items-start gap-5 group hover:border-teal-500/20 transition-colors duration-300">
                        <div class="w-12 h-12 rounded-2xl bg-teal-500/10 text-teal-600 flex items-center justify-center shrink-0">
                            <i data-lucide="phone" class="w-5 h-5" aria-hidden="true"></i>
                        </div>
                        <div>
                            <h3 class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-1">Direct Hotline</h3>
                            <a href="tel:+919559992958" class="text-xl font-serif font-bold text-slate-800 hover:text-amra-primary transition-colors block mb-1">+91 9559992958</a>
                            <p class="text-xs text-slate-400">Toll-free 24/7 dedicated support desk</p>
                        </div>
                    </div>

                    <!-- WhatsApp Advisor Card -->
                    <div class="bg-white p-8 rounded-3xl border border-slate-100 shadow-sm flex items-start gap-5 group hover:border-teal-500/20 transition-colors duration-300">
                        <div class="w-12 h-12 rounded-2xl bg-emerald-500/10 text-emerald-600 flex items-center justify-center shrink-0">
                            <svg class="w-5 h-5 fill-current" viewBox="0 0 16 16" aria-hidden="true"><path d="M13.601 2.326A7.85 7.85 0 0 0 7.994 0C3.627 0 .068 3.558.064 7.926c0 1.399.366 2.76 1.057 3.965L0 16l4.204-1.102a7.9 7.9 0 0 0 3.79.965h.004c4.368 0 7.926-3.558 7.93-7.93A7.9 7.9 0 0 0 13.6 2.326zM7.994 14.521a6.6 6.6 0 0 1-3.356-.92l-.24-.144-2.494.654.666-2.433-.156-.251a6.56 6.56 0 0 1-1.007-3.505c0-3.626 2.957-6.584 6.591-6.584a6.56 6.56 0 0 1 4.66 1.931 6.56 6.56 0 0 1 1.928 4.66c-.004 3.639-2.961 6.592-6.592 6.592m3.615-4.934c-.197-.099-1.17-.578-1.353-.646-.182-.065-.315-.099-.445.099-.133.197-.513.646-.627.775-.114.133-.232.148-.43.05-.197-.1-.836-.308-1.592-.985-.59-.525-.985-1.175-1.103-1.372-.114-.198-.011-.304.088-.403.087-.088.197-.232.296-.346.1-.114.133-.198.198-.33.065-.134.034-.248-.015-.347-.05-.099-.445-1.076-.612-1.47-.16-.389-.323-.335-.445-.34-.114-.007-.247-.007-.38-.007a.73.73 0 0 0-.529.247c-.182.198-.691.677-.691 1.654s.71 1.916.81 2.049c.098.133 1.394 2.132 3.383 2.992.47.205.84.326 1.129.418.475.152.904.129 1.246.08.38-.058 1.171-.48 1.338-.943.164-.464.164-.86.114-.943-.049-.084-.182-.133-.38-.232"/></svg>
                        </div>
                        <div>
                            <h3 class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-1">WhatsApp Channel</h3>
                            <a href="https://wa.me/919559992958" target="_blank" rel="noopener" class="text-xl font-serif font-bold text-slate-800 hover:text-emerald-500 transition-colors block mb-1">Instant Chat Support</a>
                            <p class="text-xs text-slate-400">Connect with an agent in under 2 minutes</p>
                        </div>
                    </div>

                    <!-- Email Support Card -->
                    <div class="bg-white p-8 rounded-3xl border border-slate-100 shadow-sm flex items-start gap-5 group hover:border-teal-500/20 transition-colors duration-300">
                        <div class="w-12 h-12 rounded-2xl bg-teal-500/10 text-teal-600 flex items-center justify-center shrink-0">
                            <i data-lucide="mail" class="w-5 h-5" aria-hidden="true"></i>
                        </div>
                        <div>
                            <h3 class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-1">Email Support</h3>
                            <a href="mailto:info@amraproperty.com" class="text-xl font-serif font-bold text-slate-800 hover:text-amra-primary transition-colors block mb-1">info@amraproperty.com</a>
                            <p class="text-xs text-slate-400">Response time in under 2 hours</p>
                        </div>
                    </div>

                    <!-- Lucknow Office -->
                    <div class="bg-white p-8 rounded-3xl border border-slate-100 shadow-sm flex items-start gap-5 group hover:border-teal-500/20 transition-colors duration-300">
                        <div class="w-12 h-12 rounded-2xl bg-teal-500/10 text-teal-600 flex items-center justify-center shrink-0">
                            <i data-lucide="map-pin" class="w-5 h-5" aria-hidden="true"></i>
                        </div>
                        <div>
                            <h3 class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-1">Lucknow Office</h3>
                            <h4 class="text-sm font-bold text-slate-800 mb-1">Hazratganj Square</h4>
                            <p class="text-xs text-slate-500 leading-relaxed max-w-xs">
                                4th Floor, Premium Plaza, Hazratganj, Lucknow, Uttar Pradesh - 226001
                            </p>
                        </div>
                    </div>

                    <!-- Mumbai Office -->
                    <div class="bg-white p-8 rounded-3xl border border-slate-100 shadow-sm flex items-start gap-5 group hover:border-teal-500/20 transition-colors duration-300">
                        <div class="w-12 h-12 rounded-2xl bg-teal-500/10 text-teal-600 flex items-center justify-center shrink-0">
                            <i data-lucide="map-pin" class="w-5 h-5" aria-hidden="true"></i>
                        </div>
                        <div>
                            <h3 class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-1">Mumbai Office</h3>
                            <h4 class="text-sm font-bold text-slate-800 mb-1">Rupa Solitaire</h4>
                            <p class="text-xs text-slate-500 leading-relaxed max-w-xs">
                                310, Wing C, Rupa Solitaire, MBP, Navi Mumbai, Maharashtra - 400710
                            </p>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        const form = document.getElementById('contact-form');
        const formContainer = document.getElementById('form-container');
        const submitBtn = document.getElementById('submit-btn');

        document.addEventListener('DOMContentLoaded', () => {
            const savedName = localStorage.getItem('amra_enquiry_name');
            const savedEmail = localStorage.getItem('amra_enquiry_email');

            if (savedName) {
                const parts = savedName.trim().split(/\s+/);
                if (parts.length > 0) {
                    if (document.getElementById('first-name')) document.getElementById('first-name').value = parts[0];
                    if (document.getElementById('last-name')) document.getElementById('last-name').value = parts.slice(1).join(' ') || parts[0];
                }
            }
            if (savedEmail && document.getElementById('email')) {
                document.getElementById('email').value = savedEmail;
            }
        });

        if (form) {
            form.addEventListener('submit', (e) => {
                e.preventDefault();

                const firstName = document.getElementById('first-name').value;
                const lastName = document.getElementById('last-name').value;
                const email = document.getElementById('email').value;
                const message = document.getElementById('message').value;

                // Apply animated loading spinner to submit button
                submitBtn.disabled = true;
                submitBtn.innerHTML = `
                    <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white inline-block" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    Sending…
                `;

                // Send AJAX post to our Laravel B2C organic uploader
                fetch('/contact', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                    },
                    body: JSON.stringify({
                        first_name: firstName,
                        last_name: lastName,
                        email: email,
                        message: message
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        localStorage.setItem('amra_enquiry_name', `${firstName} ${lastName}`.trim());
                        localStorage.setItem('amra_enquiry_email', email);

                        formContainer.classList.add('transition-all', 'duration-500', 'opacity-0');
                        
                        setTimeout(() => {
                            formContainer.innerHTML = `
                                <div class="text-center py-12 flex flex-col items-center justify-center">
                                    <div class="w-20 h-20 bg-emerald-500/10 text-emerald-500 rounded-full flex items-center justify-center mb-6 animate-bounce shadow-inner">
                                        <i data-lucide="check-circle-2" class="w-10 h-10"></i>
                                    </div>
                                    <h3 class="text-3xl font-serif font-extrabold text-slate-800 mb-3">Thank You, ${firstName}!</h3>
                                    <p class="text-slate-500 text-sm max-w-md mx-auto leading-relaxed mb-8">
                                        Your message has been securely submitted to our concierge desk. One of our dedicated certified **Amra Partners** will connect with you in under 15 minutes.
                                    </p>
                                    <button onclick="window.location.reload()"
                                        class="bg-amra-dark text-white px-8 py-3.5 rounded-xl font-bold text-xs hover:bg-black transition-colors shadow-md">
                                        Send Another Message
                                    </button>
                                </div>
                            `;
                            
                            // Re-trigger Lucide icons for injected success node
                            if (window.lucide) {
                                window.lucide.createIcons();
                            }
                            
                            formContainer.classList.remove('opacity-0');
                            formContainer.classList.add('opacity-100');
                        }, 300);
                    } else {
                        alert(data.message || 'An error occurred. Please try again.');
                        submitBtn.disabled = false;
                        submitBtn.innerText = 'Send Message';
                    }
                })
                .catch(err => {
                    console.error(err);
                    alert('Unable to connect to the server. Please check your connection and try again.');
                    submitBtn.disabled = false;
                    submitBtn.innerText = 'Send Message';
                });
            });
        }
    </script>
@endsection
