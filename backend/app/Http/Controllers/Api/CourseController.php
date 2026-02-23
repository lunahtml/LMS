<?php
//backend/app/Http/Controllers/Api/CourseController.php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Course;
use Illuminate\Http\Request;

class CourseController extends Controller
{
    public function index()
    {
        return response()->json(Course::all());
    }

    public function show($id)
    {
        $course = Course::with('lessons')->find($id);
        if (!$course) {
            return response()->json(['error' => 'Course not found'], 404);
        }
        return response()->json($course);
    }
}