<!DOCTYPE html>
<html lang="en" class="h-full bg-amra-light text-amra-dark">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Amra CRM') - Amra Property</title>
    <link rel="icon" type="image/png" href="{{ asset('assets/images/logo.png') }}">

    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;600;700;800&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://unpkg.com/lucide@latest"></script>
    <script src="https://cdn.tailwindcss.com"></script>
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
    @vite('resources/js/app.js')
    
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
                <a href="{{ route('crm.partner.properties.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all font-medium text-sm sidebar-link-inactive text-slate-400 hover:bg-slate-800/50 hover:text-white {{ request()->routeIs('crm.partner.properties.*') ? 'sidebar-link-active' : '' }}">
                    <i data-lucide="home" class="w-4 h-4"></i>
                    My Properties
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

            <!-- B2C Leads -->
            @if ($canViewBuyerAndPartnerModules)
                <a href="{{ route('crm.b2c.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all font-medium text-sm sidebar-link-inactive text-slate-400 hover:bg-slate-800/50 hover:text-white {{ request()->routeIs('crm.b2c.*') ? 'sidebar-link-active' : '' }}">
                    <i data-lucide="users" class="w-4 h-4"></i>
                    B2C Buyer Leads
                </a>
            @endif

            <!-- Call Logs (Tata) -->
            @if ($canViewB2BModule)
                <a href="{{ route('crm.tata-logs.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all font-medium text-sm sidebar-link-inactive text-slate-400 hover:bg-slate-800/50 hover:text-white {{ request()->routeIs('crm.tata-logs.*') ? 'sidebar-link-active' : '' }}">
                    <i data-lucide="phone-call" class="w-4 h-4"></i>
                    Call Logs (Tata)
                </a>
            @endif

            @if ($canViewBuyerAndPartnerModules)
                <div class="pt-4 pb-2 px-4">
                    <span class="text-[10px] font-bold text-slate-600 uppercase tracking-wider">Partners & Sales</span>
                </div>

                <!-- Partners -->
                <a href="{{ route('crm.partners.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all font-medium text-sm sidebar-link-inactive text-slate-400 hover:bg-slate-800/50 hover:text-white {{ request()->routeIs('crm.partners.*') ? 'sidebar-link-active' : '' }}">
                    <i data-lucide="handshake" class="w-4 h-4"></i>
                    Agents & Developers
                </a>

                <div class="pt-4 pb-2 px-4">
                    <span class="text-[10px] font-bold text-slate-600 uppercase tracking-wider">Properties & Content</span>
                </div>

                <!-- Properties -->
                <a href="{{ route('crm.properties.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all font-medium text-sm sidebar-link-inactive text-slate-400 hover:bg-slate-800/50 hover:text-white {{ request()->routeIs('crm.properties.*') ? 'sidebar-link-active' : '' }}">
                    <i data-lucide="home" class="w-4 h-4"></i>
                    Properties List
                </a>
                <a href="{{ route('crm.property-enquiries.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all font-medium text-sm sidebar-link-inactive text-slate-400 hover:bg-slate-800/50 hover:text-white {{ request()->routeIs('crm.property-enquiries.*') ? 'sidebar-link-active' : '' }}">
                    <i data-lucide="inbox" class="w-4 h-4"></i>
                    Property Enquiries
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
                $todayEnd = now()->endOfDay();
                $b2bCount = 0;
                $b2cCount = 0;
                $pendingFollowupsCount = 0;

                if (auth()->user()->role === 'partner') {
                    $partner = auth()->user()->partner;
                    if ($partner) {
                        $followupQuery = \App\Models\FollowUp::where('followable_type', \App\Models\B2CLead::class)
                            ->whereNull('completed_at')
                            ->where('due_at', '<=', $todayEnd)
                            ->where('user_id', auth()->id())
                            ->whereIn('followable_id', function ($sub) use ($partner) {
                                $sub->select('b2_c_lead_id')
                                    ->from('b2_c_lead_shares')
                                    ->where('partner_id', $partner->id);
                            });
                        $pendingFollowupsCount = $followupQuery->count();
                    }
                } else {
                    $b2bQuery = \App\Models\FollowUp::where('followable_type', \App\Models\B2BLead::class)
                        ->whereNull('completed_at')
                        ->where('due_at', '<=', $todayEnd);

                    $b2cQuery = \App\Models\FollowUp::where('followable_type', \App\Models\B2CLead::class)
                        ->whereNull('completed_at')
                        ->where('due_at', '<=', $todayEnd);

                    if (auth()->user()->role === 'sales_team') {
                        $salesPersonId = auth()->user()->salesPerson?->id;
                        $b2bQuery->where('sales_person_id', $salesPersonId);
                        $b2cQuery->where('sales_person_id', $salesPersonId);
                    }

                    $b2bCount = $b2bQuery->count();
                    $b2cCount = $b2cQuery->count();
                    $pendingFollowupsCount = $b2bCount + $b2cCount;
                }
                $unreadNotifications = auth()->user()->unreadNotifications()->latest()->take(3)->get();
            @endphp



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
        <!-- Top Bar Header -->
        <header class="bg-white border-b border-slate-200 px-6 py-4 flex items-center justify-between shrink-0 relative z-20">
            <!-- Left Side: Mobile Menu Button & Brand Name on Mobile -->
            <div class="flex items-center gap-4">
                <button id="mobile-menu-toggle" class="text-slate-500 hover:text-amra-dark focus:outline-none md:hidden">
                    <i data-lucide="menu" class="w-6 h-6"></i>
                </button>
                <div class="md:hidden">
                    <h1 class="text-lg font-serif font-extrabold text-amra-dark">
                        Amra <span class="text-amra-primary italic">Property</span>
                    </h1>
                </div>
                <div class="hidden md:block">
                    <!-- Spacing or breadcrumb placeholder -->
                </div>
            </div>
            
            <!-- Right Side: Notifications Dropdown -->
            <div class="flex items-center gap-4 relative">
                <!-- Notifications Bell with Dropdown -->
                <div class="relative" id="notification-dropdown-container">
                    <button id="notification-btn" class="relative p-2.5 text-slate-500 hover:text-amra-dark hover:bg-slate-100 rounded-xl transition-all focus:outline-none flex items-center justify-center border border-slate-100 bg-white">
                        <i data-lucide="bell" class="w-4 h-4"></i>
                        @if($pendingFollowupsCount + $unreadNotifications->count() > 0)
                            <span class="absolute top-1.5 right-1.5 w-2 h-2 bg-rose-500 border-2 border-white rounded-full"></span>
                        @endif
                    </button>
                    
                    <!-- Dropdown Panel -->
                    <div id="notification-dropdown" class="hidden absolute right-0 mt-2 w-80 bg-white border border-slate-200 rounded-2xl shadow-xl z-50 py-2">
                        <div class="px-4 py-2.5 border-b border-slate-100 flex items-center justify-between">
                            <span class="text-[10px] font-bold text-slate-500 uppercase tracking-wider">Alerts & Notifications</span>
                            @if($pendingFollowupsCount + $unreadNotifications->count() > 0)
                                <span class="bg-rose-50 text-rose-600 text-[10px] font-extrabold px-2.5 py-0.5 rounded-full border border-rose-100">
                                    {{ $pendingFollowupsCount + $unreadNotifications->count() }} new
                                </span>
                            @endif
                        </div>
                        
                        <div class="max-h-72 overflow-y-auto divide-y divide-slate-100">
                            <!-- Follow-up Alerts -->
                            @if($pendingFollowupsCount > 0)
                                <div class="p-4 space-y-2">
                                    <div class="flex items-center gap-1.5 text-[10px] font-bold uppercase tracking-wider text-rose-600">
                                        <i data-lucide="calendar" class="w-3.5 h-3.5"></i>
                                        Follow-up Alerts
                                    </div>
                                    @if (auth()->user()->role === 'partner')
                                        <a href="{{ route('crm.partner.dashboard') }}" class="block text-xs font-semibold text-slate-750 hover:text-amra-primary leading-snug">
                                            You have {{ $pendingFollowupsCount }} pending follow-up{{ $pendingFollowupsCount > 1 ? 's' : '' }} due.
                                        </a>
                                    @else
                                        <div class="space-y-1.5">
                                            @if($b2bCount > 0)
                                                <a href="{{ route('crm.b2b.index') }}?due_only=1" class="flex items-center justify-between text-xs text-slate-600 hover:text-amra-primary font-semibold py-1">
                                                    <span>B2B Leads Due</span>
                                                    <span class="bg-rose-50 border border-rose-100 px-2 py-0.5 rounded-lg text-[10px] font-extrabold text-rose-600">{{ $b2bCount }}</span>
                                                </a>
                                            @endif
                                            @if($b2cCount > 0)
                                                <a href="{{ route('crm.b2c.index') }}?due_only=1" class="flex items-center justify-between text-xs text-slate-600 hover:text-amra-primary font-semibold py-1">
                                                    <span>B2C Buyers Due</span>
                                                    <span class="bg-rose-50 border border-rose-100 px-2 py-0.5 rounded-lg text-[10px] font-extrabold text-rose-600">{{ $b2cCount }}</span>
                                                </a>
                                            @endif
                                        </div>
                                    @endif
                                </div>
                            @endif
                            
                            <!-- Admin Notifications -->
                            @if($unreadNotifications->isNotEmpty())
                                <div class="p-4 space-y-2">
                                    <div class="flex items-center gap-1.5 text-[10px] font-bold uppercase tracking-wider text-amber-600">
                                        <i data-lucide="bell-ring" class="w-3.5 h-3.5"></i>
                                        Admin Notifications
                                    </div>
                                    <div class="space-y-1.5">
                                        @foreach ($unreadNotifications as $notification)
                                            <a href="{{ $notification->data['url'] ?? '#' }}" class="block text-xs font-semibold text-slate-600 hover:text-amra-primary py-1 leading-snug">
                                                {{ $notification->data['message'] ?? 'New CRM notification' }}
                                            </a>
                                        @endforeach
                                    </div>
                                </div>
                            @endif
                            
                            @if($pendingFollowupsCount + $unreadNotifications->count() === 0)
                                <div class="p-6 text-center text-slate-400 text-xs">
                                    <i data-lucide="bell-off" class="w-6 h-6 mx-auto mb-2 text-slate-300"></i>
                                    All caught up! No active alerts.
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </header>

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

            // Notification Dropdown Toggle
            const notificationBtn = document.getElementById('notification-btn');
            const notificationDropdown = document.getElementById('notification-dropdown');

            if (notificationBtn && notificationDropdown) {
                notificationBtn.addEventListener('click', (e) => {
                    e.stopPropagation();
                    notificationDropdown.classList.toggle('hidden');
                });

                document.addEventListener('click', (e) => {
                    if (!notificationDropdown.contains(e.target) && e.target !== notificationBtn) {
                        notificationDropdown.classList.add('hidden');
                    }
                });
            }
        });
    </script>
    @yield('scripts')
</body>
</html>
