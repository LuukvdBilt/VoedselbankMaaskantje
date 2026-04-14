<?php

namespace Database\Seeders;

use App\Models\AllergiesModel;
use App\Models\FoodPackageAllergyModel;
use App\Models\FoodpackageModel;
use App\Models\FoodPackageProductModel;
use App\Models\ProductModel;
use Illuminate\Database\Seeder;

class FoodPackageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $packages = FoodpackageModel::factory()->count(5)->create();

        foreach ($packages as $package) {
            $products = ProductModel::query()->inRandomOrder()->limit(rand(2, 5))->get();
            foreach ($products as $product) {
                FoodPackageProductModel::query()->firstOrCreate(
                    [
                        'FoodPackageId' => $package->Id,
                        'ProductId' => $product->Id,
                    ],
                    [
                        'Quantity' => rand(1, 3),
                        'is_active' => true,
                    ]
                );
            }

            $allergies = AllergiesModel::query()->inRandomOrder()->limit(rand(1, 3))->get();
            foreach ($allergies as $allergy) {
                FoodPackageAllergyModel::query()->firstOrCreate(
                    [
                        'FoodPackageId' => $package->Id,
                        'AllergyId' => $allergy->Id,
                    ],
                    [
                        'is_active' => true,
                    ]
                );
            }
        }
    }
}
