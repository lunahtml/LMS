<?php
// backend/app/Filament/Resources/CompanyResource/RelationManagers/StudentProgressRelationManager.php

namespace App\Filament\Resources\CompanyResource\RelationManagers;

use Filament\Tables;
use Filament\Tables\Table;
use Filament\Resources\RelationManagers\RelationManager;

class StudentProgressRelationManager extends RelationManager
{
    protected static string $relationship = 'students';

    protected static ?string $recordTitleAttribute = 'name';

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('email')
                    ->searchable(),
                Tables\Columns\TextColumn::make('courses_count')
                    ->label('Total Courses')
                    ->formatStateUsing(function ($state, $record) {
                        return $record->courses()->count();
                    }),
                Tables\Columns\TextColumn::make('completed_lessons_count')
                    ->label('Completed Lessons')
                    ->formatStateUsing(function ($state, $record) {
                        return $record->completedLessons()->count();
                    }),
            ])
            ->filters([
                //
            ])
            ->headerActions([])
            ->actions([
                \Filament\Actions\Action::make('view_details')
                    ->label('View')
                    ->url(fn ($record): string => '#'),
            ])
            ->bulkActions([]);
    }
}