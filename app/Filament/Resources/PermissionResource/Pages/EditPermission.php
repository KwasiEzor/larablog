<?php

namespace App\Filament\Resources\PermissionResource\Pages;

use App\Filament\Resources\PermissionResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditPermission extends EditRecord
{
    protected static string $resource = PermissionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make()
                ->before(function ($record) {
                    if ($record->is_system) {
                        throw new \Exception('System permissions cannot be deleted.');
                    }
                    if ($record->roles()->count() > 0) {
                        throw new \Exception('Cannot delete permission that is assigned to roles.');
                    }
                }),
        ];
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        // Ensure the permission name is lowercase
        $data['name'] = strtolower($data['name']);

        return $data;
    }

    protected function afterSave(): void
    {
        $this->notify('success', 'Permission updated successfully.');
    }
}
