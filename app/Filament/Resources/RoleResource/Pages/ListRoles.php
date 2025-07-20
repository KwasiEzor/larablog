<?php

namespace App\Filament\Resources\RoleResource\Pages;

use App\Filament\Resources\RoleResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Filament\Actions\Action;
use App\Enums\RoleEnum;

class ListRoles extends ListRecords
{
    protected static string $resource = RoleResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->label('Create New Role')
                ->icon('heroicon-o-plus'),

            Action::make('create_system_roles')
                ->label('Create System Roles')
                ->icon('heroicon-o-shield-check')
                ->color('success')
                ->action(function () {
                    $this->createSystemRoles();
                })
                ->requiresConfirmation()
                ->modalHeading('Create System Roles')
                ->modalDescription('This will create the default system roles (admin, author, user) if they don\'t exist.')
                ->modalSubmitActionLabel('Create System Roles'),
        ];
    }

    protected function createSystemRoles(): void
    {
        $rolesCreated = 0;

        foreach (RoleEnum::cases() as $roleEnum) {
            $role = \Spatie\Permission\Models\Role::firstOrCreate(
                ['name' => $roleEnum->value],
                [
                    'display_name' => $roleEnum->displayName(),
                    'description' => $roleEnum->description(),
                    'is_system' => true,
                ]
            );

            if ($role->wasRecentlyCreated) {
                $rolesCreated++;
            }
        }

        if ($rolesCreated > 0) {
            $this->notify('success', "Successfully created {$rolesCreated} system role(s).");
        } else {
            $this->notify('info', 'All system roles already exist.');
        }
    }
}
