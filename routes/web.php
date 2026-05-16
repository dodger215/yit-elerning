<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CourseContentController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\InstructorManagementController;
use App\Http\Controllers\InviteController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\VideoController;
use Illuminate\Support\Facades\Route;

Route::inertia('/', 'Welcome')->name('home');

// Auth Routes
Route::prefix('auth')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/otp/send', [AuthController::class, 'sendOtp'])->name('auth.otp.send');
    Route::post('/otp/verify', [AuthController::class, 'verifyOtp'])->name('auth.otp.verify');

    Route::inertia('/register', 'Auth/Register')->name('register');
    Route::post('/register', [AuthController::class, 'register'])->name('register.store');

    Route::get('/google', [AuthController::class, 'redirectToGoogle'])->name('auth.google');
    Route::get('/google/callback', [AuthController::class, 'handleGoogleCallback'])->name('auth.google.callback');
    Route::get('/select-role', [AuthController::class, 'showRoleSelection'])->name('auth.role-selection');
    Route::post('/select-role', [AuthController::class, 'selectRole'])->name('auth.role.select');

    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // Invite Verification
    Route::get('/invite/{token}', [InviteController::class, 'verify'])->name('auth.invite.verify');
});

// Hybrid Routes (Public or Auth)
Route::get('/meeting/{room_id}', [\App\Http\Controllers\MeetingController::class, 'join'])->name('meeting.join');

// Protected Routes
Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [\App\Http\Controllers\DashboardController::class, 'index'])->name('dashboard');

    // Meetings Routes
    Route::get('/meetings', [\App\Http\Controllers\MeetingController::class, 'index'])->name('meetings.index');
    Route::post('/meetings', [\App\Http\Controllers\MeetingController::class, 'store'])->name('meetings.store');
    Route::patch('/meetings/{meeting}', [\App\Http\Controllers\MeetingController::class, 'update'])->name('meetings.update');

    // Profile & Settings
    Route::get('/profile', [\App\Http\Controllers\ProfileController::class, 'index'])->name('profile.index');
    Route::get('/settings', [\App\Http\Controllers\SettingController::class, 'index'])->name('settings.index');

    // Reports Routes
    Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
    Route::get('/reports/export', [ReportController::class, 'export'])->name('reports.export');

    // Supervisor Routes
    Route::middleware(['role:supervisor'])->group(function () {
        Route::get('/admin/invites', [InviteController::class, 'index'])->name('admin.invites.index');
        Route::post('/invite', [InviteController::class, 'sendInvite'])->name('admin.invite');
        
        // User Management
        Route::get('/admin/users', [UserController::class, 'index'])->name('admin.users.index');
        Route::put('/admin/users/{user}/roles', [UserController::class, 'updateRoles'])->name('admin.users.roles.update');
        Route::post('/admin/users/{user}/toggle', [UserController::class, 'toggleStatus'])->name('admin.users.toggle');
        Route::delete('/admin/users/{user}', [UserController::class, 'destroy'])->name('admin.users.destroy');

        // Instructor Supervision
        Route::get('/admin/instructors', [InstructorManagementController::class, 'index'])->name('admin.instructors.index');
        Route::get('/admin/instructors/{instructor}', [InstructorManagementController::class, 'show'])->name('admin.instructors.show');
        Route::put('/admin/courses/{course}/status', [InstructorManagementController::class, 'updateCourseStatus'])->name('admin.courses.status.update');
    });

    // Instructor Routes
    Route::middleware(['role:instructor,supervisor'])->group(function () {
        // Instructor routes here
    });

    // Editor Routes
    Route::middleware(['role:editor,supervisor'])->group(function () {
        // Editor routes here
    });

    // Course Routes
    Route::get('/courses', [CourseController::class, 'index'])->name('courses.index');
    Route::get('/courses/{course}', [CourseController::class, 'show'])->name('courses.show');
    Route::post('/courses', [CourseController::class, 'store'])->name('courses.store');
    Route::put('/courses/{course}', [CourseController::class, 'update'])->name('courses.update');
    Route::delete('/courses/{course}', [CourseController::class, 'destroy'])->name('courses.destroy');
    Route::post('/courses/{course}/enroll', [CourseController::class, 'enroll'])->name('courses.enroll');

    // Course Content Management
    Route::post('/courses/{course}/sections', [CourseContentController::class, 'addSection'])->name('courses.sections.store');
    Route::post('/sections/{section}/lessons', [CourseContentController::class, 'addLesson'])->name('sections.lessons.store');

    // Video Routes
    Route::get('/videos', [VideoController::class, 'index'])->name('videos.index');
    Route::get('/videos/{video}', [VideoController::class, 'show'])->name('videos.show');
    Route::post('/videos', [VideoController::class, 'store'])->name('videos.store');
    Route::put('/videos/{video}', [VideoController::class, 'update'])->name('videos.update');
    Route::delete('/videos/{video}', [VideoController::class, 'destroy'])->name('videos.destroy');
});
