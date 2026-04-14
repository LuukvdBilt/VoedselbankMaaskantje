<?php

namespace Database\Factories;

use App\Models\AllergiesModel;
use Illuminate\Database\Eloquent\Factories\Factory;

class AllergiesModelFactory extends Factory
{
    protected $model = AllergiesModel::class;

    public function definition(): array
    {
        return [
            'Name' => $this->faker->unique()->word(),
            'Description' => $this->faker->sentence(),
            'is_active' => true,
            'note' => $this->faker->optional()->sentence(),
        ];
    }
}
