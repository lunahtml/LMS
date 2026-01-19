<?php
// backend/app/Http/Controllers/Company/StudentProgressController.php

namespace App\Http\Controllers\Company;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StudentProgressController extends Controller
{
    public function show(User $student)
    {
        $company = Auth::user()->company;
        
        // Проверяем, что студент принадлежит компании
        if ($student->company_id !== $company->id) {
            abort(403, 'Student does not belong to your company');
        }

        $courses = $student->courses()->with('lessons')->get();
        
        $progressData = [];
        foreach ($courses as $course) {
            $totalLessons = $course->lessons->count();
            $completedLessons = $student->completedLessons()
                ->where('course_id', $course->id)
                ->count();
                
            $progressData[] = [
                'course' => $course,
                'total_lessons' => $totalLessons,
                'completed_lessons' => $completedLessons,
                'percentage' => $totalLessons > 0 ? (int)(($completedLessons / $totalLessons) * 100) : 0,
                'enrolled_at' => $course->pivot->enrolled_at,
                'course_completed_at' => $course->pivot->completed_at,
            ];
        }

        return view('company.student-progress', [
            'student' => $student,
            'progressData' => $progressData,
            'company' => $company,
        ]);
    }
}
