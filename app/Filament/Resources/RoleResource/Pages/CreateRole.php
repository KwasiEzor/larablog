<?php

namespace App\Filament\Resources\RoleResource\Pages;

use App\Filament\Resources\RoleResource;
use Filament\Resources\Pages\CreateRecord;
use Filament\Actions;

class CreateRole extends CreateRecord
{
    protected static string $resource = RoleResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        // Ensure the role name is lowercase and slugified
        $data['name'] = strtolower($data['name']);

        // Set default values if not provided
        if (empty($data['display_name'])) {
            $data['display_name'] = ucfirst($data['name']);
        }

        return $data;
    }

    protected function afterCreate(): void
    {
        $this->notify('success', 'Role created successfully.');
    }
}
