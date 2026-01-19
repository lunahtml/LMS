<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $course->title }} - LMS Platform</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50">
    <div class="min-h-screen">
        <!-- Навигация -->
        <nav class="bg-white shadow">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-16">
                    <div class="flex items-center">
                        <a href="{{ url('/student/courses') }}" class="text-gray-700 hover:text-gray-900">
                            ← Back to Courses
                        </a>
                    </div>
                    <div class="flex items-center">
                        <span class="text-gray-700">{{ Auth::user()->name }}</span>
                    </div>
                </div>
            </div>
        </nav>

        <!-- Контент -->
        <main class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
            <div class="space-y-6">
                <!-- Заголовок и прогресс -->
                <div class="bg-white rounded-lg shadow p-6">
                    <div class="flex justify-between items-start">
                        <div>
                            <h1 class="text-3xl font-bold text-gray-900">{{ $course->title }}</h1>
                            <p class="text-gray-600 mt-2">{{ $course->description }}</p>
                            <div class="mt-4 flex items-center space-x-4">
                                <span class="text-sm text-gray-500">Category: {{ $course->category->name ?? 'No category' }}</span>
                                <span class="text-sm text-gray-500">Teacher: {{ $course->teacher->name ?? 'No teacher' }}</span>
                            </div>
                        </div>
                        <div class="text-right">
                            <div class="text-lg font-semibold text-gray-900">
                                Progress: {{ $progressPercentage }}%
                            </div>
                            <div class="mt-2 w-64 bg-gray-200 rounded-full h-2">
                                <div class="bg-green-600 h-2 rounded-full" 
                                     style="width: {{ $progressPercentage }}%"></div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Список уроков -->
                <div class="bg-white rounded-lg shadow">
                    <div class="px-6 py-4 border-b">
                        <h2 class="text-lg font-semibold text-gray-900">Course Lessons</h2>
                    </div>
                    
                    <div class="divide-y">
                        @forelse($course->lessons()->orderBy('course_lesson.order')->get() as $lesson)
                            @php
                                $status = app(\App\Http\Controllers\Student\CourseController::class)->getLessonStatus($course, $lesson->id);
                            @endphp
                            <div class="px-6 py-4 hover:bg-gray-50">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center space-x-4">
                                        <div class="flex-shrink-0">
                                            @if($status['is_completed'])
                                                <div class="w-8 h-8 rounded-full bg-green-100 flex items-center justify-center">
                                                    <svg class="w-5 h-5 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                                                    </svg>
                                                </div>
                                            @else
                                                <div class="w-8 h-8 rounded-full bg-gray-100 flex items-center justify-center">
                                                    <span class="text-gray-600 font-medium">{{ $loop->iteration }}</span>
                                                </div>
                                            @endif
                                        </div>
                                        
                                        <div>
                                            <h3 class="font-medium text-gray-900">{{ $lesson->title }}</h3>
                                            <p class="text-sm text-gray-600 mt-1">{{ \Illuminate\Support\Str::limit($lesson->content, 100) }}</p>
                                        </div>
                                    </div>
                                    
                                    <div class="flex items-center space-x-4">
                                        @if($status['is_completed'])
                                            <span class="text-sm text-green-600">Completed</span>
                                            @if($status['score'] !== null)
                                                <span class="text-sm font-medium text-gray-900">Score: {{ $status['score'] }}/100</span>
                                            @endif
                                        @endif
                                        
                                        <a href="{{ route('student.lessons.show', ['course' => $course, 'lesson' => $lesson]) }}"
   class="px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-md hover:bg-blue-700">
    {{ $status['started_at'] ? 'Continue' : 'Start Lesson' }}
</a>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="px-6 py-8 text-center">
                                <p class="text-gray-500">No lessons available for this course yet.</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </main>
    </div>
</body>
</html>