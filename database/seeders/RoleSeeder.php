<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $role_klant = Role::create(['name' => 'klant']);

        $role_medewerker = Role::create(['name' => 'medewerker']);
        $permission_manage_customers = Permission::create(['name' => 'manage customers']);
        $permission_manage_employees = Permission::create(['name' => 'manage employees']);
        $permission_manage_prices = Permission::create(['name' => 'manage prices']);

        $role_medewerker->givePermissionTo($permission_manage_customers, $permission_manage_employees, $permission_manage_prices);

        $user = User::find(12);

        $user->assignRole($role_medewerker);
    }
}
