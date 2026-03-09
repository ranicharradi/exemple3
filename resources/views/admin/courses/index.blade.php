<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-5 lg:flex-row lg:items-end lg:justify-between">
            <div>
                <p class="kicker">Admin CRUD</p>
                <h2 class="mt-2 text-4xl font-extrabold tracking-tight text-slate-950">Manage courses</h2>
                <p class="mt-3 max-w-2xl text-base leading-7 text-slate-600">
                    Every course card exposes the admin actions directly, so creating, editing, and deleting are visible without hunting through a plain table.
                </p>
            </div>
            <a href="{{ route('admin.courses.create') }}" class="btn-admin-entry">Add course</a>
        </div>
    </x-slot>

    <div class="py-10">
        <div class="mx-auto max-w-7xl space-y-6 px-4 sm:px-6 lg:px-8">
            <div class="grid gap-6 md:grid-cols-2 xl:grid-cols-3">
                @forelse ($courses as $course)
                    <div class="panel flex h-full flex-col border-amber-200 bg-gradient-to-br from-white to-amber-50">
                        <div class="flex items-start justify-between gap-4">
                            <div>
                                <p class="kicker">Catalog entry</p>
                                <h3 class="mt-3 text-2xl font-extrabold tracking-tight text-slate-950">{{ $course->title }}</h3>
                            </div>
                            <span class="rounded-full bg-slate-950 px-4 py-2 text-sm font-bold text-white">
                                ${{ number_format((float) $course->price, 2) }}
                            </span>
                        </div>

                        <p class="mt-5 flex-1 text-sm leading-7 text-slate-600">{{ Str::limit($course->description, 180) }}</p>

                        <div class="mt-6 grid gap-3 sm:grid-cols-2">
                            <div class="panel-muted">
                                <p class="text-xs font-semibold uppercase tracking-[0.28em] text-slate-400">Payments</p>
                                <p class="mt-3 text-2xl font-extrabold text-slate-950">{{ $course->payments_count }}</p>
                            </div>
                            <div class="panel-muted">
                                <p class="text-xs font-semibold uppercase tracking-[0.28em] text-slate-400">Enrollments</p>
                                <p class="mt-3 text-2xl font-extrabold text-slate-950">{{ $course->enrollments_count }}</p>
                            </div>
                        </div>

                        <div class="mt-6 flex flex-wrap gap-3">
                            <a href="{{ route('admin.courses.edit', $course) }}" class="btn-admin-entry">Edit course</a>
                            <form method="POST" action="{{ route('admin.courses.destroy', $course) }}">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn-danger" onclick="return confirm('Delete this course?')">Delete</button>
                            </form>
                        </div>
                    </div>
                @empty
                    <div class="rounded-[2rem] border border-dashed border-amber-300 bg-white/70 p-10 text-sm text-slate-500 md:col-span-2 xl:col-span-3">
                        No courses created yet.
                    </div>
                @endforelse
            </div>

            <div class="pt-2">
                {{ $courses->links() }}
            </div>
        </div>
    </div>
</x-app-layout>
