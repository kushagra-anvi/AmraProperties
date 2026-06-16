<!DOCTYPE html>
<html lang="en" class="h-full bg-amra-light text-amra-dark">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Amra CRM') - Amra Property</title>
    <link rel="icon" type="image/png" href="{{ asset('assets/images/logo.png') }}">

    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;600;700;800&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
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
    
    <style>
        html,
        body {
            background: #f8fafc;
            color: #0f172a;
        }

        .crm-shell :is(.bg-slate-950, .bg-slate-900) {
            background-color: #ffffff !important;
        }

        .crm-shell :is(.bg-slate-950\/60, .bg-slate-950\/50, .bg-slate-950\/40, .bg-slate-950\/20, .bg-slate-900\/50, .bg-slate-900\/40, .bg-slate-850, .bg-slate-850\/20, .bg-slate-850\/10, .bg-slate-800, .bg-slate-800\/50) {
            background-color: #f8fafc !important;
        }

        .crm-shell :is(.hover\:bg-slate-900:hover, .hover\:bg-slate-850\/20:hover, .hover\:bg-slate-800:hover, .hover\:bg-slate-800\/50:hover, .hover\:bg-slate-750:hover, .hover\:bg-slate-755:hover, .hover\:bg-slate-700:hover) {
            background-color: #f1f5f9 !important;
        }

        .crm-shell :is(.border-slate-950, .border-slate-900, .border-slate-850, .border-slate-800, .border-slate-750, .border-slate-700) {
            border-color: #e2e8f0 !important;
        }

        .crm-shell :is(.text-white, .text-slate-100, .text-slate-200, .text-slate-250, .text-slate-300, .text-slate-350) {
            color: #0f172a !important;
        }

        .crm-shell :is(.text-slate-400, .text-slate-450, .text-slate-500, .text-slate-550, .text-slate-600, .text-slate-650, .text-slate-655) {
            color: #64748b !important;
        }

        .crm-shell :is(input, select, textarea) {
            background-color: #ffffff !important;
            border-color: #cbd5e1 !important;
            color: #0f172a !important;
        }

        .crm-shell :is(input::placeholder, textarea::placeholder) {
            color: #94a3b8 !important;
        }

        .crm-shell table thead tr {
            background-color: #f8fafc !important;
        }

        .crm-shell table thead th {
            padding: 1rem 1.5rem !important;
            white-space: nowrap;
            vertical-align: middle;
            line-height: 1.25;
        }

        .crm-shell table thead tr {
            border-color: #e2e8f0 !important;
            color: #64748b !important;
            font-size: 11px !important;
            font-weight: 800 !important;
            letter-spacing: 0.12em !important;
        }

        .crm-shell .shadow-md,
        .crm-shell .shadow-lg,
        .crm-shell .shadow-2xl {
            box-shadow: 0 12px 30px rgba(15, 23, 42, 0.06) !important;
        }

        .sidebar-link-active {
            background-color: rgba(11, 193, 178, 0.1);
            color: #0bc1b2;
            border-left: 4px solid #0bc1b2;
        }
        .sidebar-link-inactive {
            border-left: 4px solid transparent;
        }

        .sidebar-link-inactive {
            color: #475569 !important;
        }

        .sidebar-link-inactive:hover {
            background-color: #f1f5f9 !important;
            color: #0f172a !important;
        }
    </style>
    @yield('styles')
