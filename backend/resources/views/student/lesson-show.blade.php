<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $lesson->title }} - {{ $course->title }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50">
    <div class="min-h-screen">
        <!-- Навигация -->
        <nav class="bg-white shadow">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-16">
                    <div class="flex items-center space-x-4">
                        <a href="{{ route('student.courses.show', $course) }}" class="text-gray-700 hover:text-gray-900">
                            ← Back to Course
                        </a>
                        <span class="text-gray-500">/</span>
                        <span class="text-gray-900">{{ $lesson->title }}</span>
                    </div>
                    <div class="flex items-center">
                        <span class="text-gray-700">{{ Auth::user()->name }}</span>
                    </div>
                </div>
            </div>
        </nav>

        <!-- Контент урока -->
        <main class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
            <div class="bg-white rounded-lg shadow">
                <div class="px-6 py-4 border-b">
                    <h1 class="text-2xl font-bold text-gray-900">{{ $lesson->title }}</h1>
                    <p class="text-gray-600 mt-1">Course: {{ $course->title }}</p>
                </div>
                
                <div class="p-6">
                    <!-- Содержимое урока -->
                    <div class="prose max-w-none">
                        {!! $lesson->content !!}
                    </div>

                    <!-- Дополнительные материалы -->
                    @if($lesson->video_url || $lesson->attachment_path)
                        <div class="mt-8 border-t pt-6">
                            <h3 class="text-lg font-semibold text-gray-900 mb-4">Additional Materials</h3>
                            <div class="space-y-4">
                                @if($lesson->video_url)
                                    <div>
                                        <h4 class="font-medium text-gray-900">Video</h4>
                                        <a href="{{ $lesson->video_url }}" target="_blank" class="text-blue-600 hover:underline">
                                            Watch video
                                        </a>
                                    </div>
                                @endif
                                
                                @if($lesson->attachment_path)
                                    <div>
                                        <h4 class="font-medium text-gray-900">Attachment</h4>
                                        <a href="{{ Storage::url($lesson->attachment_path) }}" target="_blank" class="text-blue-600 hover:underline">
                                            Download file
                                        </a>
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endif

                    <!-- Кнопка завершения -->
                    <div class="mt-8 border-t pt-6">
                        @if($progress->is_completed)
                            <div class="bg-green-50 border border-green-200 rounded-lg p-4">
                                <div class="flex items-center">
                                    <svg class="w-5 h-5 text-green-600 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                                    </svg>
                                    <span class="text-green-800 font-medium">Lesson completed on {{ $progress->completed_at->format('M d, Y') }}</span>
                                </div>
                                @if($progress->score)
                                    <p class="text-green-700 mt-1">Score: {{ $progress->score }}/100</p>
                                @endif
                            </div>
                        @else
                            <form action="{{ route('student.lessons.complete', ['course' => $course, 'lesson' => $lesson]) }}" method="POST">
                                @csrf
                                <button type="submit" class="px-6 py-3 bg-green-600 text-white font-medium rounded-lg hover:bg-green-700">
                                    Mark as Completed
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
            </div>
        </main>
    </div>
</body>
</html>