<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-5 lg:flex-row lg:items-end lg:justify-between">
            <div class="max-w-3xl">
                <p class="kicker">Admin control center</p>
                <h2 class="mt-2 text-4xl font-extrabold tracking-tight text-slate-950">Run the platform from one management workspace.</h2>
                <p class="mt-3 text-base leading-7 text-slate-600">
                    This side is intentionally different from the student experience. CRUD actions, payment decisions, and student monitoring stay visible and separated.
                </p>
            </div>
            <div class="flex flex-wrap gap-3">
                <a href="{{ route('admin.courses.create') }}" class="btn-admin-entry">Create new course</a>
                <a href="{{ route('admin.payments.index', ['status' => 'pending']) }}" class="btn-ghost">Review pending payments</a>
            </div>
        </div>
    </x-slot>

    <div class="py-10">
        <div class="mx-auto max-w-7xl space-y-8 px-4 sm:px-6 lg:px-8">
            <section class="grid gap-6 md:grid-cols-3">
                <div class="panel border-amber-200 bg-gradient-to-br from-white to-amber-50">
                    <p class="text-xs font-semibold uppercase tracking-[0.28em] text-amber-500">Catalog</p>
                    <p class="mt-4 text-4xl font-extrabold tracking-tight text-slate-950">{{ $courseCount }}</p>
                    <p class="mt-2 text-sm text-slate-500">Courses available for purchase and editing.</p>
                </div>
                <div class="panel border-orange-200 bg-gradient-to-br from-white to-orange-50">
                    <p class="text-xs font-semibold uppercase tracking-[0.28em] text-orange-500">Reviews</p>
                    <p class="mt-4 text-4xl font-extrabold tracking-tight text-slate-950">{{ $pendingPaymentsCount }}</p>
                    <p class="mt-2 text-sm text-slate-500">Payment proofs waiting for a decision.</p>
                </div>
                <div class="panel border-yellow-200 bg-gradient-to-br from-white to-yellow-50">
                    <p class="text-xs font-semibold uppercase tracking-[0.28em] text-yellow-600">Students</p>
                    <p class="mt-4 text-4xl font-extrabold tracking-tight text-slate-950">{{ $studentCount }}</p>
                    <p class="mt-2 text-sm text-slate-500">Registered student accounts on the platform.</p>
                </div>
            </section>

            <section class="grid gap-6 lg:grid-cols-3">
                <a href="{{ route('admin.courses.index') }}" class="panel transition hover:-translate-y-1">
                    <div class="flex items-center justify-between gap-4">
                        <div>
                            <p class="kicker">CRUD</p>
                            <h3 class="mt-2 text-2xl font-extrabold tracking-tight text-slate-950">Manage courses</h3>
                        </div>
                        <span class="rounded-full bg-amber-100 px-4 py-2 text-sm font-bold text-amber-800">Create / edit / delete</span>
                    </div>
                    <p class="mt-5 text-sm leading-7 text-slate-600">Open the course management page to create new entries, edit content, and delete unused courses when they have no dependencies.</p>
                </a>

                <a href="{{ route('admin.payments.index') }}" class="panel transition hover:-translate-y-1">
                    <div class="flex items-center justify-between gap-4">
                        <div>
                            <p class="kicker">Actions</p>
                            <h3 class="mt-2 text-2xl font-extrabold tracking-tight text-slate-950">Payment review</h3>
                        </div>
                        <span class="rounded-full bg-orange-100 px-4 py-2 text-sm font-bold text-orange-800">Approve / reject</span>
                    </div>
                    <p class="mt-5 text-sm leading-7 text-slate-600">Review proofs, download receipts, and unlock enrollments directly from the payment action board.</p>
                </a>

                <a href="{{ route('admin.students.index') }}" class="panel transition hover:-translate-y-1">
                    <div class="flex items-center justify-between gap-4">
                        <div>
                            <p class="kicker">Visibility</p>
                            <h3 class="mt-2 text-2xl font-extrabold tracking-tight text-slate-950">Student activity</h3>
                        </div>
                        <span class="rounded-full bg-yellow-100 px-4 py-2 text-sm font-bold text-yellow-800">Track usage</span>
                    </div>
                    <p class="mt-5 text-sm leading-7 text-slate-600">Check which students have submitted proofs, how many active courses they unlocked, and when they joined.</p>
                </a>
            </section>
        </div>
    </div>
</x-app-layout>
