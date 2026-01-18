<?php
// backend/app/Filament/Student/Resources/StudentResource/Pages/EditStudent.php

namespace App\Filament\Student\Resources\StudentResource\Pages;

use App\Filament\Student\Resources\StudentResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Support\Facades\Hash;

class EditStudent extends EditRecord
{
    protected static string $resource = StudentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        // Обновляем пароль, если он указан
        if (!empty($data['new_password'])) {
            $data['password'] = Hash::make($data['new_password']);
        }
        
        // Удаляем временные поля
        unset($data['new_password'], $data['new_password_confirmation']);
        
        return $data;
    }
}
