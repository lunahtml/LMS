<?php
// backend/app/Http/Controllers/Student/CourseController.php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\LessonProgress;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CourseController extends Controller
{
    public function show(Course $course)
    {
        // Проверяем доступ
        $student = Auth::user();
        
        if (!Auth::user()->hasRole('student')) {
            abort(403, 'Access denied');
        }

        $hasAccess = $student->courses()->where('course_id', $course->id)->exists() ||
                     $course->companies()->where('company_id', $student->company_id)->exists();
        
        if (!$hasAccess) {
            abort(403, 'You do not have access to this course');
        }

        $totalLessons = $course->lessons()->count();
        $completedLessons = $student->completedLessons()
            ->where('course_id', $course->id)
            ->count();
            
        $progressPercentage = $totalLessons > 0 ? (int)(($completedLessons / $totalLessons) * 100) : 0;

        return view('student.course-show', [
            'course' => $course,
            'progressPercentage' => $progressPercentage,
        ]);
    }

    public function getLessonStatus($course, $lessonId)
    {
        $progress = LessonProgress::where('student_id', Auth::id())
            ->where('lesson_id', $lessonId)
            ->where('course_id', $course->id)
            ->first();
            
        return [
            'is_completed' => $progress?->is_completed ?? false,
            'started_at' => $progress?->started_at,
            'completed_at' => $progress?->completed_at,
            'score' => $progress?->score,
        ];
    }
}
