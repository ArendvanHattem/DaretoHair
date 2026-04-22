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
        // Maak de rollen
        Role::firstOrCreate(['name' => 'klant', 'guard_name' => 'web']);
        $role_medewerker = Role::firstOrCreate(['name' => 'medewerker', 'guard_name' => 'web']);

        // Maak de permissies
        $p1 = Permission::firstOrCreate(['name' => 'manage customers', 'guard_name' => 'web']);
        $p2 = Permission::firstOrCreate(['name' => 'manage employees', 'guard_name' => 'web']);
        $p3 = Permission::firstOrCreate(['name' => 'manage prices', 'guard_name' => 'web']);

        // Koppel permissies aan rol
        $role_medewerker->syncPermissions([$p1, $p2, $p3]);

        // Ken de medewerker rol toe aan gebruiker ID 2
        $user = User::find(2);
        if ($user) {
            $user->assignRole($role_medewerker);
        }
    }
}