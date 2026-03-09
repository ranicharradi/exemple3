<x-guest-layout>
    @php
        $isAdminLogin = ($loginMode ?? 'student') === 'admin';
    @endphp

    <div class="space-y-8">
        <div class="space-y-4">
            <x-auth-session-status class="text-sm text-emerald-600" :status="session('status')" />

            <div class="space-y-3">
                <span class="inline-flex rounded-full border border-slate-200 bg-slate-50 px-4 py-1 text-xs font-semibold uppercase tracking-[0.28em] text-slate-500">
                    {{ $isAdminLogin ? 'Admin Login' : 'Student Login' }}
                </span>
                <div>
                    <h2 class="text-3xl font-extrabold tracking-tight text-slate-950">
                        {{ $isAdminLogin ? 'Login as admin' : 'Continue learning' }}
                    </h2>
                    <p class="mt-2 text-sm leading-6 text-slate-500">
                        {{ $isAdminLogin
                            ? 'Only administrator accounts are allowed here. Student accounts must use the standard login.'
                            : 'Use the student portal to manage your panier, upload payment proofs, and access unlocked courses.' }}
                    </p>
                </div>
            </div>

            <div class="flex flex-wrap gap-3 text-sm">
                @if ($isAdminLogin)
                    <a href="{{ route('login') }}" class="btn-ghost">Switch to student login</a>
                @else
                    <a href="{{ route('admin.login') }}" class="btn-admin-entry">Login as admin</a>
                    <a href="{{ route('register') }}" class="btn-primary">Create student account</a>
                @endif
            </div>
        </div>

        <form method="POST" action="{{ $isAdminLogin ? route('admin.login.store') : route('login') }}" class="space-y-5">
            @csrf

            <div>
                <label for="email" class="mb-2 block text-sm font-semibold text-slate-700">Email</label>
                <input id="email" class="form-input-auth" type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="username" />
                <x-input-error :messages="$errors->get('email')" class="mt-2" />
            </div>

            <div>
                <label for="password" class="mb-2 block text-sm font-semibold text-slate-700">Password</label>
                <input id="password" class="form-input-auth" type="password" name="password" required autocomplete="current-password" />
                <x-input-error :messages="$errors->get('password')" class="mt-2" />
            </div>

            <label for="remember_me" class="inline-flex items-center gap-3 text-sm text-slate-600">
                <input id="remember_me" type="checkbox" class="rounded border-slate-300 text-sky-600 shadow-sm focus:ring-sky-500" name="remember">
                <span>Remember me</span>
            </label>

            <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                @if (Route::has('password.request'))
                    <a class="text-sm font-semibold text-slate-500 hover:text-slate-900" href="{{ route('password.request') }}">
                        Forgot your password?
                    </a>
                @endif

                <button type="submit" class="{{ $isAdminLogin ? 'btn-admin-entry' : 'btn-primary' }}">
                    {{ $isAdminLogin ? 'Enter admin workspace' : 'Login' }}
                </button>
            </div>
        </form>

        <div class="rounded-3xl border border-slate-200 bg-slate-50 p-5 text-sm leading-6 text-slate-600">
            {{ $isAdminLogin
                ? 'Admin access stays isolated from the student flow. If this is not an administrator account, return to the student login page.'
                : 'After login, you can add courses to your panier, upload a bank transfer proof once, and wait for admin validation before the course unlocks.' }}
        </div>
    </div>
</x-guest-layout>
