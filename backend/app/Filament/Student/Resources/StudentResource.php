<?php
// backend/app/Filament/Student/Resources/StudentResource.php

namespace App\Filament\Student\Resources;

use App\Filament\Student\Resources\StudentResource\Pages;
use App\Models\User;
use Filament\Actions;
use Filament\Forms;
use Filament\Schemas\Schema;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class StudentResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationLabel = 'My Profile';

    protected static ?int $navigationSort = 1;

    public static function canAccess(): bool
    {
        return auth()->user()->hasRole('student');
    }

    public static function getEloquentQuery(): Builder
    {
        // Показываем только текущего студента
        return parent::getEloquentQuery()->where('id', auth()->id());
    }

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('email')
                    ->email()
                    ->required()
                    ->maxLength(255)
                    ->unique(ignoreRecord: true),
                Forms\Components\TextInput::make('company_name')
                    ->label('Company')
                    ->disabled()
                    ->dehydrated(false)
                    ->afterStateHydrated(function ($component, $state, $record) {
                        if ($record && $record->company) {
                            $component->state($record->company->name);
                        }
                    }),
                Forms\Components\TextInput::make('new_password')
                    ->label('New Password')
                    ->password()
                    ->revealable()
                    ->minLength(8)
                    ->confirmed()
                    ->visible(fn ($livewire) => $livewire instanceof Pages\EditStudent),
                Forms\Components\TextInput::make('new_password_confirmation')
                    ->label('Confirm New Password')
                    ->password()
                    ->revealable()
                    ->visible(fn ($livewire) => $livewire instanceof Pages\EditStudent),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('email')
                    ->searchable(),
                Tables\Columns\TextColumn::make('company.name')
                    ->label('Company')
                    ->searchable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Actions\EditAction::make(),
            ])
            ->bulkActions([]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListStudents::route('/'),
            'edit' => Pages\EditStudent::route('/{record}/edit'),
        ];
    }
}