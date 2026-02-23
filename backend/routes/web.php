<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});



Route::middleware(['auth'])->prefix('student')->name('student.')->group(function () {
    Route::get('/courses/{course}', [\App\Http\Controllers\Student\CourseController::class, 'show'])
        ->name('courses.show');
        
    Route::get('/courses/{course}/lessons/{lesson}', [\App\Http\Controllers\Student\LessonController::class, 'show'])
        ->name('lessons.show');
    
    Route::post('/courses/{course}/lessons/{lesson}/complete', [\App\Http\Controllers\Student\LessonController::class, 'complete'])
        ->name('lessons.complete');

    Route::post('/courses/{course}/complete', [\App\Http\Controllers\Student\CourseController::class, 'complete'])
        ->name('courses.complete');   
});

// Для компаний
Route::middleware(['auth', 'role:company'])->prefix('company')->name('company.')->group(function () {
    Route::get('/students/{student}/progress', [\App\Http\Controllers\Company\StudentProgressController::class, 'show'])
        ->name('student.progress');



});