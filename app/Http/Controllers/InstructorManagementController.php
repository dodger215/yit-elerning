<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Inertia;

class InstructorManagementController extends Controller
{
    /**
     * List all instructors and their course stats for the supervisor.
     */
    public function index()
    {
        $this->authorize('user.manage');

        $instructors = User::whereHas('roles', function ($query) {
            $query->where('name', 'instructor');
        })
            ->withCount(['courses', 'hostedMeetings'])
            ->with(['courses' => function ($query) {
                $query->latest()->limit(5);
            }])
            ->paginate(15);

        return Inertia::render('Admin/Instructors/Index', [
            'instructors' => $instructors,
        ]);
    }

    /**
     * View detailed performance of a specific instructor.
     */
    public function show(User $instructor)
    {
        $this->authorize('user.manage');

        if (! $instructor->hasRole('instructor')) {
            abort(404);
        }

        $instructor->loadCount(['courses', 'hostedMeetings', 'enrollments']);
        $courses = Course::where('instructor_id', $instructor->id)
            ->withCount('enrollments')
            ->paginate(10);

        return Inertia::render('Admin/Instructors/Show', [
            'instructor' => $instructor,
            'courses' => $courses,
        ]);
    }

    /**
     * Manage course status (Approve/Reject) as a supervisor.
     */
    public function updateCourseStatus(Request $request, Course $course)
    {
        $this->authorize('course.manage');

        $request->validate([
            'status' => 'required|in:published,draft,archived',
        ]);

        $course->update(['status' => $request->status]);

        return back()->with('success', "Course status updated to {$request->status}.");
    }
}
