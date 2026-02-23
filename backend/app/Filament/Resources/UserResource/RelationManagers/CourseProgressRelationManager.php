<?php
// backend/app/Filament/Resources/UserResource/RelationManagers/CourseProgressRelationManager.php

namespace App\Filament\Resources\UserResource\RelationManagers;

use Filament\Tables;
use Filament\Tables\Table;
use Filament\Resources\RelationManagers\RelationManager;

class CourseProgressRelationManager extends RelationManager
{
    protected static string $relationship = 'courses';

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('title')
                    ->searchable(),
                Tables\Columns\TextColumn::make('lessons_progress')
                    ->label('Lessons Progress')
                    ->getStateUsing(function ($record) {
                        $student = $this->getOwnerRecord();
                        $totalLessons = $record->lessons()->count();
                        if ($totalLessons === 0) return '0/0 (0%)';
                        
                        $completedLessons = $student->completedLessons()
                            ->where('course_id', $record->id)
                            ->count();
                            
                        $percentage = (int)(($completedLessons / $totalLessons) * 100);
                        return "{$completedLessons}/{$totalLessons} ({$percentage}%)";
                    }),
                Tables\Columns\TextColumn::make('course_status')
                    ->label('Course Status')
                    ->getStateUsing(function ($record) {
                        $student = $this->getOwnerRecord();
                        $coursePivot = $student->courses()
                            ->where('course_id', $record->id)
                            ->first();
                        
                        if ($coursePivot && $coursePivot->pivot->completed_at) {
                            return 'Completed on ' . $coursePivot->pivot->completed_at->format('M d, Y');
                        }
                        
                        $totalLessons = $record->lessons()->count();
                        if ($totalLessons === 0) return 'No lessons';
                        
                        $completedLessons = $student->completedLessons()
                            ->where('course_id', $record->id)
                            ->count();
                        
                        if ($completedLessons === 0) return 'Not started';
                        if ($completedLessons < $totalLessons) return 'In progress';
                        return 'Ready to complete';
                    })
                    ->color(function ($state, $record) {
                        $student = $this->getOwnerRecord();
                        $coursePivot = $student->courses()
                            ->where('course_id', $record->id)
                            ->first();
                        
                        if ($coursePivot && $coursePivot->pivot->completed_at) {
                            return 'success';
                        }
                        
                        $totalLessons = $record->lessons()->count();
                        if ($totalLessons === 0) return 'gray';
                        
                        $completedLessons = $student->completedLessons()
                            ->where('course_id', $record->id)
                            ->count();
                        
                        if ($completedLessons === 0) return 'gray';
                        if ($completedLessons < $totalLessons) return 'warning';
                        return 'info';
                    }),
                Tables\Columns\TextColumn::make('pivot.enrolled_at')
                    ->label('Enrolled')
                    ->date(),
            ])
            ->filters([
                //
            ])
            ->headerActions([])
            ->actions([])
            ->bulkActions([]);
    }
}