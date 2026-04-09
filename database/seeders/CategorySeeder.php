<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            'Groenten',
            'Fruit',
            'Zuivel',
            'Vlees',
            'Dranken',
            'Granen',
            'Conserven',
            'Pasta',
            'Brood',
        ];

        foreach ($categories as $name) {
            Category::query()->updateOrCreate(
                ['Name' => $name],
                ['is_active' => true]
            );
        }
    }
}
