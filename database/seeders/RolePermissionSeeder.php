<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $permissions = [
            'manage countries',
            'manage cities',
            'manage hotels',
            'manage hotel bookings',
            'checkout hotels',
            'view hotel bookings',
        ];

        foreach ($permissions as $perm) {
            Permission::firstOrCreate([
                'name' => $perm
            ]);
        }

        $customerRole = Role::firstOrCreate([
            'name' => 'customer'
        ]);

        $customerPermission = [
            'checkout hotels',
            'view hotel bookings',
        ];

        $customerRole->syncPermissions($customerPermission);

        $superAdminRole = Role::firstOrCreate([
            'name' => 'Super Admin'
        ]);

        $user = User::create([
            'name' => 'Super Admin',
            'email' => 'superadmin@gmail.com',
            'avatar' => 'images/dummyAvatar.png',
            'password' => bcrypt('Dandy123'),
        ]);

        $user->assignRole($superAdminRole);
    }
}
