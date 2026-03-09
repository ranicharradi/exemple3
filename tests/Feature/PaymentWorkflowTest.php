<?php

namespace Tests\Feature;

use App\Models\CartItem;
use App\Models\Course;
use App\Models\Enrollment;
use App\Models\Payment;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class PaymentWorkflowTest extends TestCase
{
    use RefreshDatabase;

    public function test_student_can_submit_payment_proof_for_a_course(): void
    {
        Storage::fake('local');

        $student = User::factory()->create();
        $course = Course::factory()->create([
            'price' => 145.50,
        ]);

        $this->actingAs($student)
            ->post(route('cart.store', $course))
            ->assertRedirect(route('dashboard').'#panier');

        $cartItem = CartItem::firstOrFail();

        $this->actingAs($student)
            ->post(route('payments.store'), [
                'cart_item_id' => $cartItem->id,
                'proof' => UploadedFile::fake()->create('proof.pdf', 150, 'application/pdf'),
            ])
            ->assertRedirect(route('dashboard').'#panier');

        $payment = Payment::firstOrFail();

        $this->assertSame(Payment::STATUS_PENDING, $payment->status);
        $this->assertSame('145.50', $payment->amount);
        Storage::disk('local')->assertExists($payment->proof);
        $this->assertDatabaseMissing('cart_items', [
            'id' => $cartItem->id,
        ]);
    }

    public function test_student_cannot_submit_a_second_open_payment_for_the_same_course(): void
    {
        Storage::fake('local');

        $student = User::factory()->create();
        $course = Course::factory()->create();

        Payment::create([
            'user_id' => $student->id,
            'course_id' => $course->id,
            'amount' => $course->price,
            'reference' => 'CDX-EXISTING-REF',
            'proof' => 'payment-proofs/existing.pdf',
            'status' => Payment::STATUS_PENDING,
        ]);

        $cartItem = CartItem::factory()->create([
            'user_id' => $student->id,
            'course_id' => $course->id,
        ]);

        $this->actingAs($student)
            ->post(route('payments.store'), [
                'cart_item_id' => $cartItem->id,
                'proof' => UploadedFile::fake()->create('proof.pdf', 150, 'application/pdf'),
            ])
            ->assertRedirect(route('dashboard').'#panier');

        $this->assertSame(1, Payment::count());
    }

    public function test_admin_can_approve_payment_and_grant_enrollment(): void
    {
        $admin = User::factory()->admin()->create();
        $student = User::factory()->create();
        $course = Course::factory()->create();

        $payment = Payment::create([
            'user_id' => $student->id,
            'course_id' => $course->id,
            'amount' => $course->price,
            'reference' => 'CDX-APPROVE-REF',
            'proof' => 'payment-proofs/approve.pdf',
            'status' => Payment::STATUS_PENDING,
        ]);

        $this->actingAs($admin)
            ->patch(route('admin.payments.update', $payment), [
                'status' => Payment::STATUS_APPROVED,
            ])
            ->assertRedirect(route('admin.payments.index'));

        $this->assertDatabaseHas('payments', [
            'id' => $payment->id,
            'status' => Payment::STATUS_APPROVED,
        ]);

        $this->assertDatabaseHas('enrollments', [
            'user_id' => $student->id,
            'course_id' => $course->id,
            'access_granted' => true,
        ]);
    }

    public function test_payment_proof_download_is_authorized(): void
    {
        Storage::fake('local');

        $student = User::factory()->create();
        $otherStudent = User::factory()->create();
        $admin = User::factory()->admin()->create();
        $course = Course::factory()->create();

        Storage::disk('local')->put('payment-proofs/proof.pdf', 'proof');

        $payment = Payment::create([
            'user_id' => $student->id,
            'course_id' => $course->id,
            'amount' => $course->price,
            'reference' => 'CDX-PROOF-REF',
            'proof' => 'payment-proofs/proof.pdf',
            'status' => Payment::STATUS_PENDING,
        ]);

        $this->actingAs($student)
            ->get(route('payments.proof', $payment))
            ->assertOk();

        $this->actingAs($otherStudent)
            ->get(route('payments.proof', $payment))
            ->assertForbidden();

        $this->actingAs($admin)
            ->get(route('admin.payments.proof', $payment))
            ->assertOk();
    }

    public function test_student_cannot_pay_for_a_course_that_is_already_unlocked(): void
    {
        Storage::fake('local');

        $student = User::factory()->create();
        $course = Course::factory()->create();

        Enrollment::create([
            'user_id' => $student->id,
            'course_id' => $course->id,
            'access_granted' => true,
        ]);

        $cartItem = CartItem::factory()->create([
            'user_id' => $student->id,
            'course_id' => $course->id,
        ]);

        $this->actingAs($student)
            ->post(route('payments.store'), [
                'cart_item_id' => $cartItem->id,
                'proof' => UploadedFile::fake()->create('proof.pdf', 150, 'application/pdf'),
            ])
            ->assertForbidden();
    }
}
