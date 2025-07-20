<?php

namespace App\Filament\Resources\PermissionResource\Pages;

use App\Filament\Resources\PermissionResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Filament\Actions\Action;

class ListPermissions extends ListRecords
{
    protected static string $resource = PermissionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->label('Create New Permission')
                ->icon('heroicon-o-plus'),

            Action::make('create_system_permissions')
                ->label('Create System Permissions')
                ->icon('heroicon-o-shield-check')
                ->color('success')
                ->action(function () {
                    $this->createSystemPermissions();
                })
                ->requiresConfirmation()
                ->modalHeading('Create System Permissions')
                ->modalDescription('This will create common system permissions for the blog application.')
                ->modalSubmitActionLabel('Create System Permissions'),
        ];
    }

    protected function createSystemPermissions(): void
    {
        $permissions = [
            // User management permissions
            ['name' => 'view-users', 'display_name' => 'View Users', 'description' => 'Can view user list and details'],
            ['name' => 'create-users', 'display_name' => 'Create Users', 'description' => 'Can create new users'],
            ['name' => 'edit-users', 'display_name' => 'Edit Users', 'description' => 'Can edit existing users'],
            ['name' => 'delete-users', 'display_name' => 'Delete Users', 'description' => 'Can delete users'],

            // Role management permissions
            ['name' => 'view-roles', 'display_name' => 'View Roles', 'description' => 'Can view role list and details'],
            ['name' => 'create-roles', 'display_name' => 'Create Roles', 'description' => 'Can create new roles'],
            ['name' => 'edit-roles', 'display_name' => 'Edit Roles', 'description' => 'Can edit existing roles'],
            ['name' => 'delete-roles', 'display_name' => 'Delete Roles', 'description' => 'Can delete roles'],

            // Post management permissions
            ['name' => 'view-posts', 'display_name' => 'View Posts', 'description' => 'Can view posts'],
            ['name' => 'create-posts', 'display_name' => 'Create Posts', 'description' => 'Can create new posts'],
            ['name' => 'edit-posts', 'display_name' => 'Edit Posts', 'description' => 'Can edit existing posts'],
            ['name' => 'delete-posts', 'display_name' => 'Delete Posts', 'description' => 'Can delete posts'],
            ['name' => 'publish-posts', 'display_name' => 'Publish Posts', 'description' => 'Can publish posts'],

            // Category management permissions
            ['name' => 'view-categories', 'display_name' => 'View Categories', 'description' => 'Can view categories'],
            ['name' => 'create-categories', 'display_name' => 'Create Categories', 'description' => 'Can create new categories'],
            ['name' => 'edit-categories', 'display_name' => 'Edit Categories', 'description' => 'Can edit existing categories'],
            ['name' => 'delete-categories', 'display_name' => 'Delete Categories', 'description' => 'Can delete categories'],

            // Tag management permissions
            ['name' => 'view-tags', 'display_name' => 'View Tags', 'description' => 'Can view tags'],
            ['name' => 'create-tags', 'display_name' => 'Create Tags', 'description' => 'Can create new tags'],
            ['name' => 'edit-tags', 'display_name' => 'Edit Tags', 'description' => 'Can edit existing tags'],
            ['name' => 'delete-tags', 'display_name' => 'Delete Tags', 'description' => 'Can delete tags'],

            // Comment management permissions
            ['name' => 'view-comments', 'display_name' => 'View Comments', 'description' => 'Can view comments'],
            ['name' => 'create-comments', 'display_name' => 'Create Comments', 'description' => 'Can create new comments'],
            ['name' => 'edit-comments', 'display_name' => 'Edit Comments', 'description' => 'Can edit existing comments'],
            ['name' => 'delete-comments', 'display_name' => 'Delete Comments', 'description' => 'Can delete comments'],
            ['name' => 'moderate-comments', 'display_name' => 'Moderate Comments', 'description' => 'Can approve/reject comments'],

            // System permissions
            ['name' => 'access-admin', 'display_name' => 'Access Admin Panel', 'description' => 'Can access the admin panel'],
            ['name' => 'manage-settings', 'display_name' => 'Manage Settings', 'description' => 'Can manage system settings'],
        ];

        $permissionsCreated = 0;

        foreach ($permissions as $permissionData) {
            $permission = \Spatie\Permission\Models\Permission::firstOrCreate(
                ['name' => $permissionData['name']],
                [
                    'display_name' => $permissionData['display_name'],
                    'description' => $permissionData['description'],
                    'is_system' => true,
                ]
            );

            if ($permission->wasRecentlyCreated) {
                $permissionsCreated++;
            }
        }

        if ($permissionsCreated > 0) {
            $this->notify('success', "Successfully created {$permissionsCreated} system permission(s).");
        } else {
            $this->notify('info', 'All system permissions already exist.');
        }
    }
}
