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
    <script src="https://cdn.jsdelivr.net/npm/lucide@0.397.0/dist/umd/lucide.min.js"></script>
    
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
                <a href="{{ route('site.blog') }}" class="{{ Route::currentRouteName() === 'site.blog' ? 'text-amra-primary font-bold text-sm' : 'text-gray-600 hover:text-amra-primary font-medium text-sm' }} transition-colors">Blog</a>
                <a href="{{ route('site.about') }}" class="{{ Route::currentRouteName() === 'site.about' ? 'text-amra-primary font-bold text-sm' : 'text-gray-600 hover:text-amra-primary font-medium text-sm' }} transition-colors">About Us</a>
                <a href="{{ route('site.contact') }}" class="{{ Route::currentRouteName() === 'site.contact' ? 'text-amra-primary font-bold text-sm' : 'text-gray-600 hover:text-amra-primary font-medium text-sm' }} transition-colors">Contact</a>
            </div>

            <div class="hidden md:flex items-center gap-4">
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
                    <a href="{{ route('site.blog') }}" class="{{ Route::currentRouteName() === 'site.blog' ? 'text-amra-primary font-bold text-base' : 'text-slate-600 hover:text-amra-primary font-semibold text-base' }} transition-colors">Blog</a>
                    <a href="{{ route('site.about') }}" class="{{ Route::currentRouteName() === 'site.about' ? 'text-amra-primary font-bold text-base' : 'text-slate-600 hover:text-amra-primary font-semibold text-base' }} transition-colors">About Us</a>
                    <a href="{{ route('site.contact') }}" class="{{ Route::currentRouteName() === 'site.contact' ? 'text-amra-primary font-bold text-base' : 'text-slate-600 hover:text-amra-primary font-semibold text-base' }} transition-colors">Contact</a>
                </nav>
            </div>
            
            <div class="flex flex-col gap-4 mt-8 pt-6 border-t border-gray-100">
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
                    <div class="flex flex-wrap gap-4">
                        <a href="https://www.facebook.com/propertyamra" target="_blank" rel="noopener noreferrer" class="w-10 h-10 rounded-full bg-white/5 border border-white/10 flex items-center justify-center text-gray-400 hover:bg-amra-primary hover:text-white transition-all duration-300" aria-label="Facebook">
                            <i data-lucide="facebook" class="w-4 h-4"></i>
                        </a>
                        <a href="https://twitter.com/AmraProperty" target="_blank" rel="noopener noreferrer" class="w-10 h-10 rounded-full bg-white/5 border border-white/10 flex items-center justify-center text-gray-400 hover:bg-amra-primary hover:text-white transition-all duration-300" aria-label="Twitter">
                            <i data-lucide="twitter" class="w-4 h-4"></i>
                        </a>
                        <a href="https://www.instagram.com/amra_property/" target="_blank" rel="noopener noreferrer" class="w-10 h-10 rounded-full bg-white/5 border border-white/10 flex items-center justify-center text-gray-400 hover:bg-amra-primary hover:text-white transition-all duration-300" aria-label="Instagram">
                            <i data-lucide="instagram" class="w-4 h-4"></i>
                        </a>
                        <a href="https://in.pinterest.com/amraproperty/" target="_blank" rel="noopener noreferrer" class="w-10 h-10 rounded-full bg-white/5 border border-white/10 flex items-center justify-center text-gray-400 hover:bg-amra-primary hover:text-white transition-all duration-300" aria-label="Pinterest">
                            <i data-lucide="pinterest" class="w-4 h-4"></i>
                        </a>
                        <a href="https://in.linkedin.com/company/amra-property-india" target="_blank" rel="noopener noreferrer" class="w-10 h-10 rounded-full bg-white/5 border border-white/10 flex items-center justify-center text-gray-400 hover:bg-amra-primary hover:text-white transition-all duration-300" aria-label="LinkedIn">
                            <i data-lucide="linkedin" class="w-4 h-4"></i>
                        </a>
                        <a href="https://www.youtube.com/@AMRAPROPERTY/" target="_blank" rel="noopener noreferrer" class="w-10 h-10 rounded-full bg-white/5 border border-white/10 flex items-center justify-center text-gray-400 hover:bg-amra-primary hover:text-white transition-all duration-300" aria-label="YouTube">
                            <i data-lucide="youtube" class="w-4 h-4"></i>
                        </a>
                    </div>
                </div>
                
                <div>
                    <h3 class="text-white font-bold mb-6">Quick Links</h3>
                    <ul class="space-y-4 text-gray-400 text-sm">
                        <li><a href="{{ route('site.home') }}" class="hover:text-amra-primary transition-colors">Home</a></li>
                        <li><a href="{{ route('site.about') }}" class="hover:text-amra-primary transition-colors">About Us</a></li>
                        <li><a href="{{ route('site.property') }}" class="hover:text-amra-primary transition-colors">Property</a></li>
                        <li><a href="{{ route('site.blog') }}" class="hover:text-amra-primary transition-colors">Blog</a></li>
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
            <div class="border-t border-gray-800 pt-8 mb-6 text-[10px] text-gray-500 leading-relaxed font-medium">
                <p><strong class="text-gray-400">Disclaimer:</strong> Amra Property is a real estate listing and marketing platform. We do not act as a real estate agent or broker and do not participate in property transactions. Property details are provided by developers, brokers, or owners.</p>
            </div>

            <div class="border-t border-gray-800 pt-6 flex flex-col md:flex-row items-center justify-between gap-4 text-xs text-gray-500 font-medium">
                <p>&copy; 2026 Amra Property. All rights reserved.</p>
                <div class="flex flex-wrap gap-x-6 gap-y-2 justify-center">
                    <a href="{{ route('site.privacy') }}" class="hover:text-white transition-colors">Privacy Policy</a>
                    <a href="{{ route('site.terms') }}" class="hover:text-white transition-colors">Terms & Conditions</a>
                    <a href="{{ route('site.rera-disclaimer') }}" class="hover:text-white transition-colors">RERA Disclaimer</a>
                    <a href="{{ route('site.advertiser-agreement') }}" class="hover:text-white transition-colors">Advertiser Agreement</a>
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
