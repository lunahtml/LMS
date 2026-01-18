<?php
// backend/app/Filament/Student/Pages/Dashboard.php

namespace App\Filament\Student\Pages;

use Filament\Pages\Dashboard as BaseDashboard;

class Dashboard extends BaseDashboard
{
    protected string $view = 'filament.pages.dashboard';
    
    public static function canAccess(): bool
    {
        return auth()->user()->hasRole('student');
    }
}