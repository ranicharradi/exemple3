<?php

namespace App\Http\Controllers;

use App\Models\CartItem;
use App\Models\Course;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function store(Request $request, Course $course): RedirectResponse
    {
        $user = $request->user();

        abort_if($user->isAdmin(), 403);
        abort_if($course->hasAccessFor($user), 403);

        if ($course->hasOpenPaymentFor($user)) {
            return redirect()
                ->to(route('dashboard').'#panier')
                ->with('error', 'This course already has a pending or approved payment.');
        }

        CartItem::firstOrCreate([
            'user_id' => $user->id,
            'course_id' => $course->id,
        ]);

        return redirect()
            ->to(route('dashboard').'#panier')
            ->with('status', 'Course added to your panier.');
    }

    public function destroy(Request $request, CartItem $cartItem): RedirectResponse
    {
        abort_unless($cartItem->user_id === $request->user()->id, 403);

        $cartItem->delete();

        return redirect()
            ->to(route('dashboard').'#panier')
            ->with('status', 'Course removed from your panier.');
    }
}
