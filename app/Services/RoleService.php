<?php

namespace App\Services;

use App\Enums\RoleEnum;
use App\Models\User;
use Illuminate\Support\Collection;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleService
{
    /**
     * Get all roles with their statistics
     */
    public function getRolesWithStats(): Collection
    {
        return collect(RoleEnum::cases())->map(function ($roleEnum) {
            $role = $roleEnum->getRoleModel();
            return [
                'enum' => $roleEnum,
                'model' => $role,
                'user_count' => $role ? $role->users()->count() : 0,
                'permission_count' => $role ? $role->permissions()->count() : 0,
                'is_active' => $role !== null,
            ];
        });
    }

    /**
     * Get role statistics
     */
    public function getRoleStats(): array
    {
        $stats = [];

        foreach (RoleEnum::cases() as $roleEnum) {
            $stats[$roleEnum->value] = [
                'display_name' => $roleEnum->displayName(),
                'user_count' => $roleEnum->getUserCount(),
                'permission_count' => $roleEnum->getPermissions()->count(),
                'color' => $roleEnum->color(),
                'icon' => $roleEnum->icon(),
                'priority' => $roleEnum->priority(),
            ];
        }

        return $stats;
    }

    /**
     * Get users by role with pagination
     */
    public function getUsersByRole(RoleEnum $role, int $perPage = 15): Collection
    {
        return User::withRoleEnum($role)->paginate($perPage);
    }

    /**
     * Get all users grouped by role
     */
    public function getUsersGroupedByRole(): array
    {
        $grouped = [];

        foreach (RoleEnum::cases() as $roleEnum) {
            $grouped[$roleEnum->value] = [
                'role' => $roleEnum,
                'users' => $roleEnum->getUsers(),
                'count' => $roleEnum->getUserCount(),
            ];
        }

        return $grouped;
    }

    /**
     * Promote a user to the next role in hierarchy
     */
    public function promoteUser(User $user): bool
    {
        $currentRole = $user->getRoleEnum();
        if (!$currentRole) {
            return false;
        }

        $nextRole = $currentRole->nextRole();
        if (!$nextRole) {
            return false;
        }

        $user->syncRoleEnums([$nextRole]);
        return true;
    }

    /**
     * Demote a user to the previous role in hierarchy
     */
    public function demoteUser(User $user): bool
    {
        $currentRole = $user->getRoleEnum();
        if (!$currentRole) {
            return false;
        }

        $previousRole = $currentRole->previousRole();
        if (!$previousRole) {
            return false;
        }

        $user->syncRoleEnums([$previousRole]);
        return true;
    }

    /**
     * Check if a user can be promoted
     */
    public function canPromoteUser(User $user): bool
    {
        $currentRole = $user->getRoleEnum();
        return $currentRole && $currentRole->nextRole() !== null;
    }

    /**
     * Check if a user can be demoted
     */
    public function canDemoteUser(User $user): bool
    {
        $currentRole = $user->getRoleEnum();
        return $currentRole && $currentRole->previousRole() !== null;
    }

    /**
     * Get users that can be managed by a specific role
     */
    public function getManageableUsers(RoleEnum $managerRole): Collection
    {
        $manageableRoles = $managerRole->canManageRoles();

        if (empty($manageableRoles)) {
            return collect();
        }

        return User::withAnyRoleEnum(array_map(fn($role) => RoleEnum::fromValue($role), $manageableRoles))->get();
    }

    /**
     * Get role hierarchy as a tree
     */
    public function getRoleHierarchy(): array
    {
        $hierarchy = [];

        foreach (RoleEnum::cases() as $roleEnum) {
            $hierarchy[] = [
                'role' => $roleEnum,
                'level' => $roleEnum->priority(),
                'can_promote_to' => $roleEnum->nextRole(),
                'can_demote_to' => $roleEnum->previousRole(),
                'lower_roles' => $roleEnum->getLowerRoles(),
                'higher_roles' => $roleEnum->getHigherRoles(),
            ];
        }

        return $hierarchy;
    }

    /**
     * Validate role assignment
     */
    public function validateRoleAssignment(User $assigner, RoleEnum $roleToAssign): bool
    {
        $assignerRole = $assigner->getRoleEnum();
        if (!$assignerRole) {
            return false;
        }

        // Check if assigner can manage this role
        $manageableRoles = $assignerRole->canManageRoles();
        return in_array($roleToAssign->value, $manageableRoles);
    }

    /**
     * Get role comparison data
     */
    public function getRoleComparison(): array
    {
        $comparison = [];

        foreach (RoleEnum::cases() as $roleEnum) {
            $comparison[$roleEnum->value] = [
                'role' => $roleEnum,
                'permissions' => $roleEnum->getPermissions()->pluck('name')->toArray(),
                'capabilities' => [
                    'manage_users' => $roleEnum->canManageUsers(),
                    'manage_posts' => $roleEnum->canManagePosts(),
                    'manage_categories' => $roleEnum->canManageCategories(),
                    'manage_tags' => $roleEnum->canManageTags(),
                    'manage_comments' => $roleEnum->canManageComments(),
                    'manage_system' => $roleEnum->canManageSystem(),
                ],
            ];
        }

        return $comparison;
    }

    /**
     * Get role suggestions for a user based on their activity
     */
    public function getRoleSuggestions(User $user): array
    {
        $suggestions = [];
        $currentRole = $user->getRoleEnum();

        // Check post creation activity
        $postCount = $user->posts()->count();
        if ($postCount > 10 && $currentRole === RoleEnum::USER) {
            $suggestions[] = [
                'role' => RoleEnum::AUTHOR,
                'reason' => "User has created {$postCount} posts",
                'priority' => 'high',
            ];
        }

        // Check comment activity
        $commentCount = $user->comments()->count();
        if ($commentCount > 50 && $currentRole === RoleEnum::USER) {
            $suggestions[] = [
                'role' => RoleEnum::AUTHOR,
                'reason' => "User has made {$commentCount} comments",
                'priority' => 'medium',
            ];
        }

        // Check if user has been active for a long time
        $daysSinceRegistration = $user->created_at->diffInDays(now());
        if ($daysSinceRegistration > 30 && $currentRole === RoleEnum::USER) {
            $suggestions[] = [
                'role' => RoleEnum::AUTHOR,
                'reason' => "User has been active for {$daysSinceRegistration} days",
                'priority' => 'low',
            ];
        }

        return $suggestions;
    }

    /**
     * Get role audit log
     */
    public function getRoleAuditLog(User $user): array
    {
        // This would typically integrate with a logging system
        // For now, return basic information
        return [
            'current_role' => $user->getRoleEnum(),
            'role_history' => [], // Would be populated from audit logs
            'last_role_change' => null, // Would be populated from audit logs
        ];
    }
}
