<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class KlantSeeder extends Seeder
{
    public function run(): void
    {
        // Maak 10 klanten aan
        User::factory(5)->klant()->create();
    }
}
