<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\CourseLesson;
use App\Models\CourseSection;
use App\Services\CourseService;
use Illuminate\Http\Request;
use Inertia\Inertia;
use PhpOffice\PhpWord\IOFactory;
use Smalot\PdfParser\Parser;

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

    /**
     * Show the lesson content editor.
     */
    public function editLessonContent(CourseLesson $lesson)
    {
        $this->authorize('course.manage', $lesson->section->course);

        $lesson->load(['section.course', 'video']);

        return Inertia::render('Courses/Lessons/Content', [
            'lesson' => $lesson,
            'course' => $lesson->section->course,
        ]);
    }

    /**
     * Update the lesson content.
     */
    public function updateLessonContent(Request $request, CourseLesson $lesson)
    {
        $this->authorize('course.manage', $lesson->section->course);

        $validated = $request->validate([
            'content' => 'nullable|string',
        ]);

        $lesson->update([
            'content' => $validated['content'],
        ]);

        return back()->with('success', 'Lesson content saved successfully.');
    }

    /**
     * Extract text from a document (PDF, DOCX).
     */
    public function extractTextFromDocument(Request $request)
    {
        // Don't strictly authorize a specific course here since it's just a generic file upload,
        // but ensure the user is an instructor or higher.
        if (! auth()->user()->hasRole('instructor') && ! auth()->user()->hasRole('editor') && ! auth()->user()->hasRole('supervisor')) {
            abort(403);
        }

        $request->validate([
            'document' => 'required|file|mimes:pdf,docx,txt',
        ]);

        $file = $request->file('document');
        $extension = strtolower($file->getClientOriginalExtension());
        $text = '';

        try {
            if ($extension === 'pdf') {
                $parser = new Parser;
                $pdf = $parser->parseFile($file->getPathname());
                $text = $pdf->getText();
            } elseif ($extension === 'docx') {
                $phpWord = IOFactory::load($file->getPathname());
                foreach ($phpWord->getSections() as $section) {
                    foreach ($section->getElements() as $element) {
                        if (method_exists($element, 'getElements')) {
                            foreach ($element->getElements() as $childElement) {
                                if (method_exists($childElement, 'getText')) {
                                    $text .= $childElement->getText().' ';
                                }
                            }
                        } elseif (method_exists($element, 'getText')) {
                            $text .= $element->getText()."\n";
                        }
                    }
                }
            } elseif ($extension === 'txt') {
                $text = file_get_contents($file->getPathname());
            }

            return response()->json([
                'text' => trim($text),
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Failed to extract text from document: '.$e->getMessage(),
            ], 422);
        }
    }
}
