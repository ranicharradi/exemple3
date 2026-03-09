<?php

namespace Tests\Feature;

use App\Models\Course;
use App\Models\Payment;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminCourseManagementTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_create_update_and_delete_a_course(): void
    {
        $admin = User::factory()->admin()->create();

        $this->actingAs($admin)
            ->post(route('admin.courses.store'), [
                'title' => 'Advanced Laravel Patterns',
                'description' => 'A practical course for shipping maintainable Laravel code.',
                'price' => '199.00',
                'video_url' => 'https://example.com/videos/advanced-laravel-patterns',
            ])
            ->assertRedirect(route('admin.courses.index'));

        $course = Course::firstOrFail();

        $this->actingAs($admin)
            ->put(route('admin.courses.update', $course), [
                'title' => 'Advanced Laravel Patterns 2',
                'description' => 'Updated description',
                'price' => '249.00',
                'video_url' => 'https://example.com/videos/advanced-laravel-patterns-2',
            ])
            ->assertRedirect(route('admin.courses.index'));

        $this->assertDatabaseHas('courses', [
            'id' => $course->id,
            'title' => 'Advanced Laravel Patterns 2',
            'price' => '249.00',
        ]);

        $this->actingAs($admin)
            ->delete(route('admin.courses.destroy', $course))
            ->assertRedirect(route('admin.courses.index'));

        $this->assertDatabaseMissing('courses', [
            'id' => $course->id,
        ]);
    }

    public function test_student_cannot_access_admin_course_management(): void
    {
        $student = User::factory()->create();

        $this->actingAs($student)
            ->get(route('admin.courses.index'))
            ->assertForbidden();
    }

    public function test_course_with_payments_cannot_be_deleted(): void
    {
        $admin = User::factory()->admin()->create();
        $student = User::factory()->create();
        $course = Course::factory()->create();

        Payment::create([
            'user_id' => $student->id,
            'course_id' => $course->id,
            'amount' => $course->price,
            'reference' => 'CDX-LOCKED-COURSE',
            'proof' => 'payment-proofs/locked-course.pdf',
            'status' => Payment::STATUS_PENDING,
        ]);

        $this->actingAs($admin)
            ->delete(route('admin.courses.destroy', $course))
            ->assertRedirect(route('admin.courses.index'));

        $this->assertDatabaseHas('courses', [
            'id' => $course->id,
        ]);
    }
}
