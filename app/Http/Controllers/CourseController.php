<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Services\CourseService;
use Illuminate\Http\Request;
use Inertia\Inertia;

class CourseController extends Controller
{
    public function __construct(protected CourseService $courseService) {}

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $courses = Course::with('instructor')->paginate(12);
        return Inertia::render('Courses/Index', [
            'courses' => $courses
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $this->authorize('course.create');

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'short_description' => 'nullable|string',
            'price' => 'nullable|numeric',
            'is_free' => 'boolean',
        ]);

        $course = $this->courseService->createCourse($validated, auth()->user());

        return redirect()->route('courses.show', $course->id)
            ->with('success', 'Course created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Course $course)
    {
        $course->load(['instructor', 'sections.lessons.video']);
        $user = auth()->user();

        $data = [
            'course' => $course,
            'isEnrolled' => $user ? $user->enrollments()->where('course_id', $course->id)->exists() : false,
            'progress' => $user ? $user->lessonProgress()->whereIn('lesson_id', $course->lessons()->pluck('lessons.id'))->get() : [],
        ];

        // If instructor/editor, provide videos for selection
        if ($user && ($user->hasRole('instructor') || $user->hasRole('editor') || $user->hasRole('supervisor'))) {
            $data['availableVideos'] = \App\Models\Video::select('id', 'title')->get();
        }

        return Inertia::render('Courses/Show', $data);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Course $course)
    {
        $this->authorize('course.update', $course);

        $validated = $request->validate([
            'title' => 'string|max:255',
            'description' => 'string',
            'status' => 'string|in:draft,published,archived',
        ]);

        $this->courseService->updateCourse($course, $validated);

        return back()->with('success', 'Course updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Course $course)
    {
        $this->authorize('course.delete', $course);

        $this->courseService->deleteCourse($course);

        return redirect()->route('courses.index')
            ->with('success', 'Course deleted successfully.');
    }

    /**
     * Enroll in a course.
     */
    public function enroll(Course $course)
    {
        $this->authorize('course.enroll');

        $enrolled = $this->courseService->enrollStudent($course, auth()->user());

        if (!$enrolled) {
            return back()->with('error', 'You are already enrolled in this course.');
        }

        return back()->with('success', 'Successfully enrolled in course!');
    }
}
