<?php

namespace Database\Seeders;

use App\Models\AllergiesModel;
use Illuminate\Database\Seeder;

class AllergiesSeeder extends Seeder
{
    public function run(): void
    {
        $allergies = [
            ['Name' => 'Gluten', 'Description' => 'Allergie voor glutenbevattende granen'],
            ['Name' => 'Lactose', 'Description' => 'Allergie of intolerantie voor melkproducten'],
            ['Name' => 'Noten', 'Description' => 'Allergie voor noten en pinda’s'],
            ['Name' => 'Soja', 'Description' => 'Allergie voor sojaproducten'],
            ['Name' => 'Ei', 'Description' => 'Allergie voor kippeneieren'],
            ['Name' => 'Vis', 'Description' => 'Allergie voor visproducten'],
            ['Name' => 'Schaaldieren', 'Description' => 'Allergie voor garnalen, krab, kreeft'],
        ];

        foreach ($allergies as $item) {
            AllergiesModel::query()->updateOrCreate(
                ['Name' => $item['Name']],
                [
                    'Description' => $item['Description'],
                    'is_active' => true,
                ]
            );
        }
    }
}
