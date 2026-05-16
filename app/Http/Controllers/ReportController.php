<?php

namespace App\Http\Controllers;

use App\Services\ReportService;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ReportController extends Controller
{
    public function __construct(protected ReportService $reportService) {}

    /**
     * Display the reports page based on user role.
     */
    public function index()
    {
        $user = auth()->user();
        $reportData = [];

        if ($user->hasRole('supervisor')) {
            $reportData = $this->reportService->getSupervisorReport();
            $view = 'Admin/Reports/Index';
        } elseif ($user->hasRole('instructor')) {
            $reportData = $this->reportService->getInstructorReport($user);
            $view = 'Instructor/Reports/Index';
        } elseif ($user->hasRole('editor')) {
            $reportData = $this->reportService->getEditorReport();
            $view = 'Editor/Reports/Index';
        } else {
            $reportData = $this->reportService->getStudentReport($user);
            $view = 'Student/Reports/Index';
        }

        return Inertia::render($view, [
            'report' => $reportData,
        ]);
    }

    /**
     * Export report as JSON.
     */
    public function export(Request $request)
    {
        $user = auth()->user();
        $data = [];

        if ($user->hasRole('supervisor')) {
            $data = $this->reportService->getSupervisorReport();
        } elseif ($user->hasRole('instructor')) {
            $data = $this->reportService->getInstructorReport($user);
        }

        return response()->json([
            'generated_at' => now(),
            'data' => $data,
        ]);
    }
}
