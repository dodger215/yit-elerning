<?php

namespace App\Services;

use App\Models\Course;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class CourseService
{
    /**
     * Create a new course.
     */
    public function createCourse(array $data, User $instructor): Course
    {
        return DB::transaction(function () use ($data, $instructor) {
            $course = Course::create([
                'title' => $data['title'],
                'description' => $data['description'],
                'short_description' => $data['short_description'] ?? null,
                'price' => $data['price'] ?? 0,
                'is_free' => $data['is_free'] ?? false,
                'level' => $data['level'] ?? 'beginner',
                'language' => $data['language'] ?? 'English',
                'instructor_id' => $instructor->id,
                'status' => 'draft',
            ]);

            return $course;
        });
    }

    /**
     * Update an existing course.
     */
    public function updateCourse(Course $course, array $data): Course
    {
        $course->update($data);
        return $course;
    }

    /**
     * Delete a course.
     */
    public function deleteCourse(Course $course): bool
    {
        return $course->delete();
    }

    /**
     * Enroll a student in a course.
     */
    public function enrollStudent(Course $course, User $student): bool
    {
        if ($student->enrollments()->where('course_id', $course->id)->exists()) {
            return false;
        }

        $student->enrollments()->create([
            'course_id' => $course->id,
            'status' => 'active',
            'enrolled_at' => now(),
        ]);

        $course->increment('total_students');

        return true;
    }

    /**
     * Add a section to a course.
     */
    public function addSection(Course $course, array $data)
    {
        return $course->sections()->create([
            'title' => $data['title'],
            'description' => $data['description'] ?? null,
            'section_order' => $course->sections()->count() + 1,
        ]);
    }

    /**
     * Add a lesson to a section.
     */
    public function addLesson($section, array $data)
    {
        return $section->lessons()->create([
            'title' => $data['title'],
            'description' => $data['description'] ?? null,
            'video_id' => $data['video_id'] ?? null,
            'is_preview' => $data['is_preview'] ?? false,
            'lesson_order' => $section->lessons()->count() + 1,
        ]);
    }
}
