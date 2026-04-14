<?php

namespace Database\Factories;

use App\Models\Category;
use App\Models\ProductModel;
use App\Models\SupplierModel;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<ProductModel>
 */
class ProductFactory extends Factory
{
    protected $model = ProductModel::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'Barcode' => $this->faker->unique()->ean13(),
            'ProductName' => ucfirst($this->faker->words(2, true)),
            'CategoryId' => Category::query()->inRandomOrder()->value('Id') ?? Category::factory(),
            'SupplierId' => SupplierModel::query()->inRandomOrder()->value('Id') ?? SupplierModel::factory(),
            'is_active' => true,
            'note' => $this->faker->optional()->sentence(),
        ];
    }
}
