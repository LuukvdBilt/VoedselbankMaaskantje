<?php

namespace Database\Factories;

use App\Models\ProductModel;
use App\Models\Category;
use App\Models\SupplierModel;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProductModelFactory extends Factory
{
    protected $model = ProductModel::class;

    public function definition()
    {
        return [
            'Barcode' => $this->faker->unique()->ean13(),
            'ProductName' => $this->faker->word(),
            'CategoryId' => Category::factory(),
            'SupplierId' => SupplierModel::factory(),
            'is_active' => true,
            'note' => $this->faker->sentence(),
        ];
    }
}