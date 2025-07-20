<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PermissionResource\Pages;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\Filter;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Grid;

class PermissionResource extends Resource
{
    protected static ?string $model = Permission::class;

    protected static ?string $navigationIcon = 'heroicon-o-key';

    protected static ?string $navigationGroup = 'User Management';

    protected static ?int $navigationSort = 3;

    protected static ?string $modelLabel = 'Permission';

    protected static ?string $pluralModelLabel = 'Permissions';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Permission Information')
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                TextInput::make('name')
                                    ->required()
                                    ->maxLength(255)
                                    ->unique(ignoreRecord: true)
                                    ->helperText('Unique identifier for the permission (e.g., create-posts, edit-users)'),

                                TextInput::make('display_name')
                                    ->maxLength(255)
                                    ->helperText('Human-readable name for the permission (e.g., Create Posts, Edit Users)'),
                            ]),

                        Textarea::make('description')
                            ->maxLength(500)
                            ->rows(3)
                            ->helperText('Brief description of what this permission allows'),

                        Toggle::make('is_system')
                            ->label('System Permission')
                            ->helperText('System permissions cannot be deleted and are managed by the application')
                            ->default(false)
                            ->disabled(),
                    ]),

                Section::make('Role Assignment')
                    ->schema([
                        Select::make('roles')
                            ->multiple()
                            ->relationship('roles', 'name')
                            ->preload()
                            ->searchable()
                            ->options(function () {
                                return Role::all()->pluck('display_name', 'id')->toArray();
                            })
                            ->helperText('Select the roles that should have this permission'),
                    ])
                    ->collapsible()
                    ->collapsed(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->searchable()
                    ->sortable()
                    ->badge()
                    ->color('primary'),

                TextColumn::make('display_name')
                    ->searchable()
                    ->sortable()
                    ->label('Display Name'),

                TextColumn::make('description')
                    ->limit(50)
                    ->searchable()
                    ->sortable(),

                TextColumn::make('roles_count')
                    ->label('Roles')
                    ->counts('roles')
                    ->sortable(),

                IconColumn::make('is_system')
                    ->label('System Permission')
                    ->boolean()
                    ->trueIcon('heroicon-o-shield-check')
                    ->falseIcon('heroicon-o-key')
                    ->trueColor('success')
                    ->falseColor('gray'),

                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('roles')
                    ->relationship('roles', 'name')
                    ->multiple()
                    ->preload()
                    ->label('Filter by Roles'),

                Filter::make('system_permissions')
                    ->query(fn(Builder $query): Builder => $query->where('is_system', true))
                    ->toggle()
                    ->label('System Permissions Only'),

                Filter::make('custom_permissions')
                    ->query(fn(Builder $query): Builder => $query->where('is_system', false))
                    ->toggle()
                    ->label('Custom Permissions Only'),

                Filter::make('assigned_to_roles')
                    ->query(fn(Builder $query): Builder => $query->has('roles'))
                    ->toggle()
                    ->label('Assigned to Roles'),

                Filter::make('unassigned')
                    ->query(fn(Builder $query): Builder => $query->doesntHave('roles'))
                    ->toggle()
                    ->label('Unassigned Permissions'),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make()
                    ->before(function (Permission $record) {
                        if ($record->is_system) {
                            throw new \Exception('System permissions cannot be deleted.');
                        }
                        if ($record->roles()->count() > 0) {
                            throw new \Exception('Cannot delete permission that is assigned to roles.');
                        }
                    }),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()
                        ->before(function ($records) {
                            foreach ($records as $record) {
                                if ($record->is_system) {
                                    throw new \Exception('System permissions cannot be deleted.');
                                }
                                if ($record->roles()->count() > 0) {
                                    throw new \Exception('Cannot delete permission that is assigned to roles.');
                                }
                            }
                        }),
                ]),
            ])
            ->defaultSort('created_at', 'desc');
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPermissions::route('/'),
            'create' => Pages\CreatePermission::route('/create'),
            'view' => Pages\ViewPermission::route('/{record}'),
            'edit' => Pages\EditPermission::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->withCount(['roles']);
    }

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }

    public static function getNavigationBadgeColor(): ?string
    {
        return 'warning';
    }
}
