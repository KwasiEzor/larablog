<?php

namespace App\Filament\Resources\PermissionResource\Pages;

use App\Filament\Resources\PermissionResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;
use Filament\Infolists;
use Filament\Infolists\Infolist;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Components\Grid;
use Filament\Infolists\Components\IconEntry;

class ViewPermission extends ViewRecord
{
    protected static string $resource = PermissionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }

    public function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Section::make('Permission Information')
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                TextEntry::make('name')
                                    ->label('Permission Name')
                                    ->badge()
                                    ->color('primary'),

                                TextEntry::make('display_name')
                                    ->label('Display Name'),
                            ]),

                        TextEntry::make('description')
                            ->label('Description')
                            ->markdown()
                            ->columnSpanFull(),

                        IconEntry::make('is_system')
                            ->label('System Permission')
                            ->boolean()
                            ->trueIcon('heroicon-o-shield-check')
                            ->falseIcon('heroicon-o-key')
                            ->trueColor('success')
                            ->falseColor('gray'),
                    ]),

                Section::make('Roles with this Permission')
                    ->schema([
                        TextEntry::make('roles.name')
                            ->label('Assigned Roles')
                            ->listWithLineBreaks()
                            ->bulleted()
                            ->placeholder('No roles assigned to this permission'),
                    ])
                    ->collapsible(),

                Section::make('Timestamps')
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                TextEntry::make('created_at')
                                    ->label('Created At')
                                    ->dateTime(),

                                TextEntry::make('updated_at')
                                    ->label('Updated At')
                                    ->dateTime(),
                            ]),
                    ])
                    ->collapsible()
                    ->collapsed(),
            ]);
    }
}
