<?php

namespace Database\Factories;

use App\Models\Address;
use App\Models\Client;
use Illuminate\Database\Eloquent\Factories\Factory;

class ClientFactory extends Factory
{
    protected $model = Client::class;

    public function definition(): array
    {
        return [
            'FirstName' => $this->faker->firstName(),
            'LastName' => $this->faker->lastName(),
            'Phone' => $this->faker->phoneNumber(),
            'AddressId' => Address::factory(),
            'is_active' => true,
        ];
    }
}