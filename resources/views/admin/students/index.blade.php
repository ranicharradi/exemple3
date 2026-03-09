<x-app-layout>
    <x-slot name="header">
        <div>
            <p class="kicker">Student visibility</p>
            <h2 class="mt-2 text-4xl font-extrabold tracking-tight text-slate-950">Students</h2>
            <p class="mt-3 max-w-2xl text-base leading-7 text-slate-600">
                Monitor who joined, how much payment activity they have, and how many active enrollments are currently unlocked.
            </p>
        </div>
    </x-slot>

    <div class="py-10">
        <div class="mx-auto max-w-7xl space-y-6 px-4 sm:px-6 lg:px-8">
            <div class="grid gap-6 md:grid-cols-2 xl:grid-cols-3">
                @forelse ($students as $student)
                    <div class="panel border-yellow-200 bg-gradient-to-br from-white to-yellow-50">
                        <p class="kicker">Student</p>
                        <h3 class="mt-3 text-2xl font-extrabold tracking-tight text-slate-950">{{ $student->name }}</h3>
                        <p class="mt-2 text-sm text-slate-500">{{ $student->email }}</p>

                        <div class="mt-6 grid gap-3 sm:grid-cols-2">
                            <div class="panel-muted">
                                <p class="text-xs font-semibold uppercase tracking-[0.28em] text-slate-400">Payments</p>
                                <p class="mt-3 text-2xl font-extrabold text-slate-950">{{ $student->payments_count }}</p>
                            </div>
                            <div class="panel-muted">
                                <p class="text-xs font-semibold uppercase tracking-[0.28em] text-slate-400">Unlocked</p>
                                <p class="mt-3 text-2xl font-extrabold text-slate-950">{{ $student->active_enrollments_count }}</p>
                            </div>
                        </div>

                        <div class="mt-6 rounded-[1.5rem] bg-white p-4 text-sm text-slate-600">
                            Joined {{ $student->created_at->format('M d, Y') }}
                        </div>
                    </div>
                @empty
                    <div class="rounded-[2rem] border border-dashed border-yellow-300 bg-white/70 p-10 text-sm text-slate-500 md:col-span-2 xl:col-span-3">
                        No students found.
                    </div>
                @endforelse
            </div>

            <div class="pt-2">
                {{ $students->links() }}
            </div>
        </div>
    </div>
</x-app-layout>
