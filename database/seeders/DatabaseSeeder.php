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
        //Admin user for testing purposes

        User::factory()->create([
            'name' => 'Admin',
            'email' => 'admin@maaskantje.com',
            'password' => bcrypt('achraf123'),
            'rolename' => 'admin',
        ]);

        // Manager user for testing purposes
        User::factory()->create([
            'name' => 'Manager',
            'email' => 'manager@maaskantje.com',
            'password' => bcrypt('achraf123'),
            'rolename' => 'manager',
        ]);

        // Customer user for testing purposes

        User::factory()->create([
            'name' => 'Customer',
            'email' => 'customer@maaskantje.com',
            'password' => bcrypt('achraf123'),
            'rolename' => 'customer',
        ]);

        // Supplier user for testing purposes
        User::factory()->create([
            'name' => 'Supplier',
            'email' => 'supplier@maaskantje.com',
            'password' => bcrypt('achraf123'),
            'rolename' => 'supplier',
        ]);

        $this->call([
            ContactSeeder::class,
            SupplierSeeder::class,
            AddressSeeder::class,
        ]);
    }
}
