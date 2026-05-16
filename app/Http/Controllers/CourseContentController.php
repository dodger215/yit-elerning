<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\CourseSection;
use App\Services\CourseService;
use Illuminate\Http\Request;

class CourseContentController extends Controller
{
    public function __construct(protected CourseService $courseService) {}

    /**
     * Add a section to a course.
     */
    public function addSection(Request $request, Course $course)
    {
        $this->authorize('course.manage', $course);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        $this->courseService->addSection($course, $validated);

        return back()->with('success', 'Section added successfully.');
    }

    /**
     * Add a lesson to a section.
     */
    public function addLesson(Request $request, CourseSection $section)
    {
        $this->authorize('course.manage', $section->course);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'video_id' => 'nullable|exists:videos,id',
            'is_preview' => 'boolean',
        ]);

        $this->courseService->addLesson($section, $validated);

        return back()->with('success', 'Lesson added successfully.');
    }
}
