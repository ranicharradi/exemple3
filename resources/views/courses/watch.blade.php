<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-4 lg:flex-row lg:items-end lg:justify-between">
            <div>
                <p class="kicker">Protected learning space</p>
                <h2 class="mt-2 text-4xl font-extrabold tracking-tight text-slate-950">{{ $course->title }}</h2>
                <p class="mt-3 max-w-3xl text-base leading-7 text-slate-600">
                    This page only appears after approval. The course content, the video link, and the download action are kept behind enrolled access.
                </p>
            </div>
            <div class="flex flex-wrap gap-3">
                <a href="{{ route('courses.download', $course) }}" class="btn-ghost">Download outline</a>
                <a href="{{ route('courses.my') }}" class="btn-neutral">Back to my courses</a>
            </div>
        </div>
    </x-slot>

    @php
        $lessons = collect(preg_split('/(?<=[.!?])\s+/', trim($course->description)))
            ->filter()
            ->take(5)
            ->values();

        if ($lessons->isEmpty()) {
            $lessons = collect([
                'Course overview and expectations.',
                'Core concepts and architecture walkthrough.',
                'Hands-on implementation and review.',
                'Applied exercises and recap.',
            ]);
        }
    @endphp

    <div class="py-10">
        <div class="mx-auto grid max-w-7xl gap-6 px-4 sm:px-6 lg:grid-cols-[0.34fr_0.66fr] lg:px-8">
            <aside class="panel space-y-5">
                <div>
                    <p class="kicker">Course outline</p>
                    <h3 class="mt-2 text-2xl font-extrabold tracking-tight text-slate-950">Learning path</h3>
                </div>

                <div class="space-y-3">
                    @foreach ($lessons as $index => $lesson)
                        <div class="rounded-[1.5rem] border border-slate-200 bg-white p-4">
                            <div class="flex items-start gap-4">
                                <span class="inline-flex h-9 w-9 items-center justify-center rounded-full bg-sky-100 text-sm font-bold text-sky-700">
                                    {{ $index + 1 }}
                                </span>
                                <div>
                                    <p class="text-sm font-bold text-slate-950">Lesson {{ $index + 1 }}</p>
                                    <p class="mt-1 text-sm leading-6 text-slate-500">{{ $lesson }}</p>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </aside>

            <section class="space-y-6">
                <div class="panel overflow-hidden">
                    <div class="rounded-[2rem] bg-gradient-to-br from-slate-950 via-slate-900 to-sky-800 p-8 text-white">
                        <div class="flex flex-col gap-6 lg:flex-row lg:items-end lg:justify-between">
                            <div class="max-w-2xl">
                                <p class="text-xs font-semibold uppercase tracking-[0.28em] text-sky-200/70">Now learning</p>
                                <h3 class="mt-4 text-4xl font-extrabold tracking-tight">{{ $course->title }}</h3>
                                <p class="mt-4 text-sm leading-7 text-slate-300">{{ $course->description }}</p>
                            </div>

                            <div class="rounded-[1.75rem] bg-white/10 p-5 backdrop-blur">
                                <p class="text-xs font-semibold uppercase tracking-[0.28em] text-sky-100/70">Access</p>
                                <p class="mt-3 text-2xl font-bold">Unlocked</p>
                                <p class="mt-2 text-sm text-slate-300">Your course is available from this protected space.</p>
                            </div>
                        </div>

                        <div class="mt-8 flex flex-wrap gap-3">
                            <a href="{{ $course->video_url }}" target="_blank" rel="noopener noreferrer" class="inline-flex rounded-full bg-white px-5 py-3 text-sm font-bold text-slate-950 transition hover:bg-slate-100">
                                Open course video
                            </a>
                            <a href="{{ route('courses.download', $course) }}" class="inline-flex rounded-full border border-white/20 bg-white/10 px-5 py-3 text-sm font-bold text-white transition hover:bg-white/20">
                                Download outline
                            </a>
                        </div>
                    </div>
                </div>

                <div class="grid gap-6 lg:grid-cols-2">
                    <div class="panel">
                        <p class="kicker">What you have now</p>
                        <h4 class="mt-2 text-2xl font-extrabold tracking-tight text-slate-950">Private course access</h4>
                        <div class="mt-5 space-y-3 text-sm leading-7 text-slate-600">
                            <p>The learning page is only reachable for approved enrollments.</p>
                            <p>The video URL is shown here instead of the public course page.</p>
                            <p>A plain-text course outline is available for download at any time.</p>
                        </div>
                    </div>

                    <div class="panel">
                        <p class="kicker">Keep momentum</p>
                        <h4 class="mt-2 text-2xl font-extrabold tracking-tight text-slate-950">Next actions</h4>
                        <div class="mt-5 space-y-3">
                            <a href="{{ route('courses.my') }}" class="btn-primary">Back to library</a>
                            <a href="{{ route('courses.index') }}" class="btn-ghost">Browse another course</a>
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </div>
</x-app-layout>
