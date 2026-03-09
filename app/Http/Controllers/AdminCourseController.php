<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCourseRequest;
use App\Http\Requests\UpdateCourseRequest;
use App\Models\Course;
use Illuminate\Database\QueryException;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class AdminCourseController extends Controller
{
    public function index(): View
    {
        return view('admin.courses.index', [
            'courses' => Course::query()
                ->withCount(['payments', 'enrollments'])
                ->latest()
                ->paginate(10),
        ]);
    }

    public function create(): View
    {
        return view('admin.courses.create', [
            'course' => new Course(),
        ]);
    }

    public function store(StoreCourseRequest $request): RedirectResponse
    {
        Course::create($request->validated());

        return redirect()
            ->route('admin.courses.index')
            ->with('status', 'Course created successfully.');
    }

    public function edit(Course $course): View
    {
        return view('admin.courses.edit', [
            'course' => $course->load('documents'),
        ]);
    }

    public function update(UpdateCourseRequest $request, Course $course): RedirectResponse
    {
        $course->update($request->validated());

        return redirect()
            ->route('admin.courses.index')
            ->with('status', 'Course updated successfully.');
    }

    public function destroy(Course $course): RedirectResponse
    {
        if ($course->payments()->exists() || $course->enrollments()->exists()) {
            return redirect()
                ->route('admin.courses.index')
                ->with('error', 'This course cannot be deleted because it already has payments or enrollments.');
        }

        try {
            $course->delete();
        } catch (QueryException) {
            return redirect()
                ->route('admin.courses.index')
                ->with('error', 'This course could not be deleted because it is referenced elsewhere.');
        }

        return redirect()
            ->route('admin.courses.index')
            ->with('status', 'Course deleted successfully.');
    }
}
