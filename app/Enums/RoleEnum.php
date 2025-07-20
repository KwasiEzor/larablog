<?php

namespace App\Enums;

use Spatie\Permission\Models\Role;
use Illuminate\Support\Collection;

enum RoleEnum: string
{
    case ADMIN = 'admin';
    case AUTHOR = 'author';
    case USER = 'user';

    /**
     * Get all role values as an array
     */
    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }

    /**
     * Get all role names as an array
     */
    public static function names(): array
    {
        return array_column(self::cases(), 'name');
    }

    /**
     * Get all roles as an associative array
     */
    public static function toArray(): array
    {
        return array_combine(self::names(), self::values());
    }

    /**
     * Get all roles as a collection
     */
    public static function toCollection(): Collection
    {
        return collect(self::toArray());
    }

    /**
     * Get role by name
     */
    public static function fromName(string $name): ?self
    {
        foreach (self::cases() as $case) {
            if ($case->name === $name) {
                return $case;
            }
        }
        return null;
    }

    /**
     * Get role by value
     */
    public static function fromValue(string $value): ?self
    {
        return self::tryFrom($value);
    }

    /**
     * Check if a role exists by name
     */
    public static function hasName(string $name): bool
    {
        return self::fromName($name) !== null;
    }

    /**
     * Check if a role exists by value
     */
    public static function hasValue(string $value): bool
    {
        return self::fromValue($value) !== null;
    }

    /**
     * Get the display name for the role
     */
    public function displayName(): string
    {
        return match ($this) {
            self::ADMIN => 'Administrator',
            self::AUTHOR => 'Author',
            self::USER => 'User',
        };
    }

    /**
     * Get the description for the role
     */
    public function description(): string
    {
        return match ($this) {
            self::ADMIN => 'Full access to all features and system management',
            self::AUTHOR => 'Can create, edit, and manage posts and comments',
            self::USER => 'Basic access to view content and create comments',
        };
    }

    /**
     * Get the color/class for the role (useful for UI)
     */
    public function color(): string
    {
        return match ($this) {
            self::ADMIN => 'danger',
            self::AUTHOR => 'warning',
            self::USER => 'info',
        };
    }

    /**
     * Get the icon for the role (useful for UI)
     */
    public function icon(): string
    {
        return match ($this) {
            self::ADMIN => 'shield-check',
            self::AUTHOR => 'pencil',
            self::USER => 'user',
        };
    }

    /**
     * Get the priority level of the role (higher = more permissions)
     */
    public function priority(): int
    {
        return match ($this) {
            self::ADMIN => 3,
            self::AUTHOR => 2,
            self::USER => 1,
        };
    }

    /**
     * Check if this role has higher priority than another role
     */
    public function hasHigherPriorityThan(self $otherRole): bool
    {
        return $this->priority() > $otherRole->priority();
    }

    /**
     * Check if this role has lower priority than another role
     */
    public function hasLowerPriorityThan(self $otherRole): bool
    {
        return $this->priority() < $otherRole->priority();
    }

    /**
     * Check if this role has the same priority as another role
     */
    public function hasSamePriorityAs(self $otherRole): bool
    {
        return $this->priority() === $otherRole->priority();
    }

    /**
     * Get the Spatie Role model instance
     */
    public function getRoleModel(): ?Role
    {
        return Role::where('name', $this->value)->first();
    }

    /**
     * Get all permissions for this role
     */
    public function getPermissions(): Collection
    {
        $role = $this->getRoleModel();
        return $role ? $role->permissions : collect();
    }

    /**
     * Check if this role has a specific permission
     */
    public function hasPermission(string $permission): bool
    {
        $role = $this->getRoleModel();
        return $role ? $role->hasPermissionTo($permission) : false;
    }

    /**
     * Get all users with this role
     */
    public function getUsers(): Collection
    {
        $role = $this->getRoleModel();
        return $role ? $role->users : collect();
    }

    /**
     * Count users with this role
     */
    public function getUserCount(): int
    {
        return $this->getUsers()->count();
    }

    /**
     * Check if this is an admin role
     */
    public function isAdmin(): bool
    {
        return $this === self::ADMIN;
    }

    /**
     * Check if this is an author role
     */
    public function isAuthor(): bool
    {
        return $this === self::AUTHOR;
    }

    /**
     * Check if this is a user role
     */
    public function isUser(): bool
    {
        return $this === self::USER;
    }

    /**
     * Check if this role can manage users
     */
    public function canManageUsers(): bool
    {
        return $this->isAdmin();
    }

    /**
     * Check if this role can manage posts
     */
    public function canManagePosts(): bool
    {
        return $this->isAdmin() || $this->isAuthor();
    }

    /**
     * Check if this role can manage categories
     */
    public function canManageCategories(): bool
    {
        return $this->isAdmin();
    }

    /**
     * Check if this role can manage tags
     */
    public function canManageTags(): bool
    {
        return $this->isAdmin();
    }

    /**
     * Check if this role can manage comments
     */
    public function canManageComments(): bool
    {
        return $this->isAdmin() || $this->isAuthor();
    }

    /**
     * Check if this role can manage system settings
     */
    public function canManageSystem(): bool
    {
        return $this->isAdmin();
    }

    /**
     * Get roles that this role can manage
     */
    public function canManageRoles(): array
    {
        return match ($this) {
            self::ADMIN => self::values(),
            self::AUTHOR, self::USER => [],
        };
    }

    /**
     * Get the next role in hierarchy (for promotion)
     */
    public function nextRole(): ?self
    {
        return match ($this) {
            self::USER => self::AUTHOR,
            self::AUTHOR => self::ADMIN,
            self::ADMIN => null,
        };
    }

    /**
     * Get the previous role in hierarchy (for demotion)
     */
    public function previousRole(): ?self
    {
        return match ($this) {
            self::ADMIN => self::AUTHOR,
            self::AUTHOR => self::USER,
            self::USER => null,
        };
    }

    /**
     * Get all roles that are lower in hierarchy
     */
    public function getLowerRoles(): array
    {
        return array_filter(self::cases(), fn($role) => $role->hasLowerPriorityThan($this));
    }

    /**
     * Get all roles that are higher in hierarchy
     */
    public function getHigherRoles(): array
    {
        return array_filter(self::cases(), fn($role) => $role->hasHigherPriorityThan($this));
    }

    /**
     * Get roles at the same level (currently only one role per level)
     */
    public function getSameLevelRoles(): array
    {
        return array_filter(self::cases(), fn($role) => $role->hasSamePriorityAs($this));
    }



    /**
     * Get JSON representation
     */
    public function jsonSerialize(): mixed
    {
        return [
            'name' => $this->name,
            'value' => $this->value,
            'display_name' => $this->displayName(),
            'description' => $this->description(),
            'color' => $this->color(),
            'icon' => $this->icon(),
            'priority' => $this->priority(),
        ];
    }
}
