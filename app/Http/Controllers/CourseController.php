<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Enrollment;
use App\Models\Payment;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Response;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

class CourseController extends Controller
{
    public function index(): View
    {
        return view('courses.index', [
            'courses' => Course::query()->latest()->paginate(9),
        ]);
    }

    public function show(Request $request, Course $course): View
    {
        $course->load('documents');

        $latestPayment = null;
        $inCart = false;
        $canAddToCart = false;

        if ($request->user() && ! $request->user()->isAdmin()) {
            $latestPayment = $course->payments()
                ->where('user_id', $request->user()->id)
                ->latest()
                ->first();

            $inCart = $course->isInCartFor($request->user());
            $canAddToCart = ! $course->hasAccessFor($request->user())
                && ! $inCart
                && ! $course->hasOpenPaymentFor($request->user());
        }

        return view('courses.show', [
            'bankAccount' => config('codex.bank'),
            'canAddToCart' => $canAddToCart,
            'course' => $course,
            'hasAccess' => $course->hasAccessFor($request->user()),
            'inCart' => $inCart,
            'latestPayment' => $latestPayment,
        ]);
    }

    public function dashboard(Request $request): RedirectResponse|View
    {
        if ($request->user()->isAdmin()) {
            return redirect()->route('admin.dashboard');
        }

        $user = $request->user()->loadCount([
            'payments',
            'payments as pending_payments_count' => fn ($query) => $query->where('status', Payment::STATUS_PENDING),
            'enrollments as granted_enrollments_count' => fn ($query) => $query->where('access_granted', true),
            'cartItems',
        ]);

        return view('dashboard', [
            'courseLibrary' => $this->buildCourseLibrary($user),
            'currentLearning' => $user->enrollments()
                ->where('access_granted', true)
                ->with('course')
                ->latest()
                ->first(),
            'cartItems' => $user->cartItems()->with('course')->latest()->get(),
            'latestPayments' => $user->payments()->with('course')->latest()->take(5)->get(),
            'user' => $user,
        ]);
    }

    public function myCourses(Request $request): View
    {
        return view('courses.my-courses', [
            'entries' => $this->buildCourseLibrary($request->user()),
        ]);
    }

    public function watch(Request $request, Course $course): View
    {
        abort_unless($course->hasAccessFor($request->user()), 403);

        return view('courses.watch', [
            'course' => $course,
        ]);
    }

    public function download(Request $request, Course $course): StreamedResponse
    {
        abort_unless($course->hasAccessFor($request->user()), 403);

        $content = implode(PHP_EOL, [
            $course->title,
            str_repeat('=', strlen($course->title)),
            '',
            'Course summary',
            $course->description,
            '',
            'Video URL',
            $course->video_url,
        ]);

        return Response::streamDownload(
            static function () use ($content): void {
                echo $content;
            },
            str()->slug($course->title).'-outline.txt',
            ['Content-Type' => 'text/plain; charset=UTF-8'],
        );
    }

    private function buildCourseLibrary(User $user): Collection
    {
        $entries = collect();

        $user->enrollments()
            ->with('course')
            ->latest()
            ->get()
            ->each(function (Enrollment $enrollment) use (&$entries): void {
                $entries[$enrollment->course_id] = (object) [
                    'course' => $enrollment->course,
                    'status' => $enrollment->access_granted ? 'unlocked' : 'locked',
                    'label' => $enrollment->access_granted ? 'Unlocked' : 'Locked',
                    'action' => $enrollment->access_granted ? route('courses.watch', $enrollment->course) : route('dashboard').'#panier',
                    'action_label' => $enrollment->access_granted ? 'Continue learning' : 'Open panier',
                    'meta' => $enrollment->access_granted ? 'Course access granted.' : 'Course access is still locked.',
                    'updated_at' => $enrollment->updated_at,
                ];
            });

        $user->payments()
            ->with('course')
            ->latest()
            ->get()
            ->unique('course_id')
            ->each(function (Payment $payment) use (&$entries): void {
                if (isset($entries[$payment->course_id])) {
                    return;
                }

                $map = [
                    Payment::STATUS_PENDING => ['Pending review', 'Awaiting admin validation.', route('dashboard').'#panier', 'View panier'],
                    Payment::STATUS_REJECTED => ['Rejected', 'Update the payment proof from your panier.', route('dashboard').'#panier', 'Fix in panier'],
                    Payment::STATUS_APPROVED => ['Unlocked', 'Course access granted.', route('courses.watch', $payment->course), 'Continue learning'],
                ];

                [$label, $meta, $action, $actionLabel] = $map[$payment->status];

                $entries[$payment->course_id] = (object) [
                    'course' => $payment->course,
                    'status' => $payment->status === Payment::STATUS_APPROVED ? 'unlocked' : $payment->status,
                    'label' => $label,
                    'action' => $action,
                    'action_label' => $actionLabel,
                    'meta' => $meta,
                    'updated_at' => $payment->updated_at,
                ];
            });

        $user->cartItems()
            ->with('course')
            ->latest()
            ->get()
            ->each(function (\App\Models\CartItem $cartItem) use (&$entries): void {
                if (isset($entries[$cartItem->course_id])) {
                    return;
                }

                $entries[$cartItem->course_id] = (object) [
                    'course' => $cartItem->course,
                    'status' => 'cart',
                    'label' => 'In panier',
                    'action' => route('dashboard').'#panier',
                    'action_label' => 'Complete payment',
                    'meta' => 'Ready for bank transfer proof upload.',
                    'updated_at' => $cartItem->updated_at,
                ];
            });

        return $entries->sortByDesc('updated_at')->values();
    }
}
