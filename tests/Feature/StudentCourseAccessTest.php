<?php

namespace Tests\Feature;

use App\Models\Course;
use App\Models\Enrollment;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class StudentCourseAccessTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_can_browse_courses_and_open_course_details(): void
    {
        $course = Course::factory()->create([
            'title' => 'Public Catalog Course',
        ]);

        $this->get(route('courses.index'))
            ->assertOk()
            ->assertSee('Public Catalog Course');

        $this->get(route('courses.show', $course))
            ->assertOk()
            ->assertSee('Purchase this course')
            ->assertSee('Payment upload happens on the dashboard');
    }

    public function test_enrolled_student_can_open_protected_course_page(): void
    {
        $student = User::factory()->create();
        $course = Course::factory()->create();

        Enrollment::create([
            'user_id' => $student->id,
            'course_id' => $course->id,
            'access_granted' => true,
        ]);

        $this->actingAs($student)
            ->get(route('courses.watch', $course))
            ->assertOk()
            ->assertSee('Protected learning space')
            ->assertSee('Open course video');
    }

    public function test_non_enrolled_student_cannot_open_protected_course_page(): void
    {
        $student = User::factory()->create();
        $course = Course::factory()->create();

        $this->actingAs($student)
            ->get(route('courses.watch', $course))
            ->assertForbidden();
    }
}
