<?php

namespace Database\Factories;

use App\Models\AllergiesModel;
use App\Models\FoodPackageAllergyModel;
use App\Models\FoodpackageModel;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<FoodPackageAllergyModel>
 */
class FoodPackageAllergyFactory extends Factory
{
    protected $model = FoodPackageAllergyModel::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'FoodPackageId' => FoodpackageModel::query()->inRandomOrder()->value('Id') ?? FoodpackageModel::factory(),
            'AllergyId' => AllergiesModel::query()->inRandomOrder()->value('Id') ?? AllergiesModel::factory(),
            'is_active' => true,
            'note' => $this->faker->optional()->sentence(),
        ];
    }
}
