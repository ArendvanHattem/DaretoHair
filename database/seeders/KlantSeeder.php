<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class KlantSeeder extends Seeder
{
    public function run(): void
    {


        User::factory(5)->klant()->create()->each(function ($user) {
            $user->syncRoles(['klant']);
        });
    }
}
