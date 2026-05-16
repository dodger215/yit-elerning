<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Meeting;
use App\Models\User;
use App\Models\Video;
use Illuminate\Http\Request;
use Inertia\Inertia;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $activeRole = session('active_role');

        $stats = [
            'total_users' => User::count(),
            'active_meetings' => Meeting::where('status', 'active')->count(),
            'total_courses' => Course::count(),
            'total_videos' => Video::count(),
            'new_students_count' => User::whereHas('roles', function($q) {
                $q->where('name', 'student');
            })->where('created_at', '>=', now()->subDays(30))->count(),
            'upcoming_meetings' => Meeting::where('start_time', '>', now())
                ->orderBy('start_time', 'asc')
                ->limit(5)
                ->get(),
            'recent_courses' => Course::with('instructor')
                ->orderBy('created_at', 'desc')
                ->limit(4)
                ->get(),
            'recent_users' => User::orderBy('created_at', 'desc')
                ->limit(10)
                ->get()
                ->map(fn($user) => [
                    'name' => $user->first_name . ' ' . $user->last_name,
                    'email' => $user->email,
                    'role' => $user->roles->first()?->name ?? 'User',
                    'joined' => $user->created_at->diffForHumans(),
                ]),
        ];

        return Inertia::render('Dashboard', [
            'stats' => $stats,
        ]);
    }
}
