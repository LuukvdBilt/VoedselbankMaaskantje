<?php

namespace Database\Factories;

use App\Models\Client;
use App\Models\Household;
use Illuminate\Database\Eloquent\Factories\Factory;

class HouseholdFactory extends Factory
{
    protected $model = Household::class;

    public function definition(): array
    {
        return [
            'ClientId' => Client::factory(),
            'TotalMembers' => $this->faker->numberBetween(1, 8),
            'RegistrationDate' => now(),
            'is_active' => true,
        ];
    }
}