</head>
<body class="crm-shell h-full font-sans antialiased bg-amra-light flex flex-col md:flex-row overflow-hidden">

    <!-- Mobile Header -->
    <header class="flex items-center justify-between bg-white border-b border-slate-200 px-6 py-4 md:hidden w-full shrink-0 relative z-20">
        <div class="flex items-center gap-2">
            <h1 class="text-xl font-serif font-extrabold text-amra-dark">
                Amra <span class="text-amra-primary italic">Property</span>
            </h1>
        </div>
        <button id="mobile-menu-toggle" class="text-slate-500 hover:text-amra-dark focus:outline-none">
            <i data-lucide="menu" class="w-6 h-6"></i>
        </button>
    </header>

    <!-- Sidebar Navigation -->
    <aside id="sidebar" class="fixed inset-y-0 left-0 w-64 bg-white border-r border-slate-200 flex flex-col z-30 transform -translate-x-full transition-transform duration-300 ease-in-out md:translate-x-0 md:static md:h-full shrink-0">
        <!-- Logo Brand -->
        <div class="px-6 py-6 border-b border-slate-200 hidden md:block shrink-0">
            <h1 class="text-2xl font-serif font-extrabold text-amra-dark">
                Amra <span class="text-amra-primary italic">Property</span>
            </h1>
            <p class="text-[10px] font-bold text-slate-500 uppercase tracking-widest mt-1.5">Premium CRM Engine</p>
        </div>

        @php
            $userRole = auth()->user()->role;
            $canViewDashboard = in_array($userRole, ['super_admin', 'admin', 'analyst'], true);
            $canViewB2BModule = in_array($userRole, ['super_admin', 'admin', 'sales_team'], true);
            $canViewBuyerAndPartnerModules = in_array($userRole, ['super_admin', 'admin'], true);
            $canViewSalesModule = $userRole === 'super_admin';
        @endphp

        <!-- Navigation Links -->
        <nav class="flex-grow px-4 py-6 space-y-1.5 overflow-y-auto">
            <!-- Dashboard -->
            @if ($canViewDashboard)
                <a href="{{ route('crm.dashboard') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all font-medium text-sm sidebar-link-inactive text-slate-400 hover:bg-slate-800/50 hover:text-white {{ request()->routeIs('crm.dashboard') ? 'sidebar-link-active' : '' }}">
                    <i data-lucide="layout-dashboard" class="w-4 h-4"></i>
                    Dashboard
                </a>
            @endif

            @if ($userRole === 'partner')
                <a href="{{ route('crm.partner.dashboard') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all font-medium text-sm sidebar-link-inactive text-slate-400 hover:bg-slate-800/50 hover:text-white {{ request()->routeIs('crm.partner.*') ? 'sidebar-link-active' : '' }}">
                    <i data-lucide="layout-dashboard" class="w-4 h-4"></i>
                    Partner Dashboard
                </a>
            @endif

            @if ($canViewB2BModule || $canViewBuyerAndPartnerModules)
                <div class="pt-4 pb-2 px-4">
                    <span class="text-[10px] font-bold text-slate-600 uppercase tracking-wider">Leads Pipeline</span>
                </div>
            @endif

            <!-- B2B Leads -->
            @if ($canViewB2BModule)
                <a href="{{ route('crm.b2b.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all font-medium text-sm sidebar-link-inactive text-slate-400 hover:bg-slate-800/50 hover:text-white {{ request()->routeIs('crm.b2b.*') ? 'sidebar-link-active' : '' }}">
                    <i data-lucide="building-2" class="w-4 h-4"></i>
                    B2B Leads
                </a>
            @endif

            @if ($canViewBuyerAndPartnerModules)
                <!-- B2C Leads -->
                <a href="{{ route('crm.b2c.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all font-medium text-sm sidebar-link-inactive text-slate-400 hover:bg-slate-800/50 hover:text-white {{ request()->routeIs('crm.b2c.*') ? 'sidebar-link-active' : '' }}">
                    <i data-lucide="users" class="w-4 h-4"></i>
                    B2C Buyer Leads
                </a>

                <div class="pt-4 pb-2 px-4">
                    <span class="text-[10px] font-bold text-slate-600 uppercase tracking-wider">Partners & Sales</span>
                </div>

                <!-- Partners -->
                <a href="{{ route('crm.partners.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all font-medium text-sm sidebar-link-inactive text-slate-400 hover:bg-slate-800/50 hover:text-white {{ request()->routeIs('crm.partners.*') ? 'sidebar-link-active' : '' }}">
                    <i data-lucide="handshake" class="w-4 h-4"></i>
                    Agents & Developers
                </a>
            @endif

            <!-- Sales Team (Admin / Super Admin Access) -->
            @if ($canViewSalesModule)
                <a href="{{ route('crm.sales.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all font-medium text-sm sidebar-link-inactive text-slate-400 hover:bg-slate-800/50 hover:text-white {{ request()->routeIs('crm.sales.*') ? 'sidebar-link-active' : '' }}">
                    <i data-lucide="trending-up" class="w-4 h-4"></i>
                    Sales Performance
                </a>
            @endif
        </nav>

        <!-- User Block Profile & Logout -->
        <div class="p-4 border-t border-slate-200 bg-slate-50 shrink-0">
            @php
                $unreadNotifications = auth()->user()->unreadNotifications()->latest()->take(3)->get();
            @endphp

            @if ($unreadNotifications->isNotEmpty())
                <div class="mb-3 rounded-xl border border-amber-200 bg-amber-50 p-3 space-y-2">
                    <div class="flex items-center gap-2 text-[10px] font-bold uppercase tracking-wider text-amber-700">
                        <i data-lucide="bell-ring" class="w-3.5 h-3.5"></i>
                        Admin Notifications
                    </div>
                    @foreach ($unreadNotifications as $notification)
                        <a href="{{ $notification->data['url'] ?? '#' }}" class="block text-[10px] font-semibold text-slate-600 hover:text-amber-700 leading-snug">
                            {{ $notification->data['message'] ?? 'New CRM notification' }}
                        </a>
                    @endforeach
                </div>
            @endif

            <div class="flex items-center gap-3 px-2 py-3">
                <div class="w-10 h-10 rounded-xl bg-teal-500/10 border border-teal-500/20 text-teal-400 flex items-center justify-center font-bold text-sm shrink-0">
                    {{ strtoupper(substr(auth()->user()->name, 0, 2)) }}
                </div>
                <div class="min-w-0 flex-grow">
                    <p class="text-xs font-semibold text-amra-dark truncate">{{ auth()->user()->name }}</p>
                    <p class="text-[9px] font-bold text-slate-500 uppercase tracking-wider mt-0.5 truncate">{{ str_replace('_', ' ', auth()->user()->role) }}</p>
                </div>
            </div>
            
            <form action="{{ route('logout') }}" method="POST" class="mt-2">
                @csrf
                <button type="submit" class="w-full flex items-center justify-center gap-2 bg-white hover:bg-rose-50 hover:text-rose-600 border border-slate-200 px-4 py-2.5 rounded-xl font-semibold text-xs text-slate-500 transition-colors">
                    <i data-lucide="log-out" class="w-3.5 h-3.5"></i>
                    Log Out
                </button>
            </form>
        </div>
    </aside>

    <!-- Overlay under mobile menu -->
    <div id="sidebar-overlay" class="fixed inset-0 bg-black/60 z-25 hidden md:hidden"></div>

    <!-- Main Content viewport wrapper -->
    <main class="flex-grow flex flex-col overflow-hidden relative w-full">
        <!-- Toast Status Alerts -->
        @if (session('success'))
            <div class="fixed top-6 right-6 z-50 animate-bounce duration-500 max-w-sm bg-emerald-500/15 border border-emerald-500/30 text-emerald-400 px-4 py-3 rounded-xl flex items-start gap-3 shadow-lg backdrop-blur-md">
                <i data-lucide="check-circle" class="w-5 h-5 shrink-0"></i>
                <div class="text-xs">
                    <span class="font-bold">Success!</span> {{ session('success') }}
                </div>
            </div>
        @endif

        @if (session('error'))
            <div class="fixed top-6 right-6 z-50 animate-bounce duration-500 max-w-sm bg-rose-500/15 border border-rose-500/30 text-rose-400 px-4 py-3 rounded-xl flex items-start gap-3 shadow-lg backdrop-blur-md">
                <i data-lucide="alert-circle" class="w-5 h-5 shrink-0"></i>
                <div class="text-xs">
                    <span class="font-bold">Access Denied!</span> {{ session('error') }}
                </div>
            </div>
        @endif

        <!-- Dynamic Content Body -->
        <div class="flex-grow p-6 md:p-8 overflow-y-auto bg-amra-light">
            @yield('content')
        </div>
    </main>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            // Trigger Lucide Icons
            if (window.lucide) {
                window.lucide.createIcons();
            }

            // Mobile sidebar mechanics
            const mobileMenuBtn = document.getElementById('mobile-menu-toggle');
            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('sidebar-overlay');

            if (mobileMenuBtn && sidebar && overlay) {
                const toggleMenu = () => {
                    sidebar.classList.toggle('-translate-x-full');
                    overlay.classList.toggle('hidden');
                };

                mobileMenuBtn.addEventListener('click', toggleMenu);
                overlay.addEventListener('click', toggleMenu);
            }

            // Success alert auto-dismiss after 4 seconds
            setTimeout(() => {
                const alerts = document.querySelectorAll('.animate-bounce');
                alerts.forEach(alert => {
                    alert.classList.add('transition-opacity', 'duration-500', 'opacity-0');
                    setTimeout(() => alert.remove(), 500);
                });
            }, 4000);
        });
    </script>
    @yield('scripts')
</body>
</html>
