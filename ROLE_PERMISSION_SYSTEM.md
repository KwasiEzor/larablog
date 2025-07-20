# Role and Permission System Documentation

## Overview

This Laravel blog application implements a comprehensive role-based access control (RBAC) system using Spatie's Laravel Permission package. The system provides granular control over user permissions and roles with a user-friendly admin interface.

## Features

### 🎯 Core Features
- **Role Management**: Create, edit, and manage user roles
- **Permission Management**: Define granular permissions for different actions
- **User Role Assignment**: Assign multiple roles to users
- **System vs Custom Roles**: Distinguish between system-managed and custom roles
- **Admin Interface**: Full Filament admin panel integration
- **Command Line Tools**: Manage roles and permissions via Artisan commands

### 🔐 Security Features
- **Hierarchical Roles**: Role-based permission inheritance
- **Permission Validation**: Server-side permission checks
- **System Protection**: Prevent deletion of system roles/permissions
- **User Validation**: Prevent removal of last system role from users

## System Roles

### 1. Administrator (`admin`)
- **Display Name**: Administrator
- **Description**: Full access to all features and system management
- **Permissions**: All permissions
- **Color**: Red (danger)
- **Icon**: shield-check

### 2. Author (`author`)
- **Display Name**: Author
- **Description**: Can create, edit, and manage posts and comments
- **Permissions**: 
  - Post management (view, create, edit, delete, publish)
  - Comment management (view, create, edit, delete, moderate)
  - Category and tag viewing
  - Admin panel access
- **Color**: Orange (warning)
- **Icon**: pencil

### 3. User (`user`)
- **Display Name**: User
- **Description**: Basic access to view content and create comments
- **Permissions**:
  - View posts, categories, and tags
  - Create and view comments
- **Color**: Blue (info)
- **Icon**: user

## System Permissions

### User Management
- `view-users` - View user list and details
- `create-users` - Create new users
- `edit-users` - Edit existing users
- `delete-users` - Delete users

### Role Management
- `view-roles` - View role list and details
- `create-roles` - Create new roles
- `edit-roles` - Edit existing roles
- `delete-roles` - Delete roles

### Post Management
- `view-posts` - View posts
- `create-posts` - Create new posts
- `edit-posts` - Edit existing posts
- `delete-posts` - Delete posts
- `publish-posts` - Publish posts

### Category Management
- `view-categories` - View categories
- `create-categories` - Create new categories
- `edit-categories` - Edit existing categories
- `delete-categories` - Delete categories

### Tag Management
- `view-tags` - View tags
- `create-tags` - Create new tags
- `edit-tags` - Edit existing tags
- `delete-tags` - Delete tags

### Comment Management
- `view-comments` - View comments
- `create-comments` - Create new comments
- `edit-comments` - Edit existing comments
- `delete-comments` - Delete comments
- `moderate-comments` - Approve/reject comments

### System Management
- `access-admin` - Access the admin panel
- `manage-settings` - Manage system settings

## Admin Interface

### Role Resource (`/admin/roles`)
- **List View**: Display all roles with user and permission counts
- **Create/Edit**: Form with role information and permission assignment
- **View Details**: Comprehensive role information display
- **Filters**: Filter by permissions, system status, and user assignment
- **Actions**: Create system roles, manage permissions

### Permission Resource (`/admin/permissions`)
- **List View**: Display all permissions with role assignment counts
- **Create/Edit**: Form with permission information and role assignment
- **View Details**: Comprehensive permission information display
- **Filters**: Filter by roles, system status, and assignment status
- **Actions**: Create system permissions

### User Resource (`/admin/users`)
- **Enhanced Role Management**: Improved role assignment interface
- **Role Information**: Real-time display of role capabilities
- **Relation Manager**: Dedicated roles management tab
- **Quick Actions**: Direct role management links

## Command Line Interface

### Artisan Commands

#### List Roles
```bash
php artisan roles:manage list-roles
```

#### List Permissions
```bash
php artisan roles:manage list-permissions
```

#### Assign Role to User
```bash
# By user ID
php artisan roles:manage assign-role --user=1 --role=admin

# By email
php artisan roles:manage assign-role --user=john@example.com --role=author
```

#### Remove Role from User
```bash
php artisan roles:manage remove-role --user=1 --role=user
```

#### Create Custom Role
```bash
php artisan roles:manage create-role \
    --name=moderator \
    --display-name="Content Moderator" \
    --description="Can moderate content and manage comments"
```

#### Create Custom Permission
```bash
php artisan roles:manage create-permission \
    --name=export-data \
    --display-name="Export Data" \
    --description="Can export system data"
```

## Code Usage

### Role Enum (`App\Enums\RoleEnum`)

```php
use App\Enums\RoleEnum;

// Get role by value
$adminRole = RoleEnum::ADMIN;

// Get display name
echo $adminRole->displayName(); // "Administrator"

// Get description
echo $adminRole->description(); // "Full access to all features..."

// Check role capabilities
if ($adminRole->canManageUsers()) {
    // Admin can manage users
}

// Get role priority
echo $adminRole->priority(); // 3
```

