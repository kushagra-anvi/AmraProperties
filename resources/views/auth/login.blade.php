<!DOCTYPE html>
<html lang="en" class="h-full bg-amra-light">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login - Amra Property CRM</title>
    <link rel="icon" type="image/png" href="{{ asset('assets/images/logo.png') }}">
    
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

    <style>
        html,
        body {
            background: #f8fafc;
            color: #0f172a;
        }
    </style>
</head>
<body class="h-full font-sans antialiased overflow-hidden flex items-center justify-center bg-amra-light relative">
    <div class="w-full max-w-md px-6 relative z-10">
        <!-- Logo Header -->
        <div class="text-center mb-8">
            <span class="inline-flex items-center gap-2 bg-teal-500/10 border border-teal-500/20 text-teal-700 text-xs font-bold px-4 py-1.5 rounded-full uppercase tracking-wider mb-3">
                <i data-lucide="shield-check" class="w-3.5 h-3.5"></i> CRM Portal
            </span>
            <h1 class="text-3xl md:text-4xl font-serif font-extrabold text-amra-dark">
                Amra <span class="text-amra-primary italic">Property</span>
            </h1>
            <p class="mt-2 text-sm text-slate-500">Sign in to manage lead pipelines and tracking metrics</p>
        </div>

        <!-- Card Container -->
        <div class="bg-white border border-slate-100 rounded-3xl p-8 shadow-xl shadow-slate-200/70 relative overflow-hidden">
            <!-- Alert messages -->
            @if (session('status'))
                <div class="mb-4 p-4 rounded-xl bg-teal-500/10 border border-teal-500/20 text-teal-300 text-xs flex items-start gap-2">
                    <i data-lucide="info" class="w-4 h-4 shrink-0 mt-0.5"></i>
                    <span>{{ session('status') }}</span>
                </div>
            @endif

            @if ($errors->any())
                <div class="mb-4 p-4 rounded-xl bg-rose-500/10 border border-rose-500/20 text-rose-300 text-xs flex flex-col gap-1">
                    @foreach ($errors->all() as $error)
                        <div class="flex items-start gap-2">
                            <i data-lucide="alert-triangle" class="w-4 h-4 shrink-0 mt-0.5"></i>
                            <span>{{ $error }}</span>
                        </div>
                    @endforeach
                </div>
            @endif

            <form action="{{ route('login') }}" method="POST" class="space-y-6">
                @csrf

                <!-- Email Address -->
                <div>
                    <label for="email" class="block text-xs font-bold text-slate-600 uppercase tracking-wider mb-2">Email Address</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                            <i data-lucide="mail" class="w-4 h-4"></i>
                        </div>
                        <input id="email" name="email" type="email" autocomplete="email" required value="{{ old('email') }}"
                            class="w-full bg-slate-50 border border-slate-200 focus:border-amra-primary rounded-xl pl-10 pr-4 py-3.5 outline-none focus:ring-4 focus:ring-teal-500/10 transition-all font-medium text-sm text-slate-800 placeholder-slate-400" 
                            placeholder="john@example.com">
                    </div>
                </div>

                <!-- Password -->
                <div>
                    <div class="flex items-center justify-between mb-2">
                        <label for="password" class="block text-xs font-bold text-slate-600 uppercase tracking-wider">Password</label>
                    </div>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                            <i data-lucide="lock" class="w-4 h-4"></i>
                        </div>
                        <input id="password" name="password" type="password" autocomplete="current-password" required
                            class="w-full bg-slate-50 border border-slate-200 focus:border-amra-primary rounded-xl pl-10 pr-4 py-3.5 outline-none focus:ring-4 focus:ring-teal-500/10 transition-all font-medium text-sm text-slate-800 placeholder-slate-400" 
                            placeholder="••••••••">
                    </div>
                </div>

                <!-- Remember Me -->
                <div class="flex items-center justify-between">
                    <label class="flex items-center gap-2 cursor-pointer select-none">
                        <input type="checkbox" name="remember" class="w-4.5 h-4.5 rounded border-slate-300 bg-white text-amra-primary focus:ring-offset-white focus:ring-teal-500/20">
                        <span class="text-xs font-medium text-slate-500">Remember session</span>
                    </label>
                </div>

                <!-- Submit button -->
                <button type="submit" class="w-full bg-amra-primary hover:bg-teal-400 text-slate-950 py-4 rounded-xl font-extrabold text-sm active:scale-[0.99] transition-all duration-300 shadow-lg shadow-teal-500/10 flex items-center justify-center gap-2">
                    <i data-lucide="log-in" class="w-4 h-4"></i> Sign In
                </button>
            </form>
        </div>

        <!-- Back to Website Link -->
        <p class="mt-8 text-center text-xs text-slate-500">
            <a href="{{ route('site.home') }}" class="hover:text-amra-dark inline-flex items-center gap-1.5 transition-colors">
                <i data-lucide="arrow-left" class="w-3 h-3"></i> Back to public site
            </a>
        </p>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            if (window.lucide) {
                window.lucide.createIcons();
            }
        });
    </script>
</body>
</html>
