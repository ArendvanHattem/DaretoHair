<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        // ✅ Always include guard_name (prevents subtle bugs)
        $role_klant = Role::firstOrCreate([
            'name' => 'klant',
            'guard_name' => 'web',
        ]);

        $role_medewerker = Role::firstOrCreate([
            'name' => 'medewerker',
            'guard_name' => 'web',
        ]);

        // ✅ Same for permissions
        $permission_manage_customers = Permission::firstOrCreate([
            'name' => 'manage customers',
            'guard_name' => 'web',
        ]);

        $permission_manage_employees = Permission::firstOrCreate([
            'name' => 'manage employees',
            'guard_name' => 'web',
        ]);

        $permission_manage_prices = Permission::firstOrCreate([
            'name' => 'manage prices',
            'guard_name' => 'web',
        ]);

        // ✅ Sync permissions (better than stacking duplicates)
        $role_medewerker->syncPermissions([
            $permission_manage_customers,
            $permission_manage_employees,
            $permission_manage_prices,
        ]);

        // ✅ Assign role to user safely
        if ($user = User::find(1)) {
            $user->syncRoles([$role_medewerker]);

            // ❗ Optional: keep your column in sync (temporary fix)
            $user->update([
                'role' => 'medewerker',
            ]);
        }
    }
}
