<?php

namespace App\Traits;

use App\Enums\RoleEnum;
use Illuminate\Support\Collection;

trait HasRoleEnum
{
    /**
     * Get the user's primary role as RoleEnum
     */
    public function getRoleEnum(): ?RoleEnum
    {
        $role = $this->roles()->first();
        return $role ? RoleEnum::fromValue($role->name) : null;
    }

    /**
     * Get all user roles as RoleEnum collection
     */
    public function getRoleEnums(): Collection
    {
        return $this->roles()->get()->map(function ($role) {
            return RoleEnum::fromValue($role->name);
        })->filter();
    }

    /**
     * Check if user has a specific role
     */
    public function hasRoleEnum(RoleEnum $role): bool
    {
        return $this->hasRole($role->value);
    }

    /**
     * Check if user has any of the specified roles
     */
    public function hasAnyRoleEnum(array $roles): bool
    {
        return $this->hasAnyRole(array_map(fn($role) => $role->value, $roles));
    }

    /**
     * Check if user has all of the specified roles
     */
    public function hasAllRoleEnums(array $roles): bool
    {
        return $this->hasAllRoles(array_map(fn($role) => $role->value, $roles));
    }

    /**
     * Assign a role to the user
     */
    public function assignRoleEnum(RoleEnum $role): void
    {
        $this->assignRole($role->value);
    }

    /**
     * Remove a role from the user
     */
    public function removeRoleEnum(RoleEnum $role): void
    {
        $this->removeRole($role->value);
    }

    /**
     * Sync user roles
     */
    public function syncRoleEnums(array $roles): void
    {
        $this->syncRoles(array_map(fn($role) => $role->value, $roles));
    }

    /**
     * Check if user is admin
     */
    public function isAdmin(): bool
    {
        return $this->hasRoleEnum(RoleEnum::ADMIN);
    }

    /**
     * Check if user is author
     */
    public function isAuthor(): bool
    {
        return $this->hasRoleEnum(RoleEnum::AUTHOR);
    }

    /**
     * Check if user is regular user
     */
    public function isRegularUser(): bool
    {
        return $this->hasRoleEnum(RoleEnum::USER);
    }

    /**
     * Check if user has admin or author role
     */
    public function isAdminOrAuthor(): bool
    {
        return $this->hasAnyRoleEnum([RoleEnum::ADMIN, RoleEnum::AUTHOR]);
    }

    /**
     * Get user's role priority
     */
    public function getRolePriority(): int
    {
        $roleEnum = $this->getRoleEnum();
        return $roleEnum ? $roleEnum->priority() : 0;
    }

    /**
     * Check if user has higher priority than another user
     */
    public function hasHigherPriorityThan(self $otherUser): bool
    {
        return $this->getRolePriority() > $otherUser->getRolePriority();
    }

    /**
     * Check if user has lower priority than another user
     */
    public function hasLowerPriorityThan(self $otherUser): bool
    {
        return $this->getRolePriority() < $otherUser->getRolePriority();
    }

    /**
     * Check if user has same priority as another user
     */
    public function hasSamePriorityAs(self $otherUser): bool
    {
        return $this->getRolePriority() === $otherUser->getRolePriority();
    }

    /**
     * Check if user can manage users
     */
    public function canManageUsers(): bool
    {
        $roleEnum = $this->getRoleEnum();
        return $roleEnum ? $roleEnum->canManageUsers() : false;
    }

    /**
     * Check if user can manage posts
     */
    public function canManagePosts(): bool
    {
        $roleEnum = $this->getRoleEnum();
        return $roleEnum ? $roleEnum->canManagePosts() : false;
    }

    /**
     * Check if user can manage categories
     */
    public function canManageCategories(): bool
    {
        $roleEnum = $this->getRoleEnum();
        return $roleEnum ? $roleEnum->canManageCategories() : false;
    }

    /**
     * Check if user can manage tags
     */
    public function canManageTags(): bool
    {
        $roleEnum = $this->getRoleEnum();
        return $roleEnum ? $roleEnum->canManageTags() : false;
    }

    /**
     * Check if user can manage comments
     */
    public function canManageComments(): bool
    {
        $roleEnum = $this->getRoleEnum();
        return $roleEnum ? $roleEnum->canManageComments() : false;
    }

    /**
     * Check if user can manage system settings
     */
    public function canManageSystem(): bool
    {
        $roleEnum = $this->getRoleEnum();
        return $roleEnum ? $roleEnum->canManageSystem() : false;
    }

    /**
     * Get user's role display name
     */
    public function getRoleDisplayName(): string
    {
        $roleEnum = $this->getRoleEnum();
        return $roleEnum ? $roleEnum->displayName() : 'No Role';
    }

    /**
     * Get user's role description
     */
    public function getRoleDescription(): string
    {
        $roleEnum = $this->getRoleEnum();
        return $roleEnum ? $roleEnum->description() : 'No role assigned';
    }

    /**
     * Get user's role color
     */
    public function getRoleColor(): string
    {
        $roleEnum = $this->getRoleEnum();
        return $roleEnum ? $roleEnum->color() : 'secondary';
    }

    /**
     * Get user's role icon
     */
    public function getRoleIcon(): string
    {
        $roleEnum = $this->getRoleEnum();
        return $roleEnum ? $roleEnum->icon() : 'user';
    }

    /**
     * Scope to get users by role
     */
    public function scopeWithRoleEnum($query, RoleEnum $role)
    {
        return $query->role($role->value);
    }

    /**
     * Scope to get users with any of the specified roles
     */
    public function scopeWithAnyRoleEnum($query, array $roles)
    {
        return $query->whereHas('roles', function ($q) use ($roles) {
            $q->whereIn('name', array_map(fn($role) => $role->value, $roles));
        });
    }

    /**
     * Scope to get admin users
     */
    public function scopeAdmins($query)
    {
        return $query->withRoleEnum(RoleEnum::ADMIN);
    }

    /**
     * Scope to get author users
     */
    public function scopeAuthors($query)
    {
        return $query->withRoleEnum(RoleEnum::AUTHOR);
    }

    /**
     * Scope to get regular users
     */
    public function scopeRegularUsers($query)
    {
        return $query->withRoleEnum(RoleEnum::USER);
    }

    /**
     * Scope to get admin or author users
     */
    public function scopeAdminOrAuthors($query)
    {
        return $query->withAnyRoleEnum([RoleEnum::ADMIN, RoleEnum::AUTHOR]);
    }
}
