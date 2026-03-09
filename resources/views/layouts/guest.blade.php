<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700,800&display=swap" rel="stylesheet" />

        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    @php
        $isAdminLogin = request()->routeIs('admin.login*');
    @endphp
    <body class="app-body theme-guest {{ $isAdminLogin ? 'theme-admin-auth' : 'theme-student-auth' }}">
        <div class="guest-shell">
            <div class="guest-brand-panel">
                <a href="{{ route('home') }}" class="inline-flex h-16 w-16 items-center justify-center rounded-3xl bg-white/90 text-sky-700 shadow-lg shadow-sky-950/10">
                    <x-application-logo class="h-10 w-10 fill-current" />
                </a>

                <div class="space-y-5">
                    <span class="inline-flex rounded-full border border-white/40 bg-white/20 px-4 py-1 text-xs font-semibold uppercase tracking-[0.28em] text-white">
                        {{ $isAdminLogin ? 'Admin Access' : 'Student Access' }}
                    </span>
                    <div class="space-y-3">
                        <h1 class="text-4xl font-extrabold leading-tight text-white sm:text-5xl">
                            {{ $isAdminLogin ? 'Review payments and manage the academy.' : 'Build a learning flow that feels like a real platform.' }}
                        </h1>
                        <p class="max-w-xl text-base leading-7 text-white/78">
                            {{ $isAdminLogin
                                ? 'Use the administrator entry point to review proofs, unlock courses, and keep catalog operations separated from student access.'
                                : 'Browse courses, add them to your panier, upload one transfer proof per purchase, and continue from a dedicated learning space.' }}
                        </p>
                    </div>
                </div>

                <div class="grid gap-3 sm:grid-cols-2">
                    <div class="guest-stat">
                        <span class="guest-stat-label">Flow</span>
                        <strong class="guest-stat-value">{{ $isAdminLogin ? 'CRUD + approvals' : 'Catalog -> panier -> unlock' }}</strong>
                    </div>
                    <div class="guest-stat">
                        <span class="guest-stat-label">Security</span>
                        <strong class="guest-stat-value">Private proof storage and role-based access</strong>
                    </div>
                </div>
            </div>

            <div class="guest-form-panel">
                {{ $slot }}
            </div>
        </div>
    </body>
</html>
