<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdatePaymentStatusRequest;
use App\Models\Course;
use App\Models\Enrollment;
use App\Models\Payment;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

class AdminController extends Controller
{
    public function dashboard(): View
    {
        return view('admin.dashboard', [
            'courseCount' => Course::count(),
            'pendingPaymentsCount' => Payment::query()->where('status', Payment::STATUS_PENDING)->count(),
            'studentCount' => User::query()->where('role', User::ROLE_STUDENT)->count(),
        ]);
    }

    public function payments(Request $request): View
    {
        $status = $request->string('status')->toString();
        $status = in_array($status, Payment::statuses(), true) ? $status : null;

        return view('admin.payments.index', [
            'payments' => Payment::query()
                ->with(['course', 'user'])
                ->when($status, fn ($query) => $query->where('status', $status))
                ->latest()
                ->paginate(12)
                ->withQueryString(),
            'statusFilter' => $status,
        ]);
    }

    public function updatePaymentStatus(UpdatePaymentStatusRequest $request, Payment $payment): RedirectResponse
    {
        if (! $payment->isPending()) {
            return redirect()
                ->route('admin.payments.index')
                ->with('error', 'Only pending payments can be reviewed.');
        }

        DB::transaction(function () use ($payment, $request): void {
            $status = $request->string('status')->toString();

            $payment->update([
                'status' => $status,
                'reviewed_at' => now(),
            ]);

            if ($status === Payment::STATUS_APPROVED) {
                Enrollment::updateOrCreate([
                    'user_id' => $payment->user_id,
                    'course_id' => $payment->course_id,
                ], [
                    'access_granted' => true,
                ]);
            }
        });

        return redirect()
            ->route('admin.payments.index')
            ->with('status', 'Payment status updated successfully.');
    }

    public function showPaymentProof(Payment $payment): StreamedResponse
    {
        abort_unless($payment->proofExists(), 404);

        return Storage::disk('local')->download($payment->proof, $payment->proofFilename());
    }

    public function students(): View
    {
        return view('admin.students.index', [
            'students' => User::query()
                ->where('role', User::ROLE_STUDENT)
                ->withCount([
                    'payments',
                    'enrollments as active_enrollments_count' => fn ($query) => $query->where('access_granted', true),
                ])
                ->latest()
                ->paginate(12),
        ]);
    }
}
