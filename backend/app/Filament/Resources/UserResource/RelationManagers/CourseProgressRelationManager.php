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
                Tables\Columns\TextColumn::make('progress')
                    ->label('Progress')
                    ->getStateUsing(function ($record) {
                        $student = $this->getOwnerRecord();
                        $totalLessons = $record->lessons()->count();
                        if ($totalLessons === 0) return '0%';
                        
                        $completedLessons = $student->completedLessons()
                            ->where('course_id', $record->id)
                            ->count();
                            
                        return (int)(($completedLessons / $totalLessons) * 100) . '%';
                    }),
            ])
            ->filters([
                //
            ])
            ->headerActions([])
            ->actions([])
            ->bulkActions([]);
    }
}