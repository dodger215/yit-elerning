<?php

namespace App\Services;

use App\Models\Course;
use App\Models\CourseEnrollment;
use App\Models\Meeting;
use App\Models\User;
use App\Models\Video;
use Illuminate\Support\Facades\DB;

class ReportService
{
    /**
     * Generate platform-wide reports for Supervisors.
     */
    public function getSupervisorReport(): array
    {
        return [
            'overview' => [
                'total_users' => User::count(),
                'total_courses' => Course::count(),
                'total_enrollments' => CourseEnrollment::count(),
                'active_meetings' => Meeting::where('status', 'active')->count(),
            ],
            'user_growth' => User::select(DB::raw('DATE(created_at) as date'), DB::raw('count(*) as count'))
                ->groupBy('date')
                ->orderBy('date', 'desc')
                ->limit(30)
                ->get(),
            'popular_courses' => Course::withCount('enrollments')
                ->orderBy('enrollments_count', 'desc')
                ->limit(5)
                ->get(),
        ];
    }

    /**
     * Generate course performance reports for Instructors.
     */
    public function getInstructorReport(User $instructor): array
    {
        $courseIds = Course::where('instructor_id', $instructor->id)->pluck('id');

        return [
            'my_stats' => [
                'total_courses' => $courseIds->count(),
                'total_students' => CourseEnrollment::whereIn('course_id', $courseIds)->count(),
                'total_meetings' => Meeting::where('host_user_id', $instructor->id)->count(),
            ],
            'course_performance' => Course::where('instructor_id', $instructor->id)
                ->withCount('enrollments')
                ->get()
                ->map(fn ($course) => [
                    'title' => $course->title,
                    'students' => $course->enrollments_count,
                    'status' => $course->status,
                ]),
        ];
    }

    /**
     * Generate content engagement reports for Editors.
     */
    public function getEditorReport(): array
    {
        return [
            'video_stats' => [
                'total_videos' => Video::count(),
                'processing_videos' => Video::where('status', 'processing')->count(),
            ],
            'recent_uploads' => Video::with('uploader')
                ->latest()
                ->limit(10)
                ->get(),
        ];
    }

    /**
     * Generate personal progress reports for Students (Regular).
     */
    public function getStudentReport(User $student): array
    {
        $enrollments = $student->enrollments()->with(['course.lessons', 'lessonProgress'])->get();

        $courseProgress = $enrollments->map(function ($enrollment) {
            $totalLessons = $enrollment->course->lessons->count();
            $completedLessons = $enrollment->lessonProgress->where('status', 'completed')->count();
            $averageScore = $enrollment->lessonProgress->avg('score') ?? 0;

            return [
                'course_title' => $enrollment->course->title,
                'progress_percentage' => $totalLessons > 0 ? round(($completedLessons / $totalLessons) * 100) : 0,
                'average_score' => round($averageScore, 2),
                'status' => $enrollment->status,
            ];
        });

        return [
            'learning_stats' => [
                'enrolled_courses' => $enrollments->count(),
                'overall_progress' => round($courseProgress->avg('progress_percentage') ?? 0),
                'overall_score' => round($courseProgress->avg('average_score') ?? 0, 2),
                'upcoming_meetings' => Meeting::where('status', 'scheduled')
                    ->whereHas('course', function ($q) use ($student) {
                        $q->whereHas('enrollments', fn ($sq) => $sq->where('user_id', $student->id));
                    })
                    ->count(),
            ],
            'my_courses' => $courseProgress,
        ];
    }
}
