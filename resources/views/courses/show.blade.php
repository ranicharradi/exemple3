<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-4 lg:flex-row lg:items-end lg:justify-between">
            <div class="max-w-3xl">
                <p class="kicker">Course details</p>
                <h2 class="mt-2 text-4xl font-extrabold tracking-tight text-slate-950">{{ $course->title }}</h2>
                <p class="mt-3 text-base leading-7 text-slate-600">
                    Review the course, then use the purchase button to move it into your panier. Payment upload happens on the dashboard, not here.
                </p>
            </div>

            <div class="rounded-[2rem] bg-slate-950 px-6 py-5 text-white shadow-[0_24px_60px_rgba(15,23,42,0.18)]">
                <p class="text-xs font-semibold uppercase tracking-[0.28em] text-slate-400">Price</p>
                <p class="mt-2 text-3xl font-extrabold">${{ number_format((float) $course->price, 2) }}</p>
            </div>
        </div>
    </x-slot>

    <div class="py-10">
        <div class="mx-auto grid max-w-7xl gap-6 px-4 sm:px-6 lg:grid-cols-[1.2fr_0.8fr] lg:px-8">
            <section class="panel space-y-8">
                <div class="grid gap-4 sm:grid-cols-3">
                    <div class="panel-muted">
                        <p class="text-xs font-semibold uppercase tracking-[0.28em] text-slate-400">Access</p>
                        <p class="mt-3 text-lg font-bold text-slate-950">Manual unlock</p>
                    </div>
                    <div class="panel-muted">
                        <p class="text-xs font-semibold uppercase tracking-[0.28em] text-slate-400">Format</p>
                        <p class="mt-3 text-lg font-bold text-slate-950">Video + downloadable outline</p>
                    </div>
                    <div class="panel-muted">
                        <p class="text-xs font-semibold uppercase tracking-[0.28em] text-slate-400">Payment</p>
                        <p class="mt-3 text-lg font-bold text-slate-950">Bank transfer proof</p>
                    </div>
                </div>

                <div>
                    <p class="kicker">About this course</p>
                    <p class="mt-4 whitespace-pre-line text-base leading-8 text-slate-600">{{ $course->description }}</p>
                </div>

                @auth
                    @if (! auth()->user()->isAdmin() && $hasAccess)
                        @php
                            $embedUrl = $course->video_url;
                            $host = parse_url($course->video_url, PHP_URL_HOST) ?? '';

                            if (str_contains($host, 'youtube.com')) {
                                parse_str(parse_url($course->video_url, PHP_URL_QUERY) ?? '', $query);
                                $videoId = $query['v'] ?? null;

                                if ($videoId) {
                                    $embedUrl = 'https://www.youtube.com/embed/'.$videoId;
                                }
                            } elseif (str_contains($host, 'youtu.be')) {
                                $path = trim((string) parse_url($course->video_url, PHP_URL_PATH), '/');

                                if ($path !== '') {
                                    $embedUrl = 'https://www.youtube.com/embed/'.$path;
                                }
                            } elseif (str_contains($host, 'vimeo.com')) {
                                $path = trim((string) parse_url($course->video_url, PHP_URL_PATH), '/');

                                if ($path !== '') {
                                    $embedUrl = 'https://player.vimeo.com/video/'.$path;
                                }
                            }
                        @endphp

                        <div class="space-y-5">
                            <p class="kicker">Contenu débloqué</p>

                            <div class="overflow-hidden rounded-[1.75rem] border border-slate-200">
                                <iframe
                                    src="{{ $embedUrl }}"
                                    title="Course video player"
                                    class="h-80 w-full lg:h-[28rem]"
                                    allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                                    allowfullscreen
                                ></iframe>
                            </div>

                            <div class="rounded-[1.75rem] border border-slate-200 bg-slate-50 p-5">
                                <h3 class="text-lg font-bold text-slate-950">Ressources PDF</h3>

                                @if ($course->documents->isEmpty())
                                    <p class="mt-3 text-sm text-slate-600">Aucun document n'est disponible pour ce cours pour le moment.</p>
                                @else
                                    <ul class="mt-4 space-y-3">
                                        @foreach ($course->documents as $document)
                                            <li class="flex flex-wrap items-center justify-between gap-3 rounded-xl border border-slate-200 bg-white px-4 py-3">
                                                <span class="text-sm font-medium text-slate-800">{{ $document->title }}</span>
                                                <a href="{{ route('courses.documents.download', [$course, $document]) }}" class="btn-ghost">
                                                    Télécharger
                                                </a>
                                            </li>
                                        @endforeach
                                    </ul>
                                @endif
                            </div>
                        </div>
                    @endif
                @endauth

                <div class="grid gap-4 lg:grid-cols-2">
                    <div class="rounded-[2rem] bg-gradient-to-br from-sky-600 via-blue-600 to-cyan-400 p-6 text-white">
                        <p class="text-xs font-semibold uppercase tracking-[0.28em] text-white/70">Purchase flow</p>
                        <div class="mt-4 space-y-3 text-sm leading-7 text-white/82">
                            <p>1. Add the course to your panier.</p>
                            <p>2. Transfer the exact amount using the generated reference.</p>
                            <p>3. Upload proof from the dashboard and wait for review.</p>
                        </div>
                    </div>
                    <div class="panel-muted">
                        <p class="text-xs font-semibold uppercase tracking-[0.28em] text-slate-400">After approval</p>
                        <div class="mt-4 space-y-3 text-sm leading-7 text-slate-600">
                            <p>The course appears in your learning library.</p>
                            <p>The video URL is only shown on the protected course page.</p>
                            <p>You can also download a plain-text course outline anytime.</p>
                        </div>
                    </div>
                </div>
            </section>

            <aside class="space-y-6">
                <div class="panel space-y-5">
                    <div>
                        <p class="kicker">Next action</p>
                        <h3 class="mt-2 text-2xl font-extrabold tracking-tight text-slate-950">Purchase this course</h3>
                    </div>

                    @guest
                        <p class="text-sm leading-7 text-slate-600">Sign in first so the panier and payment proof are attached to your student account.</p>
                        <div class="flex flex-wrap gap-3">
                            <a href="{{ route('login') }}" class="btn-primary">Login</a>
                            <a href="{{ route('register') }}" class="btn-ghost">Register</a>
                        </div>
                    @endguest

                    @auth
                        @if (auth()->user()->isAdmin())
                            <div class="rounded-[1.75rem] border border-amber-200 bg-amber-50 p-5 text-sm leading-7 text-amber-900">
                                Administrator accounts do not purchase courses. Use the admin workspace to review payments and manage content.
                            </div>
                        @elseif ($hasAccess)
                            <div class="rounded-[1.75rem] border border-emerald-200 bg-emerald-50 p-5">
                                <span class="status-pill status-pill-emerald">Unlocked</span>
                                <p class="mt-4 text-sm leading-7 text-emerald-900">This course is already approved on your account.</p>
                                <div class="mt-4 flex flex-wrap gap-3">
                                    <a href="{{ route('courses.watch', $course) }}" class="btn-success">Open course</a>
                                    <a href="{{ route('courses.download', $course) }}" class="btn-ghost">Download outline</a>
                                </div>
                            </div>
                        @elseif ($inCart)
                            <div class="rounded-[1.75rem] border border-sky-200 bg-sky-50 p-5">
                                <span class="status-pill status-pill-blue">In panier</span>
                                <p class="mt-4 text-sm leading-7 text-sky-900">This course is already waiting in your panier. Finish the transfer and upload the proof from your dashboard.</p>
                                <a href="{{ route('dashboard') }}#panier" class="mt-4 inline-flex btn-primary">Go to panier</a>
                            </div>
                        @elseif ($canAddToCart)
                            <form method="POST" action="{{ route('cart.store', $course) }}" class="space-y-4">
                                @csrf
                                <p class="text-sm leading-7 text-slate-600">The purchase button sends this course directly to your panier, where you will manage payment and upload the proof.</p>
                                <button type="submit" class="btn-primary">Purchase and open panier</button>
                            </form>
                        @endif

                        @if ($latestPayment)
                            @php
                                $paymentClasses = [
                                    'pending' => 'status-pill status-pill-amber',
                                    'approved' => 'status-pill status-pill-emerald',
                                    'rejected' => 'status-pill status-pill-rose',
                                ];
                            @endphp
                            <div class="rounded-[1.75rem] border border-slate-200 bg-slate-50 p-5">
                                <div class="flex items-start justify-between gap-4">
                                    <div>
                                        <p class="text-sm font-semibold uppercase tracking-[0.28em] text-slate-400">Latest payment</p>
                                        <p class="mt-3 text-sm text-slate-500">{{ $latestPayment->reference }}</p>
                                    </div>
                                    <span class="{{ $paymentClasses[$latestPayment->status] ?? 'status-pill status-pill-slate' }}">
                                        {{ $latestPayment->status }}
                                    </span>
                                </div>
                                <p class="mt-4 text-sm leading-7 text-slate-600">
                                    {{ $latestPayment->status === 'pending'
                                        ? 'Your proof is being reviewed. Keep tracking the decision from the panier section.'
                                        : ($latestPayment->status === 'rejected'
                                            ? 'The previous proof was rejected. Open the panier to submit a new one.'
                                            : 'Payment approved. This course is already unlocked in your library.') }}
                                </p>
                                <a href="{{ $latestPayment->status === 'approved' ? route('courses.watch', $course) : route('dashboard').'#panier' }}" class="mt-4 inline-flex btn-ghost">
                                    {{ $latestPayment->status === 'approved' ? 'Open course' : 'Open panier' }}
                                </a>
                            </div>
                        @endif
                    @endauth
                </div>
            </aside>
        </div>
    </div>
</x-app-layout>
