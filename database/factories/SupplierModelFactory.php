<?php

namespace Database\Factories;

use App\Models\ContactModel;
use App\Models\SupplierModel;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<SupplierModel>
 */
class SupplierModelFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'CompanyName' => $this->faker->company(),
            'ContactId' => ContactModel::factory(),
            'is_active' => true,
            'note' => $this->faker->sentence(),
        ];
    }
}
