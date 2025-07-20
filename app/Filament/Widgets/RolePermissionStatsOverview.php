<?php

namespace App\Filament\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;
use App\Enums\RoleEnum;

class RolePermissionStatsOverview extends BaseWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('Total Users', User::count())
                ->description('All registered users')
                ->descriptionIcon('heroicon-m-users')
                ->color('primary'),

            Stat::make('Total Roles', Role::count())
                ->description('System and custom roles')
                ->descriptionIcon('heroicon-m-shield-check')
                ->color('success'),

            Stat::make('Total Permissions', Permission::count())
                ->description('Available permissions')
                ->descriptionIcon('heroicon-m-key')
                ->color('warning'),

            Stat::make('Admin Users', User::role('admin')->count())
                ->description('Users with admin role')
                ->descriptionIcon('heroicon-m-star')
                ->color('danger'),

            Stat::make('Author Users', User::role('author')->count())
                ->description('Users with author role')
                ->descriptionIcon('heroicon-m-pencil')
                ->color('warning'),

            Stat::make('Regular Users', User::role('user')->count())
                ->description('Users with basic role')
                ->descriptionIcon('heroicon-m-user')
                ->color('info'),

            Stat::make('System Roles', Role::where('is_system', true)->count())
                ->description('Predefined system roles')
                ->descriptionIcon('heroicon-m-shield-check')
                ->color('success'),

            Stat::make('Custom Roles', Role::where('is_system', false)->count())
                ->description('User-created custom roles')
                ->descriptionIcon('heroicon-m-user-group')
                ->color('gray'),

            Stat::make('System Permissions', Permission::where('is_system', true)->count())
                ->description('Predefined system permissions')
                ->descriptionIcon('heroicon-m-shield-check')
                ->color('success'),

            Stat::make('Custom Permissions', Permission::where('is_system', false)->count())
                ->description('User-created custom permissions')
                ->descriptionIcon('heroicon-m-key')
                ->color('gray'),
        ];
    }
}
