<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Admin Dashboard') | BoneHard</title>
    <link
        href="https://fonts.googleapis.com/css2?family=IBM+Plex+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700&family=IBM+Plex+Sans+Arabic:wght@300;400;500;600;700&display=swap"
        rel="stylesheet">
    <link rel="icon" type="image/png" href="{{ asset('images/favicon.png') }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        body {
            font-family: "IBM Plex Sans", "IBM Plex Sans Arabic", system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif;
        }

        :root {
            --bh-bg: #060606;
            --bh-surface: #0c0c0c;
            --bh-surface-2: #111111;
            --bh-border: rgba(255, 255, 255, 0.14);
            --bh-border-strong: rgba(255, 255, 255, 0.22);
            --bh-text: #f5f5f5;
            --bh-muted: rgba(245, 245, 245, 0.68);
            --bh-accent: rgba(255, 255, 255, 0.92);
            --bh-accent-soft: rgba(255, 255, 255, 0.12);
            --bh-accent-soft-hover: rgba(255, 255, 255, 0.18);
        }

        .bh-nav-link {
            position: relative;
        }

        .bh-nav-link-active {
            background: linear-gradient(90deg, rgba(255, 255, 255, 0.14), rgba(255, 255, 255, 0.06), transparent);
            color: #ffffff !important;
            border: 1px solid rgba(255, 255, 255, 0.18);
        }

        .bh-nav-link-active::before {
            content: '';
            position: absolute;
            left: 10px;
            top: 50%;
            transform: translateY(-50%);
            width: 6px;
            height: 6px;
            border-radius: 9999px;
            background: rgba(255, 255, 255, 0.9);
            box-shadow: 0 0 0 4px rgba(255, 255, 255, 0.08);
        }

        .bh-card {
            position: relative;
            border-radius: 1.5rem;
            border: 1px solid rgba(255, 255, 255, 0.14);
            background: linear-gradient(180deg, rgba(255,255,255,0.06), rgba(255,255,255,0.02));
            box-shadow:
                0 18px 55px rgba(0, 0, 0, 0.55),
                inset 0 1px 0 rgba(255, 255, 255, 0.06);
            transition:
                transform 160ms ease,
                border-color 160ms ease,
                box-shadow 160ms ease;
        }

        .bh-card::before {
            content: '';
            position: absolute;
            inset: 0;
            border-radius: inherit;
            pointer-events: none;
            background: radial-gradient(600px 220px at 20% 0%, rgba(255,255,255,0.12), transparent 60%);
            opacity: 0.8;
        }

        .bh-card:hover {
            transform: translateY(-2px);
            border-color: rgba(255, 255, 255, 0.24);
            box-shadow:
                0 26px 80px rgba(0, 0, 0, 0.65),
                inset 0 1px 0 rgba(255, 255, 255, 0.08);
        }

        .bh-card-icon {
            border-radius: 0.9rem;
            border: 1px solid rgba(255, 255, 255, 0.16);
            background: rgba(0, 0, 0, 0.35);
            color: rgba(255, 255, 255, 0.92);
            box-shadow: inset 0 1px 0 rgba(255, 255, 255, 0.06);
        }

        .bh-badge {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 9999px;
            padding: 0.25rem 0.75rem;
            font-size: 10px;
            font-weight: 700;
            letter-spacing: 0.18em;
            text-transform: uppercase;
            border: 1px solid rgba(255, 255, 255, 0.14);
        }

        .bh-badge--pending {
            color: #ffffff !important;
            background: rgba(245, 158, 11, 0.16) !important;
            border-color: rgba(245, 158, 11, 0.35) !important;
        }

        .bh-badge--reviewed {
            color: rgba(209, 250, 229, 0.95) !important;
            background: rgba(16, 185, 129, 0.14) !important;
            border-color: rgba(16, 185, 129, 0.30) !important;
        }

        .bh-btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 9999px;
            padding: 0.25rem 0.85rem;
            font-size: 10px;
            font-weight: 700;
            border: 1px solid rgba(255, 255, 255, 0.16);
            color: rgba(255, 255, 255, 0.9);
            background: rgba(0, 0, 0, 0.35);
            transition: background-color 160ms ease, border-color 160ms ease, transform 160ms ease;
        }

        .bh-btn:hover {
            background: rgba(255, 255, 255, 0.08);
            border-color: rgba(255, 255, 255, 0.28);
            transform: translateY(-1px);
        }

        .bh-btn--review {
            border-color: rgba(16, 185, 129, 0.30);
        }

        .bh-btn--unreview {
            border-color: rgba(245, 158, 11, 0.35);
        }

        [class*="text-amber-"] { color: var(--bh-accent) !important; }
        [class*="text-blue-"] { color: var(--bh-accent) !important; }
        [class*="text-emerald-"] { color: var(--bh-accent) !important; }
        [class*="text-red-"] { color: var(--bh-accent) !important; }

        [class*="bg-amber-"] { background-color: var(--bh-accent-soft) !important; }
        [class*="bg-blue-"] { background-color: var(--bh-accent-soft) !important; }
        [class*="bg-emerald-"] { background-color: var(--bh-accent-soft) !important; }
        [class*="bg-red-"] { background-color: var(--bh-accent-soft) !important; }

        [class*="border-amber-"] { border-color: var(--bh-border-strong) !important; }
        [class*="border-blue-"] { border-color: var(--bh-border-strong) !important; }
        [class*="border-emerald-"] { border-color: var(--bh-border-strong) !important; }
        [class*="border-red-"] { border-color: var(--bh-border-strong) !important; }

        [class*="hover:text-amber"]:hover { color: #ffffff !important; }
        [class*="hover:text-blue"]:hover { color: #ffffff !important; }
        [class*="hover:text-emerald"]:hover { color: #ffffff !important; }
        [class*="hover:text-red"]:hover { color: #ffffff !important; }

        [class*="hover:border-amber"]:hover { border-color: rgba(255, 255, 255, 0.6) !important; }
        [class*="hover:border-blue"]:hover { border-color: rgba(255, 255, 255, 0.6) !important; }
        [class*="hover:border-emerald"]:hover { border-color: rgba(255, 255, 255, 0.6) !important; }
        [class*="hover:border-red"]:hover { border-color: rgba(255, 255, 255, 0.6) !important; }

        [class*="hover:bg-amber"]:hover { background-color: var(--bh-accent-soft-hover) !important; }
        [class*="hover:bg-blue"]:hover { background-color: var(--bh-accent-soft-hover) !important; }
        [class*="hover:bg-emerald"]:hover { background-color: var(--bh-accent-soft-hover) !important; }
        [class*="hover:bg-red"]:hover { background-color: var(--bh-accent-soft-hover) !important; }

        [class*="bg-slate-"] { background-color: var(--bh-surface) !important; }
        [class*="bg-gray-"] { background-color: var(--bh-surface) !important; }
        [class*="text-slate-"] { color: var(--bh-text) !important; }
        [class*="text-gray-"] { color: var(--bh-text) !important; }
        [class*="border-slate-"] { border-color: var(--bh-border) !important; }
        [class*="border-gray-"] { border-color: var(--bh-border) !important; }

        @keyframes bhFadeIn {
            from { opacity: 0; transform: translateY(8px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .bh-page-animate {
            animation: bhFadeIn 0.4s cubic-bezier(0.2, 0.8, 0.2, 1) forwards;
        }

        /* Global Loading Overlay */
        #bh-global-loading {
            position: fixed;
            inset: 0;
            z-index: 9999;
            background: rgba(6, 6, 6, 0.7);
            backdrop-filter: blur(12px);
            display: flex;
            align-items: center;
            justify-content: center;
            opacity: 0;
            pointer-events: none;
            transition: opacity 0.3s ease;
        }
        #bh-global-loading.show {
            opacity: 1;
            pointer-events: auto;
        }
        .bh-loading-spinner {
            width: 40px;
            height: 40px;
            border: 3px solid rgba(255, 255, 255, 0.1);
            border-top-color: rgba(255, 255, 255, 0.9);
            border-radius: 50%;
            animation: bh-spin 0.8s linear infinite;
        }
        @keyframes bh-spin {
            to { transform: rotate(360deg); }
        }
    </style>
</head>

<body class="min-h-screen text-slate-100" style="background: radial-gradient(1200px 600px at 18% 0%, rgba(255,255,255,0.06), transparent 60%), radial-gradient(900px 500px at 85% 20%, rgba(255,255,255,0.04), transparent 65%), var(--bh-bg);">
    <div id="bh-global-loading">
        <div class="flex flex-col items-center gap-4">
            <div class="bh-loading-spinner"></div>
            <p class="text-white/60 text-sm font-medium tracking-widest uppercase">Loading</p>
        </div>
    </div>
    <div class="min-h-screen flex">
        <div id="bh-admin-sidebar-overlay" class="hidden fixed inset-0 z-40 md:hidden" style="background: rgba(0,0,0,0.72);"></div>
        <aside id="bh-admin-sidebar" class="fixed inset-y-0 left-0 z-50 w-72 -translate-x-full md:translate-x-0 md:static md:z-auto md:flex md:w-68 flex-col border-r border-slate-800/80 bg-slate-950/95 transition-transform duration-200" style="background-color: var(--bh-surface-2);">
            <div
                class="h-16 flex items-center px-6 border-b border-slate-800/80" style="background-color: var(--bh-surface-2);">
                <div class="flex items-center gap-3 w-full">
                    {{-- <div
                        class="h-10 w-10 rounded-2xl bg-slate-900/80 border border-amber-400/70 flex items-center justify-center overflow-hidden">
                        <img src="{{ asset('images/favicon.png') }}" alt="BoneHard Admin"
                            class="h-20 w-20 object-contain" />
                    </div> --}}
                    <div>
                        <p class="text-xl md:text-2xl font-semibold tracking-wide">BoneHard Admin</p>
                    </div>
                    <button id="bh-admin-sidebar-close" type="button" class="ml-auto inline-flex md:hidden items-center justify-center rounded-lg border border-white/15 bg-white/5 px-3 py-2 text-white/90 hover:bg-white/10">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="h-5 w-5">
                            <path d="M18 6 6 18" />
                            <path d="M6 6l12 12" />
                        </svg>
                    </button>
                </div>
            </div>
            <nav class="flex-1 px-4 py-4 space-y-1 text-base md:text-lg">
                <a href="{{ route('admin.dashboard') }}"
                    class="bh-nav-link block rounded-lg px-3 py-2 pl-7 text-sm md:text-base font-medium {{ request()->routeIs('admin.dashboard') ? 'bh-nav-link-active' : 'text-slate-300 hover:bg-slate-900 hover:text-white border border-transparent' }}">Dashboard</a>
                <a href="{{ route('admin.users.index') }}"
                    class="bh-nav-link block rounded-lg px-3 py-2 pl-7 text-sm md:text-base font-medium {{ request()->routeIs('admin.users.*') ? 'bh-nav-link-active' : 'text-slate-300 hover:bg-slate-900 hover:text-white border border-transparent' }}">Users</a>
                <a href="{{ route('admin.assistants.index') }}"
                    class="bh-nav-link block rounded-lg px-3 py-2 pl-7 text-sm md:text-base font-medium {{ request()->routeIs('admin.assistants.*') ? 'bh-nav-link-active' : 'text-slate-300 hover:bg-slate-900 hover:text-white border border-transparent' }}">Admin
                    Assistants</a>
                <a href="{{ route('admin.stats') }}"
                    class="bh-nav-link block rounded-lg px-3 py-2 pl-7 text-sm md:text-base font-medium {{ request()->routeIs('admin.stats') ? 'bh-nav-link-active' : 'text-slate-300 hover:bg-slate-900 hover:text-white border border-transparent' }}">Analytics</a>
                <div
                    class="pt-4 mt-4 border-t border-slate-800 text-[13px] font-semibold text-slate-400 uppercase tracking-[0.18em]">
                    Chats</div>
                <a href="{{ route('admin.chats.assistants') }}"
                    class="bh-nav-link block rounded-lg px-3 py-2 pl-7 {{ request()->routeIs('admin.chats.assistants*') ? 'bh-nav-link-active' : 'text-slate-300 hover:bg-slate-900 hover:text-white border border-transparent' }}">With
                    Assistants</a>
                <a href="{{ route('admin.chats.users') }}"
                    class="bh-nav-link block rounded-lg px-3 py-2 pl-7 {{ request()->routeIs('admin.chats.users*') ? 'bh-nav-link-active' : 'text-slate-300 hover:bg-slate-900 hover:text-white border border-transparent' }}">With
                    Users</a>
                <a href="{{ route('admin.chats.groups') }}"
                    class="bh-nav-link flex items-center justify-between gap-3 rounded-lg px-3 py-2 pl-7 {{ request()->routeIs('admin.chats.groups*') ? 'bh-nav-link-active' : 'text-slate-300 hover:bg-slate-900 hover:text-white border border-transparent' }}">
                    <span>User Group Chats</span>
                    @if (!empty($bhGroupChatHasNew))
                        <span class="inline-flex items-center justify-center rounded-full border border-white/20 bg-white/10 px-2 py-0.5 text-[10px] font-semibold text-white">New</span>
                    @endif
                </a>
            </nav>
        </aside>

        <div class="flex-1 flex flex-col">
            <header
                class="h-16 border-b border-slate-800/80 flex items-center justify-between px-4 md:px-8 bg-black/70 backdrop-blur-xl" style="background-color: rgba(12,12,12,0.78);">
                <div class="flex items-center gap-2 md:hidden">
                    <button id="bh-admin-sidebar-toggle" type="button" class="inline-flex items-center justify-center rounded-lg border border-white/15 bg-white/5 px-3 py-2 text-white/90 hover:bg-white/10">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="h-5 w-5">
                            <path d="M4 6h16" />
                            <path d="M4 12h16" />
                            <path d="M4 18h16" />
                        </svg>
                    </button>
                    <div
                        class="h-8 w-8 rounded-xl bg-slate-900/80 border border-amber-400/70 flex items-center justify-center overflow-hidden">
                        <img src="{{ asset('images/favicon.png') }}" alt="BoneHard Admin"
                            class="h-7 w-7 object-contain" />
                    </div>
                    <span class="text-sm font-semibold">Admin</span>
                </div>
                <div class="hidden md:block">
                    <h1 class="text-base md:text-lg font-semibold tracking-wide text-slate-200">@yield('header', 'Dashboard')</h1>
                </div>
                <div class="flex items-center gap-4 text-sm md:text-base">
                    @auth
                        @php
                            $__unreadAdminMessages = 0;
                            $__currentAdmin = auth()->user();
                            $__adminConversations = \App\Models\Conversation::whereIn('type', ['admin_assistant', 'admin_user'])
                                ->where(function ($q) use ($__currentAdmin) {
                                    $q->where('admin_id', $__currentAdmin->id)
                                        ->orWhere('participant_id', $__currentAdmin->id);
                                })
                                ->with(['messages' => function ($q) {
                                    $q->latest()->limit(1);
                                }])
                                ->get();

                            $__unreadAdminMessages = $__adminConversations
                                ->filter(function ($conversation) use ($__currentAdmin) {
                                    $last = $conversation->messages->first();

                                    return $last && $last->sender_id !== $__currentAdmin->id;
                                })
                                ->count();
                        @endphp

                        @if ($__unreadAdminMessages > 0)
                            <a href="{{ route('admin.notifications.index') }}"
                                class="relative rounded-full border border-amber-400/70 px-3 py-1.5 text-xs md:text-sm font-semibold text-amber-300 hover:bg-amber-400/10 transition-colors">
                                Messages
                                <span
                                    class="ml-2 inline-flex items-center justify-center rounded-full bg-red-500 text-white text-[10px] leading-none px-1.5 py-0.5">
                                    {{ $__unreadAdminMessages }}
                                </span>
                            </a>
                        @endif

                        <span class="hidden sm:inline text-slate-300 text-sm md:text-base">{{ auth()->user()->name }}</span>
                        <a href="{{ url('/') }}" target="_blank"
                            class="rounded-full border border-slate-600/70 px-4 py-1.5 text-sm font-semibold text-slate-200 hover:border-amber-400 hover:text-amber-300 transition-colors">Back
                            to site</a>
                        <form action="{{ route('logout') }}" method="POST">
                            @csrf
                            <button type="submit"
                                class="rounded-full border border-slate-600/70 px-4 py-1.5 text-sm font-semibold text-slate-200 hover:border-white hover:text-white transition-colors">Logout</button>
                        </form>
                    @endauth
                </div>
            </header>

            <main class="flex-1 p-4 md:p-8 bh-page-animate" style="background: transparent;">
                @if (session('status'))
                    <div
                        class="mb-4 rounded-lg border border-emerald-500/40 bg-emerald-500/10 px-4 py-3 text-xs text-emerald-200">
                        {{ session('status') }}
                    </div>
                @endif

                @yield('content')
            </main>
        </div>
    </div>
    <script>
        (function () {
            var sidebar = document.getElementById('bh-admin-sidebar');
            var overlay = document.getElementById('bh-admin-sidebar-overlay');
            var toggleBtn = document.getElementById('bh-admin-sidebar-toggle');
            var closeBtn = document.getElementById('bh-admin-sidebar-close');

            if (!sidebar || !overlay || !toggleBtn || !closeBtn) return;

            function openSidebar() {
                sidebar.classList.remove('-translate-x-full');
                sidebar.classList.add('translate-x-0');
                overlay.classList.remove('hidden');
                document.documentElement.classList.add('overflow-hidden');
            }

            function closeSidebar() {
                sidebar.classList.add('-translate-x-full');
                sidebar.classList.remove('translate-x-0');
                overlay.classList.add('hidden');
                document.documentElement.classList.remove('overflow-hidden');
            }

            toggleBtn.addEventListener('click', openSidebar);
            closeBtn.addEventListener('click', closeSidebar);
            overlay.addEventListener('click', closeSidebar);
            window.addEventListener('keydown', function (e) {
                if (e.key === 'Escape') closeSidebar();
            });

            sidebar.querySelectorAll('a').forEach(function (a) {
                a.addEventListener('click', closeSidebar);
            });

            window.addEventListener('resize', function () {
                if (window.innerWidth >= 768) closeSidebar();
            });
        })();
    </script>
    @if (session('welcome'))
        <div id="bh-welcome-toast" class="fixed top-5 right-5 z-[9999] w-[min(92vw,360px)]">
            <div class="rounded-2xl border border-amber-400/35 bg-black/80 backdrop-blur-xl px-4 py-3 shadow-2xl">
                <div class="flex items-start gap-3">
                    <div class="mt-0.5 h-9 w-9 rounded-xl bg-amber-400/15 border border-amber-400/35 flex items-center justify-center text-amber-200 font-bold text-sm">BH</div>
                    <div class="flex-1 min-w-0">
                        <p class="text-[11px] uppercase tracking-[0.18em] text-slate-400">Welcome</p>
                        <p class="mt-0.5 text-sm font-semibold text-slate-100">{{ session('welcome') }}</p>
                    </div>
                    <button type="button" id="bh-welcome-toast-close" class="text-slate-400 hover:text-amber-200 text-xs">✕</button>
                </div>
                <div class="mt-3 h-1.5 w-full rounded-full bg-white/10 overflow-hidden">
                    <div id="bh-welcome-toast-bar" class="h-full w-full bg-amber-400/70"></div>
                </div>
            </div>
        </div>
        <script>
            (function () {
                var toast = document.getElementById('bh-welcome-toast');
                var closeBtn = document.getElementById('bh-welcome-toast-close');
                var bar = document.getElementById('bh-welcome-toast-bar');
                if (!toast || !closeBtn || !bar) return;

                var duration = 4500;
                var started = Date.now();
                var raf;

                function tick() {
                    var elapsed = Date.now() - started;
                    var left = Math.max(0, 1 - (elapsed / duration));
                    bar.style.width = (left * 100) + '%';
                    if (elapsed >= duration) {
                        toast.remove();
                        return;
                    }
                    raf = requestAnimationFrame(tick);
                }

                function close() {
                    if (raf) cancelAnimationFrame(raf);
                    toast.remove();
                }

                closeBtn.addEventListener('click', close);
                tick();
            })();
        </script>
    @endif
    <script>
        (function () {
            function applyLoading(btn) {
                if (!btn || btn.dataset.bhLoadingApplied === '1') return;
                btn.dataset.bhLoadingApplied = '1';
                btn.dataset.bhOriginalHtml = btn.innerHTML;
                btn.disabled = true;
                btn.classList.add('bg-yellow-400', 'text-black', 'opacity-90', 'cursor-not-allowed');
                btn.innerHTML = '<span class="inline-flex items-center gap-2"><svg class="h-4 w-4 animate-spin" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"></path></svg><span>Loading...</span></span>';
            }

            function showGlobalLoading() {
                var loader = document.getElementById('bh-global-loading');
                if (loader) loader.classList.add('show');
            }

            document.addEventListener('submit', function (e) {
                var form = e.target;
                if (!form || !form.querySelector) return;
                var btn = form.querySelector('button[type="submit"]');
                applyLoading(btn);

                // Show global loading on form submit as well
                showGlobalLoading();
            }, true);

            // Show global loading on internal link clicks
            document.addEventListener('click', function (e) {
                var link = e.target.closest('a');
                if (!link) return;

                var href = link.getAttribute('href');
                if (!href || href === '#' || href.startsWith('javascript:') || link.target === '_blank') return;

                // Robust internal check
                var isInternal = href.startsWith('/') || 
                                 href.startsWith(window.location.origin) || 
                                 !href.includes('://'); 

                if (isInternal) {
                    showGlobalLoading();
                }
            }, true);
        })();
    </script>
    @stack('scripts')
</body>

</html>
