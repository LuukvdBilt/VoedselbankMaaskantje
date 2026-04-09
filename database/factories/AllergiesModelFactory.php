<?php

namespace Database\Factories;

use App\Models\AllergiesModel;
use Illuminate\Database\Eloquent\Factories\Factory;

class AllergiesModelFactory extends Factory
{
    // Koppel deze factory expliciet aan het juiste Eloquent-model.
    protected $model = AllergiesModel::class;

    public function definition(): array
    {
        return [
            // Uniek zodat tests met unique-validatie niet willekeurig falen.
            'Name' => $this->faker->unique()->word(),
            // Korte beschrijving die lijkt op echte gebruikersinvoer.
            'Description' => $this->faker->sentence(),
            // Nieuwe records starten standaard als actief.
            'is_active' => true,
            // Optioneel veld: soms leeg, soms met inhoud voor realistische testdata.
            'note' => $this->faker->optional()->sentence(),
        ];
    }
}
