<?php

namespace App\Filament\Pages;

use Filament\Pages\Dashboard as BaseDashboard;
use Filament\Actions\Action;

class Dashboard extends BaseDashboard
{
    protected static ?string $navigationIcon = 'heroicon-o-home';

    protected function getHeaderActions(): array
    {
        return [
            Action::make('back_to_main_dashboard')
                ->label('Back to Main Dashboard')
                ->url('/dashboard')
                ->icon('heroicon-o-arrow-left')
                ->color('gray')
                ->openUrlInNewTab(false),
        ];
    }
}
