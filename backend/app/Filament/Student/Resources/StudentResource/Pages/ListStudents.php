<?php
// backend/app/Filament/Student/Resources/StudentResource/Pages/ListStudents.php

namespace App\Filament\Student\Resources\StudentResource\Pages;

use App\Filament\Student\Resources\StudentResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListStudents extends ListRecords
{
    protected static string $resource = StudentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()->visible(false),
        ];
    }

    protected function getTableRecordsPerPageSelectOptions(): array
    {
        return [1]; // Показываем только 1 запись (самого студента)
    }

    protected function afterActionExecuted(): void
    {
        // Автоматически перенаправляем на редактирование, так как запись только одна
        $record = $this->getRecords()->first();
        if ($record) {
            $this->redirect(EditStudent::getUrl([$record]));
        }
    }
}