<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-5 lg:flex-row lg:items-end lg:justify-between">
            <div>
                <p class="kicker">Payment actions</p>
                <h2 class="mt-2 text-4xl font-extrabold tracking-tight text-slate-950">Review transfer proofs</h2>
                <p class="mt-3 max-w-2xl text-base leading-7 text-slate-600">
                    This board makes the admin actions explicit: download proof, approve and enroll, or reject and send the student back to the panier flow.
                </p>
            </div>
            <div class="flex flex-wrap gap-2 text-sm">
                <a href="{{ route('admin.payments.index') }}" class="{{ $statusFilter === null ? 'btn-admin-entry' : 'btn-ghost' }}">All</a>
                <a href="{{ route('admin.payments.index', ['status' => 'pending']) }}" class="{{ $statusFilter === 'pending' ? 'btn-admin-entry' : 'btn-ghost' }}">Pending</a>
                <a href="{{ route('admin.payments.index', ['status' => 'approved']) }}" class="{{ $statusFilter === 'approved' ? 'btn-admin-entry' : 'btn-ghost' }}">Approved</a>
                <a href="{{ route('admin.payments.index', ['status' => 'rejected']) }}" class="{{ $statusFilter === 'rejected' ? 'btn-admin-entry' : 'btn-ghost' }}">Rejected</a>
            </div>
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
                <div class="panel border-amber-200 bg-gradient-to-br from-white to-amber-50">
                    <div class="flex flex-col gap-6 lg:flex-row lg:items-start lg:justify-between">
                        <div class="space-y-4">
                            <div class="flex flex-wrap items-center gap-3">
                                <span class="{{ $statusClasses[$payment->status] ?? 'status-pill status-pill-slate' }}">{{ $payment->status }}</span>
                                <span class="text-sm font-semibold text-slate-400">{{ $payment->created_at->format('M d, Y H:i') }}</span>
                            </div>

                            <div>
                                <h3 class="text-2xl font-extrabold tracking-tight text-slate-950">{{ $payment->course->title }}</h3>
                                <p class="mt-2 text-sm text-slate-500">{{ $payment->user->name }} · {{ $payment->user->email }}</p>
                            </div>

                            <div class="grid gap-3 sm:grid-cols-2">
                                <div class="panel-muted">
                                    <p class="text-xs font-semibold uppercase tracking-[0.28em] text-slate-400">Reference</p>
                                    <p class="mt-3 break-all font-mono text-sm text-slate-700">{{ $payment->reference }}</p>
                                </div>
                                <div class="panel-muted">
                                    <p class="text-xs font-semibold uppercase tracking-[0.28em] text-slate-400">Amount</p>
                                    <p class="mt-3 text-2xl font-extrabold text-slate-950">${{ number_format((float) $payment->amount, 2) }}</p>
                                </div>
                            </div>
                        </div>

                        <div class="w-full max-w-sm space-y-3">
                            <a href="{{ route('admin.payments.proof', $payment) }}" class="btn-neutral w-full">Download proof</a>

                            @if ($payment->status === 'pending')
                                <form method="POST" action="{{ route('admin.payments.update', $payment) }}">
                                    @csrf
                                    @method('PATCH')
                                    <input type="hidden" name="status" value="approved">
                                    <button type="submit" class="btn-success w-full">Approve and enroll</button>
                                </form>

                                <form method="POST" action="{{ route('admin.payments.update', $payment) }}">
                                    @csrf
                                    @method('PATCH')
                                    <input type="hidden" name="status" value="rejected">
                                    <button type="submit" class="btn-danger w-full">Reject payment</button>
                                </form>
                            @endif
                        </div>
                    </div>
                </div>
            @empty
                <div class="rounded-[2rem] border border-dashed border-amber-300 bg-white/70 p-10 text-sm text-slate-500">
                    No payments match the current filter.
                </div>
            @endforelse

            <div class="pt-2">
                {{ $payments->links() }}
            </div>
        </div>
    </div>
</x-app-layout>
