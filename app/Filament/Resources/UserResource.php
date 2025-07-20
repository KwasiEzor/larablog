<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
use App\Filament\Resources\UserResource\RelationManagers;
use App\Models\User;
use App\Enums\RoleEnum;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Hash;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\Filter;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-users';

    protected static ?string $navigationGroup = 'User Management';

    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Basic Information')
                    ->schema([
                        TextInput::make('name')
                            ->required()
                            ->maxLength(255),
                        TextInput::make('email')
                            ->email()
                            ->required()
                            ->maxLength(255)
                            ->unique(ignoreRecord: true),
                        TextInput::make('password')
                            ->password()
                            ->dehydrateStateUsing(fn($state) => Hash::make($state))
                            ->dehydrated(fn($state) => filled($state))
                            ->required(fn(string $context): bool => $context === 'create'),
                        TextInput::make('password_confirmation')
                            ->password()
                            ->dehydrated(false)
                            ->required(fn(string $context): bool => $context === 'create')
                            ->same('password'),
                    ])->columns(2),

                Forms\Components\Section::make('Role & Permissions')
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

                        Forms\Components\Placeholder::make('role_info')
                            ->label('Role Information')
                            ->content(function ($get) {
                                $roles = $get('roles');
                                if (empty($roles)) {
                                    return 'No roles selected. User will have basic access only.';
                                }

                                $roleInfo = [];
                                foreach ($roles as $roleName) {
                                    $roleEnum = \App\Enums\RoleEnum::fromValue($roleName);
                                    if ($roleEnum) {
                                        $roleInfo[] = "• {$roleEnum->displayName()}: {$roleEnum->description()}";
                                    }
                                }

                                return implode("\n", $roleInfo);
                            })
                            ->visible(fn($get) => !empty($get('roles'))),
                    ]),

                Forms\Components\Section::make('Email Verification')
                    ->schema([
                        Toggle::make('email_verified_at')
                            ->label('Email Verified')
                            ->dehydrateStateUsing(fn($state) => $state ? now() : null)
                            ->dehydrated(fn($state) => filled($state)),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('profile_photo_url')
                    ->label('Avatar')
                    ->circular()
                    ->defaultImageUrl(fn(User $record): string => "https://ui-avatars.com/api/?name={$record->name}&color=7C3AED&background=EBF4FF"),
                TextColumn::make('name')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('email')
                    ->searchable()
                    ->sortable(),
                BadgeColumn::make('roles.name')
                    ->label('Roles')
                    ->colors([
                        'danger' => 'admin',
                        'warning' => 'author',
                        'info' => 'user',
                    ]),
                TextColumn::make('email_verified_at')
                    ->label('Email Verified')
                    ->dateTime()
                    ->sortable()
                    ->badge()
                    ->color(fn($state) => $state ? 'success' : 'danger')
                    ->formatStateUsing(fn($state) => $state ? 'Verified' : 'Not Verified'),
                TextColumn::make('posts_count')
                    ->label('Posts')
                    ->counts('posts')
                    ->sortable(),
                TextColumn::make('comments_count')
                    ->label('Comments')
                    ->counts('comments')
                    ->sortable(),
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
                    ->preload(),
                Filter::make('email_verified')
                    ->query(fn(Builder $query): Builder => $query->whereNotNull('email_verified_at'))
                    ->toggle(),
                Filter::make('email_not_verified')
                    ->query(fn(Builder $query): Builder => $query->whereNull('email_verified_at'))
                    ->toggle(),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\Action::make('manage_roles')
                    ->label('Manage Roles')
                    ->icon('heroicon-o-shield-check')
                    ->color('warning')
                    ->url(fn(User $record): string => route('filament.admin.resources.users.edit', ['record' => $record, 'activeTab' => 'roles']))
                    ->openUrlInNewTab(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('created_at', 'desc');
    }

    public static function getRelations(): array
    {
        return [
            RelationManagers\RolesRelationManager::class,
            RelationManagers\PostsRelationManager::class,
            RelationManagers\CommentsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->withCount(['posts', 'comments']);
    }
}
