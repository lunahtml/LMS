<?php
// backend/app/Filament/Student/Resources/CourseResource.php

namespace App\Filament\Student\Resources;

use App\Filament\Student\Resources\CourseResource\Pages;
use App\Models\Course;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Illuminate\Database\Eloquent\Builder;

class CourseResource extends Resource
{
    protected static ?string $model = Course::class;

    protected static ?string $navigationLabel = 'My Courses';

    protected static ?int $navigationSort = 2;

    public static function canAccess(): bool
    {
        return auth()->user()->hasRole('student');
    }

    public static function getEloquentQuery(): Builder
    {
        // Показываем только курсы, доступные студенту
        return parent::getEloquentQuery()
            ->whereHas('students', function ($query) {
                $query->where('student_id', auth()->id());
            })
            ->orWhereHas('companies', function ($query) {
                $query->where('company_id', auth()->user()->company_id);
            });
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('title')
                    ->searchable()
                    ->description(fn (Course $record) => $record->description ? substr($record->description, 0, 100) . '...' : ''),
                Tables\Columns\TextColumn::make('category.name')
                    ->label('Category'),
                Tables\Columns\TextColumn::make('teacher.name')
                    ->label('Teacher'),
                Tables\Columns\TextColumn::make('lessons_count')
                    ->label('Lessons')
                    ->counts('lessons'),
                Tables\Columns\IconColumn::make('is_published')
                    ->boolean()
                    ->label('Published'),
            ])
            ->filters([
                //
            ])
            ->actions([
                \Filament\Actions\Action::make('view')
                    ->label('View Course')
                    ->url(fn (Course $record): string => route('student.courses.show', ['course' => $record])),
            ])
            ->bulkActions([]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListCourses::route('/'),
        ];
    }
}