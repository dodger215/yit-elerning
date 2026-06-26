<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CourseContentController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\FormController;
use App\Http\Controllers\InstructorManagementController;
use App\Http\Controllers\InviteController;
use App\Http\Controllers\MeetingChatController;
use App\Http\Controllers\MeetingController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\SeoController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\VideoController;
use App\Models\Video;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    $videos = Video::latest()->take(6)->get();

    return Inertia::render('Welcome', [
        'videos' => $videos,
        'appName' => config('app.name', 'EduConnect'),
    ]);
})->name('home');

// SEO Routes
Route::get('/sitemap.xml', [SeoController::class, 'sitemap'])->name('seo.sitemap');
Route::get('/robots.txt', [SeoController::class, 'robots'])->name('seo.robots');

// Auth Routes
Route::prefix('auth')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/otp/send', [AuthController::class, 'sendOtp'])->name('auth.otp.send');
    Route::post('/otp/verify', [AuthController::class, 'verifyOtp'])->name('auth.otp.verify');

    Route::get('/register', function (Request $request) {
        return Inertia::render('Auth/Register', [
            'email' => $request->query('email'),
            'first_name' => $request->query('first_name'),
            'last_name' => $request->query('last_name'),
        ]);
    })->name('register');
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
Route::get('/meeting/{room_id}', [MeetingController::class, 'join'])->name('meeting.join');
Route::get('/meeting/{room_id}/ended', [MeetingController::class, 'ended'])->name('meeting.ended');
Route::get('/meetings/recordings/{file}', [MeetingController::class, 'downloadRecording'])->name('meetings.download-recording');

// Meeting Chat Routes
Route::get('/meeting/{room_id}/chat', [MeetingChatController::class, 'index'])->name('meeting.chat.index');
Route::post('/meeting/{room_id}/chat', [MeetingChatController::class, 'store'])->name('meeting.chat.store');

// Protected Routes
Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Meetings Routes
    Route::get('/meetings', [MeetingController::class, 'index'])->name('meetings.index');
    Route::get('/meetings/recordings', [MeetingController::class, 'recordings'])->name('meetings.recordings');
    Route::post('/meetings', [MeetingController::class, 'store'])->name('meetings.store');
    Route::patch('/meetings/{meeting}', [MeetingController::class, 'update'])->name('meetings.update');
    Route::delete('/meetings/{meeting}', [MeetingController::class, 'destroy'])->name('meetings.destroy');
    Route::post('/meetings/{meeting}/upload-recording', [MeetingController::class, 'uploadRecording'])->name('meetings.upload-recording');
    Route::post('/meetings/{meeting}/resend-invites', [MeetingController::class, 'resendInvites'])->name('meetings.resend-invites');

    // Profile & Settings
    Route::get('/profile', [ProfileController::class, 'index'])->name('profile.index');
    Route::get('/settings', [SettingController::class, 'index'])->name('settings.index');

    // Reports Routes
    Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
    Route::get('/reports/export', [ReportController::class, 'export'])->name('reports.export');

    // Supervisor Routes
    Route::middleware(['role:supervisor'])->group(function () {
        Route::get('/admin/invites', [InviteController::class, 'index'])->name('admin.invites.index');
        Route::post('/invite', [InviteController::class, 'sendInvite'])->name('admin.invite');
        Route::get('/invite', fn () => redirect()->route('admin.invites.index'))->name('admin.invite.redirect');

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

    // Lesson Content Editor
    Route::get('/lessons/{lesson}/content', [CourseContentController::class, 'editLessonContent'])->name('lessons.content.edit');
    Route::put('/lessons/{lesson}/content', [CourseContentController::class, 'updateLessonContent'])->name('lessons.content.update');
    Route::post('/lessons/extract-text', [CourseContentController::class, 'extractTextFromDocument'])->name('lessons.content.extract');

    // Video Routes
    Route::get('/videos', [VideoController::class, 'index'])->name('videos.index');
    Route::get('/videos/{video}', [VideoController::class, 'show'])->name('videos.show');
    Route::post('/videos', [VideoController::class, 'store'])->name('videos.store');
    Route::put('/videos/{video}', [VideoController::class, 'update'])->name('videos.update');
    Route::delete('/videos/{video}', [VideoController::class, 'destroy'])->name('videos.destroy');
});

Route::middleware(['auth'])->prefix('forms')->group(function () {
    Route::get('/', [FormController::class, 'index'])->name('forms.index');
    Route::get('/create', [FormController::class, 'createForm'])->name('forms.create');
    Route::post('/create', [FormController::class, 'storeForm'])->name('forms.store');
    Route::get('/{form}/submissions', [FormController::class, 'submissions'])->name('forms.submissions');
    Route::post('/submissions/{submission}/grade', [FormController::class, 'gradeSubmission'])->name('forms.grade');
    Route::get('/{form}/edit', [FormController::class, 'editForm'])->name('forms.edit');
    Route::put('/{form}', [FormController::class, 'updateForm'])->name('forms.update');
});

Route::get('forms/{form}', [FormController::class, 'showForm'])->name('forms.show');
Route::post('forms/{form}/submit', [FormController::class, 'submitForm'])->name('forms.submit');
