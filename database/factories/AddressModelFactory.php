<?php

namespace Database\Factories;

use App\Models\AddressModel;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<AddressModel>
 */
class AddressModelFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'Street' => $this->faker->streetAddress(),
            'City' => $this->faker->city(),
            'HouseNumber' => $this->faker->buildingNumber(),
            'PostalCode' => $this->faker->postcode(),
            'is_active' => true,
            'note' => $this->faker->sentence(),
        ];
    }
}
