<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-6 lg:flex-row lg:items-end lg:justify-between">
            <div class="max-w-2xl">
                <p class="kicker">Student dashboard</p>
                <h2 class="mt-2 text-4xl font-extrabold tracking-tight text-slate-950">
                    Good evening, {{ Str::of($user->name)->explode(' ')->first() }}
                </h2>
                <p class="mt-3 text-base leading-7 text-slate-600">
                    Keep the purchase flow in one place: browse, add to panier, upload the transfer proof, then continue learning once access is approved.
                </p>
            </div>

            <div class="flex flex-wrap gap-3">
                <a href="{{ route('courses.index') }}" class="btn-primary">Explore courses</a>
                <a href="#panier" class="btn-ghost">Open panier</a>
            </div>
        </div>
    </x-slot>

    <div class="py-10">
        <div class="mx-auto max-w-7xl space-y-8 px-4 sm:px-6 lg:px-8">
            <section class="grid gap-6 lg:grid-cols-[1.5fr_0.9fr]">
                <div class="panel overflow-hidden">
                    <div class="grid gap-8 lg:grid-cols-[1.15fr_0.85fr]">
                        <div class="space-y-5">
                            <div class="inline-flex rounded-full bg-sky-100 px-4 py-1 text-xs font-bold uppercase tracking-[0.28em] text-sky-800">
                                My learning
                            </div>

                            @if ($currentLearning)
                                <div class="space-y-4">
                                    <h3 class="text-3xl font-extrabold tracking-tight text-slate-950">{{ $currentLearning->course->title }}</h3>
                                    <p class="text-sm leading-7 text-slate-600">{{ Str::limit($currentLearning->course->description, 220) }}</p>
                                </div>

                                <div class="grid gap-3 sm:grid-cols-2">
                                    <div class="rounded-[1.5rem] bg-slate-950 p-5 text-white">
                                        <p class="text-xs font-semibold uppercase tracking-[0.28em] text-slate-400">Current status</p>
                                        <p class="mt-3 text-2xl font-bold">Unlocked</p>
                                        <p class="mt-2 text-sm text-slate-300">Your latest approved course is ready in the dedicated learning page.</p>
                                    </div>
                                    <div class="panel-muted">
                                        <p class="text-xs font-semibold uppercase tracking-[0.28em] text-slate-400">Next step</p>
                                        <p class="mt-3 text-lg font-bold text-slate-950">Resume and download your course resources.</p>
                                    </div>
                                </div>

                                <div class="flex flex-wrap gap-3">
                                    <a href="{{ route('courses.watch', $currentLearning->course) }}" class="btn-primary">Continue learning</a>
                                    <a href="{{ route('courses.download', $currentLearning->course) }}" class="btn-ghost">Download outline</a>
                                </div>
                            @else
                                <div class="space-y-4">
                                    <h3 class="text-3xl font-extrabold tracking-tight text-slate-950">Your next course starts from the panier.</h3>
                                    <p class="text-sm leading-7 text-slate-600">
                                        Add a course, transfer the exact amount, then upload the proof directly from your dashboard. Once the admin approves it, the course moves into your learning space automatically.
                                    </p>
                                </div>

                                <div class="grid gap-3 sm:grid-cols-3">
                                    <div class="panel-muted">
                                        <p class="text-xs font-semibold uppercase tracking-[0.28em] text-slate-400">1. Choose</p>
                                        <p class="mt-3 text-base font-bold text-slate-950">Browse the catalog</p>
                                    </div>
                                    <div class="panel-muted">
                                        <p class="text-xs font-semibold uppercase tracking-[0.28em] text-slate-400">2. Pay</p>
                                        <p class="mt-3 text-base font-bold text-slate-950">Use the panier payment flow</p>
                                    </div>
                                    <div class="panel-muted">
                                        <p class="text-xs font-semibold uppercase tracking-[0.28em] text-slate-400">3. Learn</p>
                                        <p class="mt-3 text-base font-bold text-slate-950">Unlock your course page</p>
                                    </div>
                                </div>
                            @endif
                        </div>

                        <div class="rounded-[2rem] bg-gradient-to-br from-sky-600 via-blue-600 to-cyan-400 p-6 text-white shadow-[0_28px_80px_rgba(37,99,235,0.28)]">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-xs font-semibold uppercase tracking-[0.28em] text-white/70">Panier</p>
                                    <h3 class="mt-3 text-3xl font-extrabold">{{ $user->cart_items_count }}</h3>
                                </div>
                                <div class="rounded-[1.5rem] bg-white/15 p-4">
                                    <svg class="h-8 w-8" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M3 3h2l.4 2m0 0L7 13h10l2-8H5.4zm1.6 0L6.25 3M9 19a1 1 0 100 2 1 1 0 000-2zm8 0a1 1 0 100 2 1 1 0 000-2z" />
                                    </svg>
                                </div>
                            </div>

                            <p class="mt-4 text-sm leading-6 text-white/82">
                                Manage all pending purchases here. The payment proof upload and the final review result both live in this section.
                            </p>

                            <div class="mt-6 space-y-3">
                                <div class="rounded-[1.5rem] bg-white/12 p-4">
                                    <p class="text-xs font-semibold uppercase tracking-[0.28em] text-white/70">Pending reviews</p>
                                    <p class="mt-2 text-2xl font-bold">{{ $user->pending_payments_count }}</p>
                                </div>
                                <div class="rounded-[1.5rem] bg-white/12 p-4">
                                    <p class="text-xs font-semibold uppercase tracking-[0.28em] text-white/70">Unlocked courses</p>
                                    <p class="mt-2 text-2xl font-bold">{{ $user->granted_enrollments_count }}</p>
                                </div>
                            </div>

                            <a href="#panier" class="mt-6 inline-flex rounded-full bg-white px-5 py-3 text-sm font-bold text-sky-700 transition hover:bg-sky-50">
                                Open panier
                            </a>
                        </div>
                    </div>
                </div>

                <div class="panel space-y-4">
                    <div>
                        <p class="kicker">Snapshot</p>
                        <h3 class="mt-2 text-2xl font-extrabold tracking-tight text-slate-950">Your account at a glance</h3>
                    </div>

                    <div class="grid gap-3 sm:grid-cols-3 lg:grid-cols-1">
                        <div class="panel-muted">
                            <p class="text-xs font-semibold uppercase tracking-[0.28em] text-slate-400">Payments</p>
                            <p class="mt-3 text-3xl font-extrabold text-slate-950">{{ $user->payments_count }}</p>
                            <p class="mt-2 text-sm text-slate-500">All submitted proofs.</p>
                        </div>
                        <div class="panel-muted">
                            <p class="text-xs font-semibold uppercase tracking-[0.28em] text-slate-400">Course library</p>
                            <p class="mt-3 text-3xl font-extrabold text-slate-950">{{ $courseLibrary->count() }}</p>
                            <p class="mt-2 text-sm text-slate-500">Locked, pending, and unlocked entries.</p>
                        </div>
                        <div class="panel-muted">
                            <p class="text-xs font-semibold uppercase tracking-[0.28em] text-slate-400">Role</p>
                            <p class="mt-3 text-3xl font-extrabold text-slate-950">Student</p>
                            <p class="mt-2 text-sm text-slate-500">Purchases and learning stay separate from admin tools.</p>
                        </div>
                    </div>
                </div>
            </section>

            <section id="panier" class="grid scroll-mt-24 gap-6 lg:grid-cols-[1.4fr_0.8fr]">
                <div class="panel space-y-6">
                    <div class="flex flex-col gap-3 sm:flex-row sm:items-end sm:justify-between">
                        <div>
                            <p class="kicker">Panier</p>
                            <h3 class="mt-2 text-3xl font-extrabold tracking-tight text-slate-950">Complete purchases from one page</h3>
                            <p class="mt-2 text-sm leading-6 text-slate-500">Each selected course gets one payment reference. Upload the proof here and track the review decision here.</p>
                        </div>
                        <a href="{{ route('courses.index') }}" class="btn-primary">Add more courses</a>
                    </div>

                    @forelse ($cartItems as $cartItem)
                        <div class="rounded-[2rem] border border-sky-100 bg-gradient-to-br from-white to-sky-50/80 p-6 shadow-sm">
                            <div class="flex flex-col gap-4 lg:flex-row lg:items-start lg:justify-between">
                                <div class="space-y-3">
                                    <div class="flex flex-wrap items-center gap-3">
                                        <span class="status-pill status-pill-blue">In panier</span>
                                        <span class="text-sm font-semibold text-slate-500">Reference: <span class="font-mono text-slate-700">{{ $cartItem->paymentReference() }}</span></span>
                                    </div>
                                    <div>
                                        <h4 class="text-2xl font-bold tracking-tight text-slate-950">{{ $cartItem->course->title }}</h4>
                                        <p class="mt-2 max-w-2xl text-sm leading-7 text-slate-600">{{ Str::limit($cartItem->course->description, 180) }}</p>
                                    </div>
                                </div>
                                <div class="rounded-[1.5rem] bg-slate-950 px-5 py-4 text-white">
                                    <p class="text-xs font-semibold uppercase tracking-[0.28em] text-slate-400">Amount</p>
                                    <p class="mt-2 text-2xl font-bold">${{ number_format((float) $cartItem->course->price, 2) }}</p>
                                </div>
                            </div>

                            <div class="mt-6 grid gap-4 lg:grid-cols-[0.95fr_1.05fr]">
                                <div class="panel-muted space-y-4">
                                    <div>
                                        <p class="text-xs font-semibold uppercase tracking-[0.28em] text-slate-400">Bank details</p>
                                        <div class="mt-4 space-y-3 text-sm text-slate-600">
                                            <p><span class="font-semibold text-slate-950">Account holder:</span> {{ config('codex.bank.account_holder') }}</p>
                                            <p><span class="font-semibold text-slate-950">Bank:</span> {{ config('codex.bank.bank_name') }}</p>
                                            <p class="break-all"><span class="font-semibold text-slate-950">IBAN:</span> {{ config('codex.bank.iban') }}</p>
                                        </div>
                                    </div>

                                    <div class="rounded-[1.25rem] bg-white p-4 text-sm text-slate-600">
                                        Transfer the exact amount, keep the generated reference, then upload a JPG, PNG, or PDF receipt.
                                    </div>
                                </div>

                                <div class="rounded-[1.75rem] border border-slate-200 bg-white p-5">
                                    <form method="POST" action="{{ route('payments.store') }}" enctype="multipart/form-data" class="space-y-4">
                                        @csrf
                                        <input type="hidden" name="cart_item_id" value="{{ $cartItem->id }}">

                                        <div>
                                            <label for="proof-{{ $cartItem->id }}" class="mb-2 block text-sm font-semibold text-slate-700">Upload proof of transfer</label>
                                            <input id="proof-{{ $cartItem->id }}" name="proof" type="file" accept=".jpg,.jpeg,.png,.pdf" class="block w-full rounded-2xl border border-slate-200 px-4 py-3 text-sm text-slate-700 shadow-sm focus:border-sky-400 focus:ring-sky-400">
                                            <x-input-error :messages="$errors->get('proof')" class="mt-2" />
                                        </div>

                                        <button type="submit" class="btn-primary">Submit payment proof</button>
                                    </form>

                                    <form method="POST" action="{{ route('cart.destroy', $cartItem) }}" class="mt-3">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn-danger" onclick="return confirm('Remove this course from your panier?')">Remove course</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="rounded-[2rem] border border-dashed border-slate-300 bg-white/70 p-10 text-center">
                            <div class="mx-auto max-w-xl space-y-3">
                                <h4 class="text-2xl font-bold tracking-tight text-slate-950">Your panier is empty.</h4>
                                <p class="text-sm leading-7 text-slate-500">Use the purchase button on a course page to push it here. The course detail page no longer asks for payment proof directly.</p>
                            </div>
                        </div>
                    @endforelse
                </div>

                <div class="panel space-y-5">
                    <div>
                        <p class="kicker">Payment decisions</p>
                        <h3 class="mt-2 text-2xl font-extrabold tracking-tight text-slate-950">Latest review results</h3>
                    </div>

                    @forelse ($latestPayments as $payment)
                        @php
                            $statusClasses = [
                                'pending' => 'status-pill status-pill-amber',
                                'approved' => 'status-pill status-pill-emerald',
                                'rejected' => 'status-pill status-pill-rose',
                            ];
                        @endphp
                        <div class="panel-muted">
                            <div class="flex items-start justify-between gap-4">
                                <div>
                                    <p class="text-base font-bold text-slate-950">{{ $payment->course->title }}</p>
                                    <p class="mt-1 text-sm text-slate-500">{{ $payment->reference }}</p>
                                </div>
                                <span class="{{ $statusClasses[$payment->status] ?? 'status-pill status-pill-slate' }}">
                                    {{ $payment->status }}
                                </span>
                            </div>
                            <p class="mt-3 text-sm text-slate-500">
                                {{ $payment->status === 'approved' ? 'Approved courses immediately appear in your course library.' : ($payment->status === 'rejected' ? 'Rejected proofs require a new transfer proof from your panier.' : 'Pending proofs are waiting for admin review.') }}
                            </p>
                            <div class="mt-4 flex flex-wrap gap-3">
                                <a href="{{ route('payments.proof', $payment) }}" class="btn-neutral">Download proof</a>
                                @if ($payment->status === 'approved')
                                    <a href="{{ route('courses.watch', $payment->course) }}" class="btn-success">Open course</a>
                                @endif
                            </div>
                        </div>
                    @empty
                        <div class="panel-muted text-sm leading-7 text-slate-500">
                            No payment decisions yet. Once you upload a proof, the review outcome will be shown here.
                        </div>
                    @endforelse
                </div>
            </section>

            <section class="panel space-y-6">
                <div class="flex flex-col gap-3 sm:flex-row sm:items-end sm:justify-between">
                    <div>
                        <p class="kicker">My courses</p>
                        <h3 class="mt-2 text-3xl font-extrabold tracking-tight text-slate-950">Your full course library</h3>
                    </div>
                    <a href="{{ route('courses.my') }}" class="btn-ghost">Open full library</a>
                </div>

                <div class="grid gap-4 md:grid-cols-2 xl:grid-cols-3">
                    @forelse ($courseLibrary as $entry)
                        @php
                            $entryClasses = match ($entry->status) {
                                'unlocked' => 'status-pill status-pill-emerald',
                                'pending' => 'status-pill status-pill-amber',
                                'rejected' => 'status-pill status-pill-rose',
                                'cart' => 'status-pill status-pill-blue',
                                default => 'status-pill status-pill-slate',
                            };
                        @endphp
                        <div class="rounded-[2rem] border border-slate-200 bg-white p-6 shadow-sm">
                            <div class="flex items-center justify-between gap-4">
                                <span class="{{ $entryClasses }}">{{ $entry->label }}</span>
                                <span class="text-sm font-semibold text-slate-400">{{ $entry->updated_at->diffForHumans() }}</span>
                            </div>
                            <h4 class="mt-4 text-xl font-bold tracking-tight text-slate-950">{{ $entry->course->title }}</h4>
                            <p class="mt-3 text-sm leading-7 text-slate-600">{{ Str::limit($entry->course->description, 135) }}</p>
                            <p class="mt-4 text-sm text-slate-500">{{ $entry->meta }}</p>
                            <a href="{{ $entry->action }}" class="mt-5 inline-flex text-sm font-bold text-sky-700 hover:text-sky-900">
                                {{ $entry->action_label }}
                            </a>
                        </div>
                    @empty
                        <div class="rounded-[2rem] border border-dashed border-slate-300 bg-white/70 p-8 text-sm text-slate-500 md:col-span-2 xl:col-span-3">
                            Your library is still empty. Add a course to the panier to get started.
                        </div>
                    @endforelse
                </div>
            </section>
        </div>
    </div>
</x-app-layout>
