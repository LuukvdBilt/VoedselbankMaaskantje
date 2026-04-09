<?php

namespace Database\Factories;

use App\Models\Inventory;
use App\Models\ProductModel;
use App\Models\SupplierModel;
use Illuminate\Database\Eloquent\Factories\Factory;

class InventoryFactory extends Factory
{
    protected $model = Inventory::class;

    public function definition(): array
    {
        return [
            'ProductId' => ProductModel::factory(),
            'SupplierId' => SupplierModel::factory(),
            'Quantity' => $this->faker->numberBetween(0, 1000),
            'ExpirationDate' => $this->faker->dateTimeBetween('+1 day', '+1 year'),
            'is_active' => $this->faker->boolean(),
            'note' => $this->faker->optional()->sentence(),
        ];
    }
}
