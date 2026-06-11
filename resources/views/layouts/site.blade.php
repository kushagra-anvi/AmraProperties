<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Amra Property')</title>
    <meta name="description" content="@yield('meta_description', 'Premium flats, villas and plots in Lucknow and Mumbai.')">
    
    <link rel="icon" type="image/png" href="{{ asset('assets/images/logo.png') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/index.css') }}">
    <script src="https://cdn.tailwindcss.com"></script>
    
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;600;700;800&family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
    <script src="https://unpkg.com/lucide@latest"></script>
    
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Inter', 'sans-serif'],
                        serif: ['Outfit', 'sans-serif'],
                    },
                    colors: {
                        'amra-primary': '#0bc1b2',
                        'amra-secondary': '#10B981',
                        'amra-dark': '#0F172A',
                        'amra-light': '#F8FAFC',
                    }
                }
            }
        }
    </script>
</head>
<body class="bg-amra-light text-amra-dark selection:bg-amra-primary selection:text-white overflow-x-hidden font-sans min-h-screen flex flex-col">

    <!-- Header Navbar -->
    <nav class="fixed w-full z-50 transition-all duration-300 bg-white/90 backdrop-blur-md border-b border-gray-200 shadow-sm">
        <div class="max-w-7xl mx-auto px-6 h-20 flex items-center justify-between">
            <a href="{{ route('site.home') }}" class="text-2xl font-serif font-bold text-amra-dark tracking-tight flex items-center gap-2">
                <img src="{{ asset('assets/images/logo.png') }}" alt="Amra Logo" width="32" height="32" class="w-8 h-8">
                Amra<span class="text-amra-primary">Property</span>
            </a>

            <div class="hidden md:flex items-center gap-8">
                <a href="{{ route('site.home') }}" class="{{ Route::currentRouteName() === 'site.home' ? 'text-amra-primary font-bold text-sm' : 'text-gray-600 hover:text-amra-primary font-medium text-sm' }} transition-colors">Home</a>
                <a href="{{ route('site.property') }}" class="{{ Route::currentRouteName() === 'site.property' ? 'text-amra-primary font-bold text-sm' : 'text-gray-600 hover:text-amra-primary font-medium text-sm' }} transition-colors">Property</a>
                <a href="{{ route('site.about') }}" class="{{ Route::currentRouteName() === 'site.about' ? 'text-amra-primary font-bold text-sm' : 'text-gray-600 hover:text-amra-primary font-medium text-sm' }} transition-colors">About Us</a>
                <a href="{{ route('site.contact') }}" class="{{ Route::currentRouteName() === 'site.contact' ? 'text-amra-primary font-bold text-sm' : 'text-gray-600 hover:text-amra-primary font-medium text-sm' }} transition-colors">Contact</a>
            </div>

            <div class="hidden md:flex items-center gap-4">
                <a href="#" class="border-2 border-amra-primary text-amra-primary px-6 py-2 rounded-full font-bold text-sm hover:bg-amra-primary hover:text-white transition-colors">Post Free Ad</a>
                <a href="{{ route('login') }}" class="w-10 h-10 bg-gray-100 rounded-full flex items-center justify-center text-gray-600 hover:bg-amra-primary hover:text-white transition-colors">
                    <i data-lucide="user" class="w-5 h-5"></i>
                </a>
            </div>

            <button id="hamburger-btn" class="md:hidden text-gray-600 hover:text-amra-primary transition-colors focus:outline-none" aria-label="Toggle Mobile Menu">
                <i data-lucide="menu" class="w-6 h-6"></i>
            </button>
        </div>
    </nav>

    <!-- Mobile Drawer Navigation -->
    <div id="mobile-menu-drawer" class="fixed inset-0 z-[100] bg-slate-900/40 backdrop-blur-sm opacity-0 pointer-events-none transition-opacity duration-300">
        <div class="fixed top-0 right-0 bottom-0 w-3/4 max-w-xs bg-white shadow-2xl p-6 flex flex-col justify-between transform translate-x-full transition-transform duration-300 ease-out">
            <div>
                <div class="flex items-center justify-between mb-8">
                    <a href="{{ route('site.home') }}" class="text-xl font-serif font-bold text-amra-dark tracking-tight flex items-center gap-2">
                        <img src="{{ asset('assets/images/logo.png') }}" alt="Amra Logo" width="28" height="28" class="w-7 h-7">
                        Amra<span class="text-amra-primary">Prop</span>
                    </a>
                    <button id="close-mobile-menu" class="w-8 h-8 rounded-full bg-slate-50 flex items-center justify-center text-gray-500 hover:text-amra-dark hover:bg-slate-100 transition-colors" aria-label="Close Mobile Menu">
                        <i data-lucide="x" class="w-5 h-5"></i>
                    </button>
                </div>
                
                <nav class="flex flex-col gap-5">
                    <a href="{{ route('site.home') }}" class="{{ Route::currentRouteName() === 'site.home' ? 'text-amra-primary font-bold text-base' : 'text-slate-600 hover:text-amra-primary font-semibold text-base' }} transition-colors">Home</a>
                    <a href="{{ route('site.property') }}" class="{{ Route::currentRouteName() === 'site.property' ? 'text-amra-primary font-bold text-base' : 'text-slate-600 hover:text-amra-primary font-semibold text-base' }} transition-colors">Property</a>
                    <a href="{{ route('site.about') }}" class="{{ Route::currentRouteName() === 'site.about' ? 'text-amra-primary font-bold text-base' : 'text-slate-600 hover:text-amra-primary font-semibold text-base' }} transition-colors">About Us</a>
                    <a href="{{ route('site.contact') }}" class="{{ Route::currentRouteName() === 'site.contact' ? 'text-amra-primary font-bold text-base' : 'text-slate-600 hover:text-amra-primary font-semibold text-base' }} transition-colors">Contact</a>
                </nav>
            </div>
            
            <div class="flex flex-col gap-4 mt-8 pt-6 border-t border-gray-100">
                <a href="#" class="border-2 border-amra-primary text-amra-primary text-center py-2.5 rounded-full font-bold text-sm hover:bg-amra-primary hover:text-white transition-colors">Post Free Ad</a>
                <div class="flex items-center gap-3">
                    <a href="{{ route('login') }}" class="w-10 h-10 bg-slate-100 rounded-full flex items-center justify-center text-gray-600">
                        <i data-lucide="user" class="w-5 h-5"></i>
                    </a>
                    <span class="text-sm font-semibold text-slate-700">My Account</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Content Area -->
    <main class="flex-grow">
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="bg-amra-dark pt-20 pb-10 border-t border-gray-800 mt-auto">
        <div class="max-w-7xl mx-auto px-6">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-12 mb-16">
                <div class="md:col-span-2">
                    <a href="{{ route('site.home') }}" class="text-3xl font-serif font-bold text-white tracking-tight flex items-center gap-2 mb-6">
                        <img src="{{ asset('assets/images/logo.png') }}" alt="Amra Logo" width="40" height="40" class="w-10 h-10">
                        Amra<span class="text-amra-primary">Property</span>
                    </a>
                    <p class="text-gray-400 max-w-sm leading-relaxed mb-8">
                        Your trusted partner in finding the perfect home. RERA-approved projects and verified developers in Lucknow & Mumbai.
                    </p>
                    <div class="flex gap-4">
                        <a href="#" class="w-10 h-10 rounded-full bg-white/5 border border-white/10 flex items-center justify-center text-gray-400 hover:bg-amra-primary hover:text-white transition-all duration-300">
                            <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 2h-3a5 5 0 0 0-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 0 1 1-1h3z"></path></svg>
                        </a>
                        <a href="#" class="w-10 h-10 rounded-full bg-white/5 border border-white/10 flex items-center justify-center text-gray-400 hover:bg-amra-primary hover:text-white transition-all duration-300">
                            <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 4s-.7 2.1-2 3.4c1.6 10-9.4 17.3-18 11.6 2.2.1 4.4-.6 6-2C3 15.5.5 9.6 3 5c2.2 2.6 5.6 4.1 9 4-.9-4.2 4-6.6 7-3.8 1.1 0 3-1.2 3-1.2z"></path></svg>
                        </a>
                        <a href="#" class="w-10 h-10 rounded-full bg-white/5 border border-white/10 flex items-center justify-center text-gray-400 hover:bg-amra-primary hover:text-white transition-all duration-300">
                            <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="2" width="20" height="20" rx="5" ry="5"></rect><path d="M16 11.37A4 4 0 1 1 12.63 8 4 4 0 0 1 16 11.37z"></path><line x1="17.5" y1="6.5" x2="17.51" y2="6.5"></line></svg>
                        </a>
                    </div>
                </div>
                
                <div>
                    <h3 class="text-white font-bold mb-6">Quick Links</h3>
                    <ul class="space-y-4 text-gray-400 text-sm">
                        <li><a href="{{ route('site.home') }}" class="hover:text-amra-primary transition-colors">Home</a></li>
                        <li><a href="{{ route('site.about') }}" class="hover:text-amra-primary transition-colors">About Us</a></li>
                        <li><a href="{{ route('site.property') }}" class="hover:text-amra-primary transition-colors">Property</a></li>
                        <li><a href="{{ route('site.contact') }}" class="hover:text-amra-primary transition-colors">Contact</a></li>
                    </ul>
                </div>
                
                <div>
                    <h3 class="text-white font-bold mb-6">Properties</h3>
                    <ul class="space-y-4 text-gray-400 text-sm">
                        <li><a href="{{ route('site.property') }}" class="hover:text-amra-primary transition-colors">Flats in Mumbai</a></li>
                        <li><a href="{{ route('site.property') }}" class="hover:text-amra-primary transition-colors">Villas in Lucknow</a></li>
                        <li><a href="{{ route('site.property') }}" class="hover:text-amra-primary transition-colors">Commercial Plots</a></li>
                        <li><a href="{{ route('site.property') }}" class="hover:text-amra-primary transition-colors">New Launches</a></li>
                    </ul>
                </div>
            </div>
            
            <div class="border-t border-gray-800 pt-8 flex flex-col md:flex-row items-center justify-between gap-4 text-xs text-gray-500 font-medium">
                <p>&copy; 2026 Amra Property. All rights reserved.</p>
                <div class="flex gap-6">
                    <a href="{{ route('site.privacy') }}" class="hover:text-white transition-colors">Privacy Policy</a>
                    <a href="{{ route('site.terms') }}" class="hover:text-white transition-colors">Terms of Service</a>
                </div>
            </div>
        </div>
    </footer>

    <!-- Initialize Lucide Icons & Drawer script -->
    <script>
        lucide.createIcons();

        const hamburgerBtn = document.getElementById('hamburger-btn');
        const closeMobileMenuBtn = document.getElementById('close-mobile-menu');
        const mobileMenuDrawer = document.getElementById('mobile-menu-drawer');
        
        if (hamburgerBtn && mobileMenuDrawer) {
            const drawerContent = mobileMenuDrawer.firstElementChild;
            
            const openMenu = () => {
                mobileMenuDrawer.classList.remove('pointer-events-none', 'opacity-0');
                mobileMenuDrawer.classList.add('opacity-100');
                drawerContent.classList.remove('translate-x-full');
                drawerContent.classList.add('translate-x-0');
                document.body.classList.add('overflow-hidden');
            };
            
            const closeMenu = () => {
                mobileMenuDrawer.classList.add('pointer-events-none', 'opacity-0');
                mobileMenuDrawer.classList.remove('opacity-100');
                drawerContent.classList.add('translate-x-full');
                drawerContent.classList.remove('translate-x-0');
                document.body.classList.remove('overflow-hidden');
            };
            
            hamburgerBtn.addEventListener('click', (e) => {
                e.stopPropagation();
                openMenu();
            });
            
            if (closeMobileMenuBtn) {
                closeMobileMenuBtn.addEventListener('click', closeMenu);
            }
            
            mobileMenuDrawer.addEventListener('click', (e) => {
                if (e.target === mobileMenuDrawer) {
                    closeMenu();
                }
            });

            document.addEventListener('keydown', (e) => {
                if (e.key === 'Escape' && !mobileMenuDrawer.classList.contains('opacity-0')) {
                    closeMenu();
                }
            });
        }
    </script>
    @yield('scripts')
</body>
</html>
