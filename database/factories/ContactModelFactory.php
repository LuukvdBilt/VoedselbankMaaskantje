<?php

namespace Database\Factories;

use App\Models\ContactModel;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<ContactModel>
 */
class ContactModelFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'UserId' => User::factory(),
            'FirstName' => $this->faker->firstName(),
            'LastName' => $this->faker->lastName(),
            'Phone' => $this->faker->phoneNumber(),
            'AddressId' => null, 
            'is_active' => true,
            'note' => $this->faker->sentence(),
        ];
    }
}
