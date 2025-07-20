<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;
use App\Enums\RoleEnum;

class ManageRolesAndPermissions extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'roles:manage
                            {action : The action to perform (list-roles, list-permissions, assign-role, remove-role, create-role, create-permission)}
                            {--user= : User ID or email for role assignment}
                            {--role= : Role name for assignment}
                            {--permission= : Permission name for assignment}
                            {--name= : Name for new role/permission}
                            {--display-name= : Display name for new role/permission}
                            {--description= : Description for new role/permission}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Manage roles and permissions for the application';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $action = $this->argument('action');

        switch ($action) {
            case 'list-roles':
                $this->listRoles();
                break;
            case 'list-permissions':
                $this->listPermissions();
                break;
            case 'assign-role':
                $this->assignRole();
                break;
            case 'remove-role':
                $this->removeRole();
                break;
            case 'create-role':
                $this->createRole();
                break;
            case 'create-permission':
                $this->createPermission();
                break;
            default:
                $this->error("Unknown action: {$action}");
                $this->showHelp();
                return 1;
        }

        return 0;
    }

    protected function listRoles()
    {
        $this->info('Available Roles:');
        $this->newLine();

        $roles = Role::withCount(['users', 'permissions'])->get();

        $headers = ['ID', 'Name', 'Display Name', 'Description', 'System', 'Users', 'Permissions'];
        $rows = [];

        foreach ($roles as $role) {
            $rows[] = [
                $role->id,
                $role->name,
                $role->display_name ?? 'N/A',
                $role->description ?? 'N/A',
                $role->is_system ? 'Yes' : 'No',
                $role->users_count,
                $role->permissions_count,
            ];
        }

        $this->table($headers, $rows);
    }

    protected function listPermissions()
    {
        $this->info('Available Permissions:');
        $this->newLine();

        $permissions = Permission::withCount('roles')->get();

        $headers = ['ID', 'Name', 'Display Name', 'Description', 'System', 'Roles'];
        $rows = [];

        foreach ($permissions as $permission) {
            $rows[] = [
                $permission->id,
                $permission->name,
                $permission->display_name ?? 'N/A',
                $permission->description ?? 'N/A',
                $permission->is_system ? 'Yes' : 'No',
                $permission->roles_count,
            ];
        }

        $this->table($headers, $rows);
    }

    protected function assignRole()
    {
        $userIdentifier = $this->option('user');
        $roleName = $this->option('role');

        if (!$userIdentifier || !$roleName) {
            $this->error('Both --user and --role options are required.');
            return 1;
        }

        // Find user by ID or email
        $user = User::where('id', $userIdentifier)
            ->orWhere('email', $userIdentifier)
            ->first();

        if (!$user) {
            $this->error("User not found: {$userIdentifier}");
            return 1;
        }

        $role = Role::where('name', $roleName)->first();

        if (!$role) {
            $this->error("Role not found: {$roleName}");
            return 1;
        }

        if ($user->hasRole($role)) {
            $this->warn("User {$user->name} already has role {$role->name}");
            return 0;
        }

        $user->assignRole($role);
        $this->info("Successfully assigned role '{$role->name}' to user '{$user->name}'");
    }

    protected function removeRole()
    {
        $userIdentifier = $this->option('user');
        $roleName = $this->option('role');

        if (!$userIdentifier || !$roleName) {
            $this->error('Both --user and --role options are required.');
            return 1;
        }

        // Find user by ID or email
        $user = User::where('id', $userIdentifier)
            ->orWhere('email', $userIdentifier)
            ->first();

        if (!$user) {
            $this->error("User not found: {$userIdentifier}");
            return 1;
        }

        $role = Role::where('name', $roleName)->first();

        if (!$role) {
            $this->error("Role not found: {$roleName}");
            return 1;
        }

        if (!$user->hasRole($role)) {
            $this->warn("User {$user->name} does not have role {$role->name}");
            return 0;
        }

        $user->removeRole($role);
        $this->info("Successfully removed role '{$role->name}' from user '{$user->name}'");
    }

    protected function createRole()
    {
        $name = $this->option('name');
        $displayName = $this->option('display-name');
        $description = $this->option('description');

        if (!$name) {
            $this->error('--name option is required for creating a role.');
            return 1;
        }

        if (Role::where('name', $name)->exists()) {
            $this->error("Role '{$name}' already exists.");
            return 1;
        }

        $role = Role::create([
            'name' => strtolower($name),
            'display_name' => $displayName ?? ucwords(str_replace('-', ' ', $name)),
            'description' => $description ?? "Custom role: {$name}",
            'is_system' => false,
        ]);

        $this->info("Successfully created role '{$role->name}'");
    }

    protected function createPermission()
    {
        $name = $this->option('name');
        $displayName = $this->option('display-name');
        $description = $this->option('description');

        if (!$name) {
            $this->error('--name option is required for creating a permission.');
            return 1;
        }

        if (Permission::where('name', $name)->exists()) {
            $this->error("Permission '{$name}' already exists.");
            return 1;
        }

        $permission = Permission::create([
            'name' => strtolower($name),
            'display_name' => $displayName ?? ucwords(str_replace('-', ' ', $name)),
            'description' => $description ?? "Custom permission: {$name}",
            'is_system' => false,
        ]);

        $this->info("Successfully created permission '{$permission->name}'");
    }

    protected function showHelp()
    {
        $this->info('Available actions:');
        $this->line('  list-roles                    - List all roles');
        $this->line('  list-permissions              - List all permissions');
        $this->line('  assign-role --user=ID --role=name - Assign role to user');
        $this->line('  remove-role --user=ID --role=name - Remove role from user');
        $this->line('  create-role --name=name [--display-name=name] [--description=desc] - Create new role');
        $this->line('  create-permission --name=name [--display-name=name] [--description=desc] - Create new permission');
        $this->newLine();
        $this->info('Examples:');
        $this->line('  php artisan roles:manage list-roles');
        $this->line('  php artisan roles:manage assign-role --user=1 --role=admin');
        $this->line('  php artisan roles:manage assign-role --user=john@example.com --role=author');
        $this->line('  php artisan roles:manage create-role --name=moderator --display-name="Content Moderator"');
    }
}
