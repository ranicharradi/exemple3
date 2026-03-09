<x-app-layout>
    <x-slot name="header">
        <div>
            <p class="kicker">Admin CRUD</p>
            <h2 class="mt-2 text-4xl font-extrabold tracking-tight text-slate-950">Create course</h2>
        </div>
    </x-slot>

    <div class="py-10">
        <div class="mx-auto grid max-w-6xl gap-6 px-4 sm:px-6 lg:grid-cols-[0.72fr_0.28fr] lg:px-8">
            <div class="panel border-amber-200 bg-gradient-to-br from-white to-amber-50">
                <form method="POST" action="{{ route('admin.courses.store') }}" class="space-y-6">
                    @csrf
                    @include('admin.courses.partials.form', ['course' => $course, 'submitLabel' => 'Create course'])
                </form>
            </div>

            <aside class="panel space-y-4">
                <p class="kicker">Publishing notes</p>
                <div class="space-y-3 text-sm leading-7 text-slate-600">
                    <p>Use a clear title and a practical description.</p>
                    <p>The video URL is only shown after the payment is approved.</p>
                    <p>Deletion is blocked once payments or enrollments exist.</p>
                </div>
            </aside>
        </div>
    </div>
</x-app-layout>
