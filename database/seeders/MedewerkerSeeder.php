<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class MedewerkerSeeder extends Seeder
{
    public function run(): void
    {
        // Maak 5 medewerkers aan met de medewerker-state uit de factory
        User::factory(5)->medewerker()->create()->each(function ($user) {
            // Spatie rol toewijzen en eventuele standaard 'klant' rol overschrijven
            $user->syncRoles(['medewerker']);
        });
    }
}
