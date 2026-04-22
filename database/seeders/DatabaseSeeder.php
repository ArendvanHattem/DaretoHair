<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            RoleSeeder::class,       // 1. Maak de rollen en permissies aan
            MedewerkerSeeder::class, // 2. Maak de medewerkers (en koppel de rol)
            KlantSeeder::class,      // 3. Maak de klanten
            PricelistSeeder::class,
        ]);
    }
}
