<?php

namespace App\Filament\Resources\RoleResource\Pages;

use App\Filament\Resources\RoleResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditRole extends EditRecord
{
    protected static string $resource = RoleResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make()
                ->before(function ($record) {
                    if ($record->is_system) {
                        throw new \Exception('System roles cannot be deleted.');
                    }
                    if ($record->users()->count() > 0) {
                        throw new \Exception('Cannot delete role that has assigned users.');
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
        // Ensure the role name is lowercase
        $data['name'] = strtolower($data['name']);

        return $data;
    }

    protected function afterSave(): void
    {
        $this->notify('success', 'Role updated successfully.');
    }
}
