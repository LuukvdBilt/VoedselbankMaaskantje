<?php

namespace Database\Factories;

use App\Models\FoodpackageModel;
use App\Models\FoodPackageProductModel;
use App\Models\ProductModel;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<FoodPackageProductModel>
 */
class FoodPackageProductFactory extends Factory
{
    protected $model = FoodPackageProductModel::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'FoodPackageId' => FoodpackageModel::query()->inRandomOrder()->value('Id') ?? FoodpackageModel::factory(),
            'ProductId' => ProductModel::query()->inRandomOrder()->value('Id') ?? ProductModel::factory(),
            'Quantity' => $this->faker->numberBetween(1, 5),
            'is_active' => true,
            'note' => $this->faker->optional()->sentence(),
        ];
    }
}
