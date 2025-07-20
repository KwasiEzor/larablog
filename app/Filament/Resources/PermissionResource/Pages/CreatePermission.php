<?php

namespace App\Filament\Resources\PermissionResource\Pages;

use App\Filament\Resources\PermissionResource;
use Filament\Resources\Pages\CreateRecord;
use Filament\Actions;

class CreatePermission extends CreateRecord
{
    protected static string $resource = PermissionResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        // Ensure the permission name is lowercase and slugified
        $data['name'] = strtolower($data['name']);

        // Set default values if not provided
        if (empty($data['display_name'])) {
            $data['display_name'] = ucwords(str_replace('-', ' ', $data['name']));
        }

        return $data;
    }

    protected function afterCreate(): void
    {
        $this->notify('success', 'Permission created successfully.');
    }
}
