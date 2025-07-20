<?php

namespace App\Http\Controllers;

use App\Enums\RoleEnum;
use App\Models\User;
use App\Services\RoleService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Auth;

class RoleController extends BaseController
{
    public function __construct(
        private RoleService $roleService
    ) {
        $this->middleware('auth');
        $this->middleware('can:manage roles');
    }

    /**
     * Display role management dashboard
     */
    public function index(): View
    {
        $roleStats = $this->roleService->getRoleStats();
        $roleHierarchy = $this->roleService->getRoleHierarchy();
        $usersGroupedByRole = $this->roleService->getUsersGroupedByRole();

        return view('roles.index', compact('roleStats', 'roleHierarchy', 'usersGroupedByRole'));
    }

    /**
     * Get role statistics as JSON
     */
    public function stats(): JsonResponse
    {
        $stats = $this->roleService->getRoleStats();

        return response()->json([
            'success' => true,
            'data' => $stats,
        ]);
    }

    /**
     * Get users by role
     */
    public function usersByRole(Request $request): JsonResponse
    {
        $request->validate([
            'role' => 'required|string|in:' . implode(',', RoleEnum::values()),
        ]);

        $role = RoleEnum::fromValue($request->role);
        $users = $this->roleService->getUsersByRole($role, $request->get('per_page', 15));

        return response()->json([
            'success' => true,
            'data' => $users,
        ]);
    }

    /**
     * Assign role to user
     */
    public function assignRole(Request $request): JsonResponse
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'role' => 'required|string|in:' . implode(',', RoleEnum::values()),
        ]);

        $user = User::findOrFail($request->user_id);
        $role = RoleEnum::fromValue($request->role);

        // Validate if current user can assign this role
        if (!$this->roleService->validateRoleAssignment(Auth::user(), $role)) {
            return response()->json([
                'success' => false,
                'message' => 'You do not have permission to assign this role.',
            ], 403);
        }

        $user->syncRoleEnums([$role]);

        return response()->json([
            'success' => true,
            'message' => "Role {$role->displayName()} assigned successfully to {$user->name}",
        ]);
    }

    /**
     * Promote user to next role
     */
    public function promoteUser(Request $request): JsonResponse
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
        ]);

        $user = User::findOrFail($request->user_id);

        if (!$this->roleService->canPromoteUser($user)) {
            return response()->json([
                'success' => false,
                'message' => 'User cannot be promoted further.',
            ], 400);
        }

        $success = $this->roleService->promoteUser($user);

        if ($success) {
            $newRole = $user->getRoleEnum();
            return response()->json([
                'success' => true,
                'message' => "User promoted to {$newRole->displayName()}",
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Failed to promote user.',
        ], 500);
    }

    /**
     * Demote user to previous role
     */
    public function demoteUser(Request $request): JsonResponse
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
        ]);

        $user = User::findOrFail($request->user_id);

        if (!$this->roleService->canDemoteUser($user)) {
            return response()->json([
                'success' => false,
                'message' => 'User cannot be demoted further.',
            ], 400);
        }

        $success = $this->roleService->demoteUser($user);

        if ($success) {
            $newRole = $user->getRoleEnum();
            return response()->json([
                'success' => true,
                'message' => "User demoted to {$newRole->displayName()}",
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Failed to demote user.',
        ], 500);
    }

    /**
     * Get role comparison
     */
    public function comparison(): JsonResponse
    {
        $comparison = $this->roleService->getRoleComparison();

        return response()->json([
            'success' => true,
            'data' => $comparison,
        ]);
    }

    /**
     * Get role suggestions for a user
     */
    public function suggestions(Request $request): JsonResponse
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
        ]);

        $user = User::findOrFail($request->user_id);
        $suggestions = $this->roleService->getRoleSuggestions($user);

        return response()->json([
            'success' => true,
            'data' => $suggestions,
        ]);
    }

    /**
     * Get all available roles
     */
    public function available(): JsonResponse
    {
        $roles = collect(RoleEnum::cases())->map(function ($role) {
            return [
                'name' => $role->name,
                'value' => $role->value,
                'display_name' => $role->displayName(),
                'description' => $role->description(),
                'color' => $role->color(),
                'icon' => $role->icon(),
                'priority' => $role->priority(),
            ];
        });

        return response()->json([
            'success' => true,
            'data' => $roles,
        ]);
    }

    /**
     * Get role hierarchy
     */
    public function hierarchy(): JsonResponse
    {
        $hierarchy = $this->roleService->getRoleHierarchy();

        return response()->json([
            'success' => true,
            'data' => $hierarchy,
        ]);
    }

    /**
     * Get manageable users for current user
     */
    public function manageableUsers(): JsonResponse
    {
        $currentUser = Auth::user();
        $currentRole = $currentUser->getRoleEnum();

        if (!$currentRole) {
            return response()->json([
                'success' => false,
                'message' => 'User has no role assigned.',
            ], 400);
        }

        $manageableUsers = $this->roleService->getManageableUsers($currentRole);

        return response()->json([
            'success' => true,
            'data' => $manageableUsers,
        ]);
    }
}
