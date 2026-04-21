<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class MedewerkerSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Maak het test account aan
        $admin = User::create([
            'name' => 'Test',
            'email' => 'admin@test.nl',
            'phone' => '0612345678',
            'password' => bcrypt('geheim123'),
            'specialiteit' => 'testing',
        ]);

        // 2. CRUCIAAL: Verander de rol van 'klant' (uit je model) naar 'medewerker'
        $admin->syncRoles(['medewerker']);

        // 3. Maak de overige medewerkers via de factory
        User::factory(5)->medewerker()->create()->each(function ($user) {
            // Ook hier: overschrijf de automatische klant-rol
            $user->syncRoles(['medewerker']);
        });
    }
}
