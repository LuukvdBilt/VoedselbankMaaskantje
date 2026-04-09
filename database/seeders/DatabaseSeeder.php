<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Admin user for testing purposes

        User::query()->updateOrCreate(
            ['email' => 'admin@maaskantje.com'],
            [
                'name' => 'Admin',
                'password' => bcrypt('achraf123'),
                'rolename' => 'admin',
            ]
        );

        // Manager user for testing purposes
        User::query()->updateOrCreate(
            ['email' => 'manager@maaskantje.com'],
            [
                'name' => 'Manager',
                'password' => bcrypt('achraf123'),
                'rolename' => 'manager',
            ]
        );

        // Customer user for testing purposes

        User::query()->updateOrCreate(
            ['email' => 'customer@maaskantje.com'],
            [
                'name' => 'Customer',
                'password' => bcrypt('achraf123'),
                'rolename' => 'customer',
            ]
        );

        // Supplier user for testing purposes
        User::query()->updateOrCreate(
            ['email' => 'supplier@maaskantje.com'],
            [
                'name' => 'Supplier',
                'password' => bcrypt('achraf123'),
                'rolename' => 'supplier',
            ]
        );

        $this->call([
            ContactSeeder::class,
            SupplierSeeder::class,
            AddressSeeder::class,
            AllergiesSeeder::class,
            CategorySeeder::class,
            ProductSeeder::class,
            FoodPackageSeeder::class,
        ]);
    }
}
