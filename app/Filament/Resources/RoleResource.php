<?php

namespace App\Filament\Resources;

use App\Filament\Resources\RoleResource\Pages;
use App\Enums\RoleEnum;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
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

class RoleResource extends Resource
{
    protected static ?string $model = Role::class;

    protected static ?string $navigationIcon = 'heroicon-o-shield-check';

    protected static ?string $navigationGroup = 'User Management';

    protected static ?int $navigationSort = 2;

    protected static ?string $modelLabel = 'Role';

    protected static ?string $pluralModelLabel = 'Roles';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Role Information')
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                TextInput::make('name')
                                    ->required()
                                    ->maxLength(255)
                                    ->unique(ignoreRecord: true)
                                    ->helperText('Unique identifier for the role (e.g., admin, author, user)'),

                                TextInput::make('display_name')
                                    ->maxLength(255)
                                    ->helperText('Human-readable name for the role (e.g., Administrator, Author, User)'),
                            ]),

                        Textarea::make('description')
                            ->maxLength(500)
                            ->rows(3)
                            ->helperText('Brief description of what this role can do'),

                        Toggle::make('is_system')
                            ->label('System Role')
                            ->helperText('System roles cannot be deleted and are managed by the application')
                            ->default(false)
                            ->disabled(),
                    ]),

                Section::make('Permissions')
                    ->schema([
                        Select::make('permissions')
                            ->multiple()
                            ->relationship('permissions', 'name')
                            ->preload()
                            ->searchable()
                            ->options(function () {
                                return Permission::all()->pluck('name', 'id')->toArray();
                            })
                            ->helperText('Select the permissions that this role should have'),
                    ])
                    ->collapsible()
                    ->collapsed(),

                Section::make('Role Hierarchy')
                    ->schema([
                        Select::make('parent_role')
                            ->label('Parent Role')
                            ->options(function () {
                                return Role::where('id', '!=', request()->route('record'))->pluck('name', 'id')->toArray();
                            })
                            ->searchable()
                            ->helperText('Optional: Assign a parent role for hierarchical permissions'),
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
                    ->color(fn(string $state): string => match ($state) {
                        'admin' => 'danger',
                        'author' => 'warning',
                        'user' => 'info',
                        default => 'secondary',
                    }),

                TextColumn::make('display_name')
                    ->searchable()
                    ->sortable()
                    ->label('Display Name'),

                TextColumn::make('description')
                    ->limit(50)
                    ->searchable()
                    ->sortable(),

                TextColumn::make('permissions_count')
                    ->label('Permissions')
                    ->counts('permissions')
                    ->sortable(),

                TextColumn::make('users_count')
                    ->label('Users')
                    ->counts('users')
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

                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('permissions')
                    ->relationship('permissions', 'name')
                    ->multiple()
                    ->preload()
                    ->label('Filter by Permissions'),

                Filter::make('system_roles')
                    ->query(fn(Builder $query): Builder => $query->where('is_system', true))
                    ->toggle()
                    ->label('System Roles Only'),

                Filter::make('custom_roles')
                    ->query(fn(Builder $query): Builder => $query->where('is_system', false))
                    ->toggle()
                    ->label('Custom Roles Only'),

                Filter::make('has_users')
                    ->query(fn(Builder $query): Builder => $query->has('users'))
                    ->toggle()
                    ->label('Roles with Users'),

                Filter::make('no_users')
                    ->query(fn(Builder $query): Builder => $query->doesntHave('users'))
                    ->toggle()
                    ->label('Roles without Users'),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make()
                    ->before(function (Role $record) {
                        if ($record->is_system) {
                            throw new \Exception('System roles cannot be deleted.');
                        }
                        if ($record->users()->count() > 0) {
                            throw new \Exception('Cannot delete role that has assigned users.');
                        }
                    }),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()
                        ->before(function ($records) {
                            foreach ($records as $record) {
                                if ($record->is_system) {
                                    throw new \Exception('System roles cannot be deleted.');
                                }
                                if ($record->users()->count() > 0) {
                                    throw new \Exception('Cannot delete role that has assigned users.');
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
            'index' => Pages\ListRoles::route('/'),
            'create' => Pages\CreateRole::route('/create'),
            'view' => Pages\ViewRole::route('/{record}'),
            'edit' => Pages\EditRole::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->withCount(['permissions', 'users']);
    }

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }

    public static function getNavigationBadgeColor(): ?string
    {
        return 'primary';
    }
}
