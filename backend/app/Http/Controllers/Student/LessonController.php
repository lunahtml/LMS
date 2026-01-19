<?php
// backend/app/Http/Controllers/Student/LessonController.php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Lesson;
use App\Models\LessonProgress;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LessonController extends Controller
{
    public function show(Course $course, Lesson $lesson)
    {
        $student = Auth::user();
        
        // Проверка доступа
        if (!$student->hasRole('student')) {
            abort(403, 'Access denied');
        }

        $hasAccess = $student->courses()->where('course_id', $course->id)->exists() ||
                     $course->companies()->where('company_id', $student->company_id)->exists();
        
        if (!$hasAccess || !$course->lessons()->where('lesson_id', $lesson->id)->exists()) {
            abort(403, 'You do not have access to this lesson');
        }
        if (!$student->courses()->where('course_id', $course->id)->exists()) {
            // Автоматически зачисляем студента на курс
            $student->courses()->attach($course->id, ['enrolled_at' => now()]);
        }
        // Создаем или обновляем прогресс
        $progress = LessonProgress::firstOrCreate(
            [
                'student_id' => $student->id,
                'lesson_id' => $lesson->id,
                'course_id' => $course->id,
            ],
            [
                'started_at' => now(),
            ]
        );

        // Если урок только начат, обновляем started_at
        if (!$progress->started_at) {
            $progress->update(['started_at' => now()]);
        }

        return view('student.lesson-show', [
            'course' => $course,
            'lesson' => $lesson,
            'progress' => $progress,
        ]);
    }

    public function complete(Request $request, Course $course, Lesson $lesson)
    {
        $student = Auth::user();
        
        $progress = LessonProgress::where('student_id', $student->id)
            ->where('lesson_id', $lesson->id)
            ->where('course_id', $course->id)
            ->firstOrFail();

        $progress->update([
            'is_completed' => true,
            'completed_at' => now(),
            'score' => $request->input('score', null),
        ]);

        return redirect()->route('student.courses.show', $course)
            ->with('success', 'Lesson completed successfully!');
    }
}