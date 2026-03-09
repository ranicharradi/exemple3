@php
    $isAdmin = auth()->check() && auth()->user()->isAdmin();
    $cartCount = auth()->check() && ! $isAdmin ? auth()->user()->cartItems()->count() : 0;
    $brandTarget = auth()->check()
        ? ($isAdmin ? route('admin.dashboard') : route('dashboard'))
        : route('courses.index');
@endphp

<nav x-data="{ open: false }" class="nav-shell">
    <div class="mx-auto flex max-w-7xl items-center justify-between gap-4 px-4 py-4 sm:px-6 lg:px-8">
        <div class="flex items-center gap-4 lg:gap-10">
            <a href="{{ $brandTarget }}" class="nav-brand">
                <span class="nav-brand-mark">
                    <x-application-logo class="h-8 w-8 fill-current" />
                </span>
                <span class="hidden sm:flex sm:flex-col">
                    <span class="text-sm font-extrabold tracking-tight text-slate-950">
                        {{ $isAdmin ? 'Codex Control' : 'Codex Academy' }}
                    </span>
                    <span class="text-xs font-medium text-slate-500">
                        {{ $isAdmin ? 'Admin workspace' : 'Learning platform' }}
                    </span>
                </span>
            </a>

            <div class="hidden items-center gap-2 md:flex">
                <a href="{{ route('courses.index') }}" @class(['nav-link', 'nav-link-active' => request()->routeIs('home') || request()->routeIs('courses.index') || request()->routeIs('courses.show')])>
                    Courses
                </a>

                @auth
                    @if ($isAdmin)
                        <a href="{{ route('admin.dashboard') }}" @class(['nav-link', 'nav-link-active' => request()->routeIs('admin.dashboard')])>Overview</a>
                        <a href="{{ route('admin.courses.index') }}" @class(['nav-link', 'nav-link-active' => request()->routeIs('admin.courses.*')])>Courses CRUD</a>
                        <a href="{{ route('admin.payments.index') }}" @class(['nav-link', 'nav-link-active' => request()->routeIs('admin.payments.*')])>Payment Actions</a>
                        <a href="{{ route('admin.students.index') }}" @class(['nav-link', 'nav-link-active' => request()->routeIs('admin.students.*')])>Students</a>
                    @else
                        <a href="{{ route('dashboard') }}" @class(['nav-link', 'nav-link-active' => request()->routeIs('dashboard')])>Dashboard</a>
                        <a href="{{ route('dashboard') }}#panier" @class(['nav-link nav-link-panier', 'nav-link-active' => request()->routeIs('dashboard')])>
                            Panier
                            @if ($cartCount > 0)
                                <span class="nav-count">{{ $cartCount }}</span>
                            @endif
                        </a>
                        <a href="{{ route('payments.index') }}" @class(['nav-link', 'nav-link-active' => request()->routeIs('payments.*')])>My Payments</a>
                        <a href="{{ route('courses.my') }}" @class(['nav-link', 'nav-link-active' => request()->routeIs('courses.my') || request()->routeIs('courses.watch')])>My Courses</a>
                    @endif
                @endauth
            </div>
        </div>

        <div class="hidden items-center gap-3 md:flex">
            @auth
                <x-dropdown align="right" width="56">
                    <x-slot name="trigger">
                        <button class="profile-trigger">
                            <span class="profile-avatar">{{ strtoupper(substr(Auth::user()->name, 0, 1)) }}</span>
                            <span class="text-left">
                                <span class="block text-sm font-semibold text-slate-900">{{ Auth::user()->name }}</span>
                                <span class="profile-role">{{ Auth::user()->role }}</span>
                            </span>
                            <svg class="h-4 w-4 text-slate-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M5.23 7.21a.75.75 0 011.06.02L10 11.168l3.71-3.938a.75.75 0 111.08 1.04l-4.25 4.5a.75.75 0 01-1.08 0l-4.25-4.5a.75.75 0 01.02-1.06z" clip-rule="evenodd" />
                            </svg>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <x-dropdown-link :href="route('profile.edit')">
                            Profile
                        </x-dropdown-link>

                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <x-dropdown-link :href="route('logout')" onclick="event.preventDefault(); this.closest('form').submit();">
                                Log Out
                            </x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
            @else
                <a href="{{ route('login') }}" class="btn-ghost">Login</a>
                <a href="{{ route('admin.login') }}" class="btn-admin-entry">Login as admin</a>
                <a href="{{ route('register') }}" class="btn-primary">Register</a>
            @endauth
        </div>

        <button @click="open = !open" class="inline-flex h-11 w-11 items-center justify-center rounded-2xl border border-slate-200 bg-white text-slate-700 md:hidden">
            <svg class="h-5 w-5" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                <path :class="{ 'hidden': open, 'inline-flex': !open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 7h16M4 12h16M4 17h16" />
                <path :class="{ 'hidden': !open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
        </button>
    </div>

    <div x-cloak x-show="open" class="border-t border-slate-200/80 bg-white/95 px-4 py-4 md:hidden">
        <div class="space-y-2">
            <a href="{{ route('courses.index') }}" class="mobile-nav-link">Courses</a>

            @auth
                @if ($isAdmin)
                    <a href="{{ route('admin.dashboard') }}" class="mobile-nav-link">Overview</a>
                    <a href="{{ route('admin.courses.index') }}" class="mobile-nav-link">Courses CRUD</a>
                    <a href="{{ route('admin.payments.index') }}" class="mobile-nav-link">Payment Actions</a>
                    <a href="{{ route('admin.students.index') }}" class="mobile-nav-link">Students</a>
                @else
                    <a href="{{ route('dashboard') }}" class="mobile-nav-link">Dashboard</a>
                    <a href="{{ route('dashboard') }}#panier" class="mobile-nav-link">Panier ({{ $cartCount }})</a>
                    <a href="{{ route('payments.index') }}" class="mobile-nav-link">My Payments</a>
                    <a href="{{ route('courses.my') }}" class="mobile-nav-link">My Courses</a>
                @endif

                <a href="{{ route('profile.edit') }}" class="mobile-nav-link">Profile</a>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="mobile-nav-link w-full text-left">Log Out</button>
                </form>
            @else
                <a href="{{ route('login') }}" class="mobile-nav-link">Login</a>
                <a href="{{ route('admin.login') }}" class="mobile-nav-link">Login as admin</a>
                <a href="{{ route('register') }}" class="mobile-nav-link">Register</a>
            @endauth
        </div>
    </div>
</nav>
