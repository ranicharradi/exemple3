<x-app-layout>
    <x-slot name="header">
        <div class="grid gap-6 lg:grid-cols-[1.15fr_0.85fr] lg:items-end">
            <div class="space-y-4">
                <p class="kicker">Course catalog</p>
                <h2 class="text-4xl font-extrabold tracking-tight text-slate-950 sm:text-5xl">
                    Learn with a cleaner, purchase-first flow.
                </h2>
                <p class="max-w-2xl text-base leading-7 text-slate-600">
                    Discover practical courses, move the ones you want into your panier, then finish payment and access from your student dashboard instead of juggling steps on each course page.
                </p>
            </div>

            <div class="panel bg-gradient-to-br from-sky-600 via-blue-600 to-cyan-400 text-white">
                <p class="text-xs font-semibold uppercase tracking-[0.28em] text-white/70">How it works</p>
                <div class="mt-4 grid gap-3 text-sm leading-6 md:grid-cols-3 lg:grid-cols-1">
                    <div class="rounded-[1.5rem] bg-white/12 p-4">1. Open a course and check the details.</div>
                    <div class="rounded-[1.5rem] bg-white/12 p-4">2. Use <strong>Purchase</strong> to push it into the panier.</div>
                    <div class="rounded-[1.5rem] bg-white/12 p-4">3. Upload proof from the dashboard and wait for approval.</div>
                </div>
            </div>
        </div>
    </x-slot>

    <div class="py-10">
        <div class="mx-auto max-w-7xl space-y-8 px-4 sm:px-6 lg:px-8">
            <section class="grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
                <div class="panel-muted">
                    <p class="text-xs font-semibold uppercase tracking-[0.28em] text-slate-400">Courses</p>
                    <p class="mt-3 text-3xl font-extrabold text-slate-950">{{ $courses->total() }}</p>
                    <p class="mt-2 text-sm text-slate-500">Published in the current catalog.</p>
                </div>
                <div class="panel-muted">
                    <p class="text-xs font-semibold uppercase tracking-[0.28em] text-slate-400">Format</p>
                    <p class="mt-3 text-2xl font-extrabold text-slate-950">Video + outline</p>
                    <p class="mt-2 text-sm text-slate-500">Protected course page after approval.</p>
                </div>
                <div class="panel-muted">
                    <p class="text-xs font-semibold uppercase tracking-[0.28em] text-slate-400">Payments</p>
                    <p class="mt-3 text-2xl font-extrabold text-slate-950">Manual review</p>
                    <p class="mt-2 text-sm text-slate-500">Private proof uploads and admin validation.</p>
                </div>
                <div class="panel-muted">
                    <p class="text-xs font-semibold uppercase tracking-[0.28em] text-slate-400">Dashboard</p>
                    <p class="mt-3 text-2xl font-extrabold text-slate-950">Panier centered</p>
                    <p class="mt-2 text-sm text-slate-500">Track payment decisions and locked courses.</p>
                </div>
            </section>

            <section class="grid gap-6 md:grid-cols-2 xl:grid-cols-3">
                @forelse ($courses as $course)
                    <article class="panel flex h-full flex-col">
                        <div class="flex items-start justify-between gap-4">
                            <div>
                                <p class="kicker">Practical track</p>
                                <h3 class="mt-3 text-2xl font-extrabold tracking-tight text-slate-950">{{ $course->title }}</h3>
                            </div>
                            <span class="rounded-full bg-slate-950 px-4 py-2 text-sm font-bold text-white">
                                ${{ number_format((float) $course->price, 2) }}
                            </span>
                        </div>

                        <p class="mt-5 flex-1 text-sm leading-7 text-slate-600">{{ Str::limit($course->description, 170) }}</p>

                        <div class="mt-6 flex items-center justify-between gap-3">
                            <a href="{{ route('courses.show', $course) }}" class="btn-primary">View details</a>
                            <span class="text-sm font-semibold text-slate-400">Protected after approval</span>
                        </div>
                    </article>
                @empty
                    <div class="rounded-[2rem] border border-dashed border-slate-300 bg-white/70 p-10 text-sm text-slate-500 md:col-span-2 xl:col-span-3">
                        No courses are available yet.
                    </div>
                @endforelse
            </section>

            <div class="pt-2">
                {{ $courses->links() }}
            </div>
        </div>
    </div>
</x-app-layout>
