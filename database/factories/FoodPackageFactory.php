<?php

namespace Database\Factories;

use App\Models\FoodpackageModel;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<FoodpackageModel>
 */
class FoodPackageFactory extends Factory
{
    protected $model = FoodpackageModel::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $packages = [
            'Basispakket' => 'Standaard voedselpakket',
            'Gezinspakket' => 'Voor gezinnen',
            'Vegetarisch pakket' => 'Geen vlees',
            'Kindpakket' => 'Voor kinderen',
            'Senior pakket' => 'Voor ouderen',
        ];

        $name = $this->faker->unique()->randomElement(array_keys($packages));

        return [
            'Name' => $name,
            'Description' => $packages[$name],
            'is_active' => true,
            'note' => $this->faker->optional()->sentence(),
        ];
    }
}