### User Model (`App\Models\User`)

```php
use App\Models\User;

$user = User::find(1);

// Check roles
if ($user->isAdmin()) {
    // User is admin
}

if ($user->hasRoleEnum(RoleEnum::AUTHOR)) {
    // User has author role
}

// Get role information
echo $user->getRoleDisplayName(); // "Administrator"
echo $user->getRoleDescription(); // "Full access to all features..."

// Check permissions
if ($user->canManagePosts()) {
    // User can manage posts
}

// Assign roles
$user->assignRoleEnum(RoleEnum::AUTHOR);

// Remove roles
$user->removeRoleEnum(RoleEnum::USER);
```

### Blade Templates

```blade
@if(auth()->user()->isAdmin())
    <div class="admin-panel">
        <!-- Admin only content -->
    </div>
@endif

@if(auth()->user()->canManagePosts())
    <a href="{{ route('posts.create') }}">Create Post</a>
@endif

@if(auth()->user()->hasRoleEnum(\App\Enums\RoleEnum::AUTHOR))
    <div class="author-tools">
        <!-- Author tools -->
    </div>
@endif
```

### Controllers

```php
use App\Enums\RoleEnum;

public function index()
{
    // Check permissions
    if (!auth()->user()->canManageUsers()) {
        abort(403, 'Unauthorized action.');
    }

    // Or use middleware
    $this->middleware('role:admin');
    
    return view('users.index');
}
```

## Database Schema

### Roles Table
```sql
CREATE TABLE roles (
    id BIGINT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(255) UNIQUE NOT NULL,
    display_name VARCHAR(255) NULL,
    description TEXT NULL,
    is_system BOOLEAN DEFAULT FALSE,
    parent_role_id BIGINT UNSIGNED NULL,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    FOREIGN KEY (parent_role_id) REFERENCES roles(id) ON DELETE SET NULL
);
```

### Permissions Table
```sql
CREATE TABLE permissions (
    id BIGINT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(255) UNIQUE NOT NULL,
    display_name VARCHAR(255) NULL,
    description TEXT NULL,
    is_system BOOLEAN DEFAULT FALSE,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL
);
```

## Widgets and Dashboard

### Role Permission Stats Widget
- **Total Users**: Count of all registered users
- **Total Roles**: Count of system and custom roles
- **Total Permissions**: Count of available permissions
- **Role Distribution**: Breakdown by role type
- **System vs Custom**: Distinction between system and custom items

## Best Practices

### 1. Role Design
- Keep roles focused on job functions, not individual permissions
- Use descriptive names and clear descriptions
- Consider role hierarchy for permission inheritance

### 2. Permission Design
- Use consistent naming conventions (e.g., `action-resource`)
- Group related permissions together
- Provide clear descriptions for each permission

### 3. Security
- Always validate permissions server-side
- Use middleware for route protection
- Regularly audit role assignments
- Protect system roles from deletion

### 4. Performance
- Cache role and permission queries when appropriate
- Use eager loading for related data
- Consider database indexing for frequently queried fields

## Migration and Setup

### 1. Run Migrations
```bash
php artisan migrate
```

### 2. Seed Roles and Permissions
```bash
php artisan db:seed --class=RoleAndPermissionSeeder
```

### 3. Assign Admin Role
```bash
php artisan roles:manage assign-role --user=1 --role=admin
```

## Troubleshooting

### Common Issues

1. **Permission Denied Errors**
   - Check if user has the required role/permission
   - Verify permission names match exactly
   - Clear permission cache: `php artisan permission:cache-reset`

2. **Role Not Found**
   - Ensure role exists in database
   - Check role name spelling
   - Verify role is assigned to user

3. **Admin Panel Access Issues**
   - Check `canAccessPanel()` method in User model
   - Verify user has admin or author role
   - Check Filament configuration

### Debug Commands
```bash
# Clear permission cache
php artisan permission:cache-reset

# List user roles
php artisan roles:manage list-roles

# Check specific user roles
php artisan tinker
>>> User::find(1)->roles->pluck('name')
```

## Future Enhancements

### Planned Features
- **Role Hierarchy**: Parent-child role relationships
- **Permission Groups**: Organize permissions into logical groups
- **Temporary Permissions**: Time-limited permission grants
- **Audit Logging**: Track role and permission changes
- **Bulk Operations**: Mass role/permission assignments
- **API Integration**: RESTful endpoints for role management

### Customization
- **Custom Role Types**: Extend role system for specific use cases
- **Permission Scopes**: Resource-specific permission scoping
- **Multi-tenancy**: Role system for multi-tenant applications
- **External Integration**: Connect with external identity providers

## Support

For issues or questions about the role and permission system:

1. Check the troubleshooting section above
2. Review the Laravel Permission package documentation
3. Examine the code examples in this documentation
4. Use the provided Artisan commands for debugging

## Contributing

When contributing to the role and permission system:

1. Follow the established naming conventions
2. Add appropriate tests for new functionality
3. Update this documentation for any changes
4. Ensure backward compatibility when possible
5. Test thoroughly with different role combinations 
