<?php

namespace App\Filament\Resources\UserResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Enums\RoleEnum;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Section;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Columns\IconColumn;

class RolesRelationManager extends RelationManager
{
    protected static string $relationship = 'roles';

    protected static ?string $recordTitleAttribute = 'name';

    protected static ?string $title = 'User Roles';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Role Assignment')
                    ->schema([
                        Select::make('roles')
                            ->multiple()
                            ->relationship('roles', 'name')
                            ->preload()
                            ->searchable()
                            ->options(function () {
                                return \Spatie\Permission\Models\Role::all()->pluck('display_name', 'name')->toArray();
                            })
                            ->helperText('Select the roles for this user. Users can have multiple roles.')
                            ->reactive()
                            ->afterStateUpdated(function ($state, callable $set) {
                                // You can add custom logic here when roles change
                            }),
                    ])
                    ->columns(1),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('name')
            ->columns([
                TextColumn::make('name')
                    ->label('Role Name')
                    ->searchable()
                    ->sortable()
                    ->badge()
                    ->color(fn(string $state): string => match ($state) {
                        'admin' => 'danger',
                        'author' => 'warning',
                        'user' => 'info',
                        default => 'secondary',
                    }),

                TextColumn::make('display_name')
                    ->label('Display Name')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('description')
                    ->label('Description')
                    ->limit(50)
                    ->searchable()
                    ->sortable(),

                TextColumn::make('permissions_count')
                    ->label('Permissions')
                    ->counts('permissions')
                    ->sortable(),

                IconColumn::make('is_system')
                    ->label('System Role')
                    ->boolean()
                    ->trueIcon('heroicon-o-shield-check')
                    ->falseIcon('heroicon-o-user')
                    ->trueColor('success')
                    ->falseColor('gray'),

                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('permissions')
                    ->relationship('permissions', 'name')
                    ->multiple()
                    ->preload()
                    ->label('Filter by Permissions'),

                Tables\Filters\TernaryFilter::make('is_system')
                    ->label('System Roles Only')
                    ->placeholder('All Roles')
                    ->trueLabel('System Roles')
                    ->falseLabel('Custom Roles'),
            ])
            ->headerActions([
                Tables\Actions\AttachAction::make()
                    ->preloadRecordSelect()
                    ->recordSelectOptionsQuery(fn(Builder $query) => $query->orderBy('name'))
                    ->recordSelectLabel('display_name')
                    ->recordSelectSearchColumns(['name', 'display_name'])
                    ->form(fn(Tables\Actions\AttachAction $action): array => [
                        $action->getRecordSelect()
                            ->label('Select Role')
                            ->helperText('Choose a role to assign to this user'),
                    ]),
            ])
            ->actions([
                Tables\Actions\DetachAction::make()
                    ->before(function ($record) {
                        if ($record->is_system && $this->getOwnerRecord()->roles()->count() === 1) {
                            throw new \Exception('Cannot remove the last system role from a user.');
                        }
                    }),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DetachBulkAction::make()
                        ->before(function ($records) {
                            foreach ($records as $record) {
                                if ($record->is_system && $this->getOwnerRecord()->roles()->count() === 1) {
                                    throw new \Exception('Cannot remove the last system role from a user.');
                                }
                            }
                        }),
                ]),
            ])
            ->defaultSort('name', 'asc');
    }
}
