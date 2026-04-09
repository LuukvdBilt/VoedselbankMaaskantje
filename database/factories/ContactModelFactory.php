<?php

namespace Database\Factories;

use App\Models\ContactModel;
use App\Models\AddressModel;
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
     // Nederlandse locale instellen
    public function definition(): array
    {
        $faker = \Faker\Factory::create('nl_NL');

        return [
            'UserId' => User::factory(),
            'FirstName' => $faker->firstName(),
            'LastName' => $faker->lastName(),
            'Phone' => $faker->phoneNumber(),
            'AddressId' => AddressModel::factory(),
            'is_active' => true,
            'note' => $faker->sentence(),
        ];
    }
}
