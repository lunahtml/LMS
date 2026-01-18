<?php
// backend/app/Filament/Company/Resources/CompanyResource/RelationManagers/CoursesRelationManager.php

namespace App\Filament\Company\Resources\CompanyResource\RelationManagers;

use Filament\Tables;
use Filament\Tables\Table;
use Filament\Resources\RelationManagers\RelationManager;

class CoursesRelationManager extends RelationManager
{
    protected static string $relationship = 'courses';

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('title')
            ->columns([
                Tables\Columns\TextColumn::make('title')
                    ->searchable(),
                Tables\Columns\TextColumn::make('category.name')
                    ->label('Category'),
                Tables\Columns\TextColumn::make('teacher.name')
                    ->label('Teacher'),
                Tables\Columns\TextColumn::make('description')
                    ->limit(50)
                    ->wrap(),
                Tables\Columns\TextColumn::make('price')
                    ->money('USD')
                    ->sortable(),
                Tables\Columns\IconColumn::make('is_published')
                    ->boolean()
                    ->label('Published'),
            ])
            ->filters([
                //
            ])
            ->headerActions([]) // Компании не могут добавлять курсы самостоятельно
            ->actions([
                // ViewAction не существует в Filament 1.x, убираем
            ])
            ->bulkActions([]);
    }
}