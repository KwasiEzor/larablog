<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Seed roles and permissions first
        $this->call([
            RoleAndPermissionSeeder::class,
        ]);

        // Create admin user
        $adminUser = User::factory()->create([
            'name' => 'Admin',
            'email' => 'admin@example.com',
            'password' => Hash::make('password'),
        ]);

        // Create author user
        $authorUser = User::factory()->create([
            'name' => 'John Author',
            'email' => 'author@example.com',
            'password' => Hash::make('password'),
        ]);

        // Create regular user
        $regularUser = User::factory()->create([
            'name' => 'Jane User',
            'email' => 'user@example.com',
            'password' => Hash::make('password'),
        ]);

        // Assign roles
        $adminRole = Role::where('name', 'admin')->first();
        $authorRole = Role::where('name', 'author')->first();
        $userRole = Role::where('name', 'user')->first();

        if ($adminRole) {
            $adminUser->assignRole($adminRole);
        }
        if ($authorRole) {
            $authorUser->assignRole($authorRole);
        }
        if ($userRole) {
            $regularUser->assignRole($userRole);
        }

        // Create additional users
        User::factory(7)->create()->each(function ($user) use ($userRole) {
            if ($userRole) {
                $user->assignRole($userRole);
            }
        });

        // Seed categories, tags, posts, and comments
        $this->call([
            CategorySeeder::class,
            TagSeeder::class,
            PostSeeder::class,
            CommentSeeder::class,
        ]);
    }
}
