{{-- backend/resources/views/filament/student/resources/course-resource/pages/view-course.blade.php --}}
<x-filament::page>
    <div class="space-y-6">
        <!-- Заголовок и прогресс -->
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex justify-between items-start">
                <div>
                    <h2 class="text-2xl font-bold text-gray-900">{{ $record->title }}</h2>
                    <p class="text-gray-600 mt-2">{{ $record->description }}</p>
                    <div class="mt-4 flex items-center space-x-4">
                        <span class="text-sm text-gray-500">Category: {{ $record->category->name ?? 'No category' }}</span>
                        <span class="text-sm text-gray-500">Teacher: {{ $record->teacher->name ?? 'No teacher' }}</span>
                    </div>
                </div>
                <div class="text-right">
                    <div class="text-lg font-semibold text-gray-900">
                        Progress: {{ $this->getProgressPercentage() }}%
                    </div>
                    <div class="mt-2 w-64 bg-gray-200 rounded-full h-2">
                        <div class="bg-green-600 h-2 rounded-full" 
                             style="width: {{ $this->getProgressPercentage() }}%"></div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Список уроков -->
        <div class="bg-white rounded-lg shadow">
            <div class="px-6 py-4 border-b">
                <h3 class="text-lg font-semibold text-gray-900">Course Lessons</h3>
            </div>
            
            <div class="divide-y">
                @forelse($record->lessons()->orderBy('course_lesson.order')->get() as $lesson)
                    @php
                        $status = $this->getLessonStatus($lesson->id);
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
                                    @elseif($status['started_at'])
                                        <div class="w-8 h-8 rounded-full bg-blue-100 flex items-center justify-center">
                                            <svg class="w-5 h-5 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd" />
                                            </svg>
                                        </div>
                                    @else
                                        <div class="w-8 h-8 rounded-full bg-gray-100 flex items-center justify-center">
                                            <span class="text-gray-600 font-medium">{{ $loop->iteration }}</span>
                                        </div>
                                    @endif
                                </div>
                                
                                <div>
                                    <h4 class="font-medium text-gray-900">{{ $lesson->title }}</h4>
                                    <p class="text-sm text-gray-600 mt-1">{!! \Illuminate\Support\Str::limit(strip_tags($lesson->content), 100) !!}</p>
                                </div>
                            </div>
                            
                            <div class="flex items-center space-x-4">
                                @if($status['is_completed'])
                                    <span class="text-sm text-green-600">Completed</span>
                                    @if($status['score'] !== null)
                                        <span class="text-sm font-medium text-gray-900">Score: {{ $status['score'] }}/100</span>
                                    @endif
                                @elseif($status['started_at'])
                                    <span class="text-sm text-blue-600">In Progress</span>
                                @endif
                                
                                <a href="{{ route('student.lesson.view', ['course' => $record->id, 'lesson' => $lesson->id]) }}"
                                   class="px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-md hover:bg-blue-700">
                                    {{ $status['started_at'] ? 'Continue' : 'Start' }}
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
</x-filament::page>