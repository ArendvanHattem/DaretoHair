<?php

namespace Database\Seeders;

use App\Models\pricelist;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;


class PricelistSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Specifieke diensten toevoegen
        Pricelist::create([
            'service' => 'Wassen & Knippen (Heren)',
            'description' => 'Inclusief hoofdhuidmassage en styling.',
            'amount' => 28.50,
            'category' => 'knippen & stylen',
        ]);

        Pricelist::create([
            'service' => 'Wassen, Knippen & Föhnen (Dames)',
            'description' => 'Complete behandeling voor dames.',
            'amount' => 45.00,
            'category' => 'knippen & stylen',
        ]);

        Pricelist::create([
            'service' => 'Volledige kleuring',
            'description' => 'Permanente kleuring van het hele haar.',
            'amount' => 65.00,
            'category' => 'Kleuren',
        ]);
    }
}
