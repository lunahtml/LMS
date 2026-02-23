<?php
// backend/app/Filament/Company/Resources/CompanyResource/RelationManagers/StudentProgressRelationManager.php

namespace App\Filament\Company\Resources\CompanyResource\RelationManagers;

use Filament\Tables;
use Filament\Tables\Table;
use Filament\Resources\RelationManagers\RelationManager;

class StudentProgressRelationManager extends RelationManager
{
    protected static string $relationship = 'students';

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('email')
                    ->searchable(),
                Tables\Columns\TextColumn::make('enrolled_courses')
                    ->label('Enrolled Courses')
                    ->getStateUsing(function ($record) {
                        return $record->courses()->count();
                    }),
                Tables\Columns\TextColumn::make('completed_courses')
                    ->label('Completed Courses')
                    ->getStateUsing(function ($record) {
                        return $record->courses()
                            ->whereNotNull('completed_at')
                            ->count();
                    }),
                Tables\Columns\TextColumn::make('total_progress')
                    ->label('Overall Progress')
                    ->getStateUsing(function ($record) {
                        $totalLessons = 0;
                        $completedLessons = 0;
                        
                        foreach ($record->courses as $course) {
                            $totalLessons += $course->lessons()->count();
                            $completedLessons += $record->completedLessons()
                                ->where('course_id', $course->id)
                                ->count();
                        }
                        
                        if ($totalLessons === 0) return '0%';
                        return (int)(($completedLessons / $totalLessons) * 100) . '%';
                    }),
            ])
            ->filters([
                //
            ])
            ->headerActions([])
            ->actions([
                \Filament\Actions\Action::make('view_details')
                    ->label('View Details')
                    ->url(fn ($record): string => '#'),
            ])
            ->bulkActions([]);
    }
}