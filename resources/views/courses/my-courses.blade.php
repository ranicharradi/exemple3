<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-4 lg:flex-row lg:items-end lg:justify-between">
            <div>
                <p class="kicker">My courses</p>
                <h2 class="mt-2 text-4xl font-extrabold tracking-tight text-slate-950">Your learning library</h2>
                <p class="mt-3 max-w-2xl text-base leading-7 text-slate-600">
                    Approved courses are ready to open. Locked, pending, rejected, and panier entries still stay visible here so you never lose track of what comes next.
                </p>
            </div>
            <a href="{{ route('dashboard') }}#panier" class="btn-primary">Open panier</a>
        </div>
    </x-slot>

    <div class="py-10">
        <div class="mx-auto max-w-7xl space-y-8 px-4 sm:px-6 lg:px-8">
            <div class="grid gap-6 md:grid-cols-2 xl:grid-cols-3">
                @forelse ($entries as $entry)
                    @php
                        $entryClasses = match ($entry->status) {
                            'unlocked' => 'status-pill status-pill-emerald',
                            'pending' => 'status-pill status-pill-amber',
                            'rejected' => 'status-pill status-pill-rose',
                            'cart' => 'status-pill status-pill-blue',
                            default => 'status-pill status-pill-slate',
                        };
                    @endphp
                    <div class="panel flex h-full flex-col">
                        <div class="flex items-center justify-between gap-4">
                            <span class="{{ $entryClasses }}">{{ $entry->label }}</span>
                            <span class="text-sm font-semibold text-slate-400">{{ $entry->updated_at->diffForHumans() }}</span>
                        </div>

                        <h3 class="mt-5 text-2xl font-extrabold tracking-tight text-slate-950">{{ $entry->course->title }}</h3>
                        <p class="mt-4 flex-1 text-sm leading-7 text-slate-600">{{ Str::limit($entry->course->description, 170) }}</p>

                        <div class="mt-6 rounded-[1.5rem] bg-slate-50 p-4">
                            <p class="text-sm leading-7 text-slate-600">{{ $entry->meta }}</p>
                        </div>

                        <div class="mt-6 flex flex-wrap gap-3">
                            <a href="{{ $entry->action }}" class="{{ $entry->status === 'unlocked' ? 'btn-primary' : 'btn-ghost' }}">
                                {{ $entry->action_label }}
                            </a>
                            @if ($entry->status === 'unlocked')
                                <a href="{{ route('courses.download', $entry->course) }}" class="btn-neutral">Download</a>
                            @endif
                        </div>
                    </div>
                @empty
                    <div class="rounded-[2rem] border border-dashed border-slate-300 bg-white/70 p-10 text-center text-sm text-slate-500 md:col-span-2 xl:col-span-3">
                        You do not have any course entries yet. Browse the catalog and add your first course to the panier.
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</x-app-layout>
