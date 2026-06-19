<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\FormData;
use App\Models\Forms;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Inertia\Inertia;

class FormController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        $query = Forms::with('user');

        // If regular user, show only active forms assigned to them or general ones
        if ($user->hasRole('regular')) {
            $query->where('is_active', true)
                ->where(function ($q) use ($user) {
                    $q->where('form_type', 'general')
                        ->orWhere(function ($sub) use ($user) {
                            $sub->where('form_type', 'course_assignment')
                                ->whereIn('course_id', $user->enrollments()->pluck('course_id'));
                        });
                });
        } else {
            // Instructor/Supervisor sees forms they created
            $query->where('by', $user->id);
        }

        $forms = $query->latest()->paginate(10);

        return Inertia::render('Forms/Index', [
            'forms' => $forms,
        ]);
    }

    public function showForm($formId)
    {
        $form = Forms::with('course')->findOrFail($formId);

        if (! $form->is_active) {
            return redirect()->back()->with('error', 'Form is not active');
        }

        // If course assignment, check enrollment
        if ($form->form_type === 'course_assignment') {
            $user = Auth::user();
            $isEnrolled = $user->enrollments()->where('course_id', $form->course_id)->exists();
            $isInstructor = $user->hasRole('instructor') || $user->hasRole('supervisor');

            if (! $isEnrolled && ! $isInstructor) {
                return redirect()->route('dashboard')->with('error', 'You must be enrolled in the course to access this assignment.');
            }
        }

        return Inertia::render('Forms/Show', [
            'form' => $form,
        ]);
    }

    public function createForm()
    {
        $courses = Course::select('id', 'title')->get();

        return Inertia::render('Forms/Create', [
            'courses' => $courses,
        ]);
    }

    public function storeForm(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'fields' => 'required', // json or array depending on how it's sent
            'form_type' => 'required|in:general,course_assignment',
            'course_id' => 'required_if:form_type,course_assignment|nullable|exists:courses,id',
            'cohort' => 'nullable|string',
        ]);

        $form = Forms::create([
            'id' => Str::uuid(),
            'by' => Auth::id(),
            'title' => $request->title,
            'description' => $request->description,
            'fields' => is_string($request->fields) ? $request->fields : json_encode($request->fields),
            'form_type' => $request->form_type,
            'course_id' => $request->course_id,
            'cohort' => $request->cohort,
        ]);

        return redirect()->route('forms.index')->with('success', 'Form created successfully!');
    }

    public function submitForm(Request $request, $formId)
    {
        $form = Forms::findOrFail($formId);

        if (! $form->is_active) {
            return response()->json(['message' => 'Form is not active'], 403);
        }

        $request->validate([
            'email_to_notify' => 'nullable|email',
            'data' => 'required',
        ]);

        $formData = FormData::create([
            'id' => Str::uuid(),
            'form_id' => $formId,
            'user_id' => Auth::id(),
            'email_to_notify' => $request->email_to_notify,
            'data' => is_string($request->data) ? $request->data : json_encode($request->data),
        ]);

        if ($formData->email_to_notify) {
            // mail notification logic
        }

        return Inertia::render('Forms/Submit', [
            'message' => 'Form submitted successfully',
            'form' => $form,
        ]);
    }

    public function submissions($formId)
    {
        $form = Forms::findOrFail($formId);

        // Ensure only the creator or supervisor can see submissions
        if ($form->by !== Auth::id() && ! Auth::user()->hasRole('supervisor')) {
            abort(403);
        }

        $submissions = FormData::where('form_id', $formId)
            ->with('user')
            ->latest()
            ->paginate(20);

        return Inertia::render('Forms/Submissions', [
            'form' => $form,
            'submissions' => $submissions,
        ]);
    }

    public function gradeSubmission(Request $request, $submissionId)
    {
        $submission = FormData::findOrFail($submissionId);
        $form = $submission->form;

        if ($form->by !== Auth::id() && ! Auth::user()->hasRole('supervisor')) {
            abort(403);
        }

        $request->validate([
            'grade' => 'required|string',
            'feedback' => 'nullable|string',
        ]);

        $submission->update([
            'grade' => $request->grade,
            'feedback' => $request->feedback,
            'graded_by' => Auth::id(),
        ]);

        return back()->with('success', 'Submission graded successfully!');
    }

    public function deactivateForm($formId)
    {
        $form = Forms::findOrFail($formId);

        if ($form->by !== Auth::id() && ! Auth::user()->hasRole('supervisor')) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $form->is_active = ! $form->is_active;
        $form->save();

        return redirect()->back()->with('success', 'Form status updated.');
    }
}
