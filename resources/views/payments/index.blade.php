<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-4 lg:flex-row lg:items-end lg:justify-between">
            <div>
                <p class="kicker">Payments</p>
                <h2 class="mt-2 text-4xl font-extrabold tracking-tight text-slate-950">Your payment history</h2>
                <p class="mt-3 max-w-2xl text-base leading-7 text-slate-600">
                    All submitted proofs stay here, but the actual upload and correction flow lives in the panier section of your dashboard.
                </p>
            </div>
            <a href="{{ route('dashboard') }}#panier" class="btn-primary">Open panier</a>
        </div>
    </x-slot>

    <div class="py-10">
        <div class="mx-auto max-w-7xl space-y-6 px-4 sm:px-6 lg:px-8">
            @forelse ($payments as $payment)
                @php
                    $statusClasses = [
                        'pending' => 'status-pill status-pill-amber',
                        'approved' => 'status-pill status-pill-emerald',
                        'rejected' => 'status-pill status-pill-rose',
                    ];
                @endphp
                <div class="panel">
                    <div class="flex flex-col gap-5 lg:flex-row lg:items-start lg:justify-between">
                        <div class="space-y-3">
                            <div class="flex flex-wrap items-center gap-3">
                                <span class="{{ $statusClasses[$payment->status] ?? 'status-pill status-pill-slate' }}">{{ $payment->status }}</span>
                                <span class="text-sm font-semibold text-slate-400">{{ $payment->created_at->format('M d, Y') }}</span>
                            </div>
                            <div>
                                <h3 class="text-2xl font-extrabold tracking-tight text-slate-950">{{ $payment->course->title }}</h3>
                                <p class="mt-2 text-sm text-slate-500">{{ $payment->reference }}</p>
                            </div>
                        </div>

                        <div class="grid gap-3 sm:grid-cols-2 lg:min-w-[280px]">
                            <div class="panel-muted">
                                <p class="text-xs font-semibold uppercase tracking-[0.28em] text-slate-400">Amount</p>
                                <p class="mt-3 text-2xl font-extrabold text-slate-950">${{ number_format((float) $payment->amount, 2) }}</p>
                            </div>
                            <div class="panel-muted">
                                <p class="text-xs font-semibold uppercase tracking-[0.28em] text-slate-400">Proof</p>
                                <a href="{{ route('payments.proof', $payment) }}" class="mt-3 inline-flex text-sm font-bold text-sky-700 hover:text-sky-900">Download proof</a>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="rounded-[2rem] border border-dashed border-slate-300 bg-white/70 p-10 text-sm text-slate-500">
                    No payments submitted yet.
                </div>
            @endforelse

            <div class="pt-2">
                {{ $payments->links() }}
            </div>
        </div>
    </div>
</x-app-layout>
