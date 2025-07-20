<?php

namespace App\Filament\Resources\RoleResource\Pages;

use App\Filament\Resources\RoleResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;
use Filament\Infolists;
use Filament\Infolists\Infolist;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Components\Grid;
use Filament\Infolists\Components\IconEntry;

class ViewRole extends ViewRecord
{
    protected static string $resource = RoleResource::class;

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
                Section::make('Role Information')
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                TextEntry::make('name')
                                    ->label('Role Name')
                                    ->badge()
                                    ->color(fn(string $state): string => match ($state) {
                                        'admin' => 'danger',
                                        'author' => 'warning',
                                        'user' => 'info',
                                        default => 'secondary',
                                    }),

                                TextEntry::make('display_name')
                                    ->label('Display Name'),
                            ]),

                        TextEntry::make('description')
                            ->label('Description')
                            ->markdown()
                            ->columnSpanFull(),

                        IconEntry::make('is_system')
                            ->label('System Role')
                            ->boolean()
                            ->trueIcon('heroicon-o-shield-check')
                            ->falseIcon('heroicon-o-user')
                            ->trueColor('success')
                            ->falseColor('gray'),
                    ]),

                Section::make('Permissions')
                    ->schema([
                        TextEntry::make('permissions.name')
                            ->label('Assigned Permissions')
                            ->listWithLineBreaks()
                            ->bulleted()
                            ->placeholder('No permissions assigned'),
                    ])
                    ->collapsible(),

                Section::make('Users with this Role')
                    ->schema([
                        TextEntry::make('users.name')
                            ->label('Users')
                            ->listWithLineBreaks()
                            ->bulleted()
                            ->placeholder('No users assigned to this role'),
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
