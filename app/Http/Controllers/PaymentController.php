<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePaymentRequest;
use App\Models\CartItem;
use App\Models\Course;
use App\Models\Payment;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Throwable;

class PaymentController extends Controller
{
    public function index(Request $request): View
    {
        return view('payments.index', [
            'payments' => Payment::query()
                ->with('course')
                ->where('user_id', $request->user()->id)
                ->latest()
                ->paginate(10),
        ]);
    }

    public function store(StorePaymentRequest $request): RedirectResponse
    {
        $cartItem = CartItem::query()
            ->with('course')
            ->where('user_id', $request->user()->id)
            ->findOrFail($request->integer('cart_item_id'));

        $course = $cartItem->course;
        $user = $request->user();

        abort_if($course->hasAccessFor($user), 403);

        if ($course->hasOpenPaymentFor($user)) {
            return redirect()
                ->to(route('dashboard').'#panier')
                ->with('error', 'A pending or approved payment already exists for this course.');
        }

        $proofPath = $request->file('proof')->store('payment-proofs', 'local');

        try {
            DB::transaction(function () use ($cartItem, $course, $proofPath, $user): void {
                Payment::create([
                    'user_id' => $user->id,
                    'course_id' => $course->id,
                    'amount' => $course->price,
                    'reference' => $cartItem->paymentReference(),
                    'proof' => $proofPath,
                    'status' => Payment::STATUS_PENDING,
                ]);

                $cartItem->delete();
            });
        } catch (Throwable $exception) {
            Storage::disk('local')->delete($proofPath);

            throw $exception;
        }

        return redirect()
            ->to(route('dashboard').'#panier')
            ->with('status', 'Payment proof uploaded successfully. An administrator will review it.');
    }

    public function showProof(Request $request, Payment $payment): StreamedResponse
    {
        abort_unless($payment->user_id === $request->user()->id, 403);
        abort_unless($payment->proofExists(), 404);

        return Storage::disk('local')->download($payment->proof, $payment->proofFilename());
    }
}
