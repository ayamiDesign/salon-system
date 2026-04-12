<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategoriesTableSeeder extends Seeder
{
    public function run(): void
    {
        $data = [
            ['name' => '接客', 'sort_order' => 1],
            ['name' => '施術', 'sort_order' => 2],
            ['name' => '受付', 'sort_order' => 3],
            ['name' => '会計', 'sort_order' => 4],
        ];

        foreach ($data as $row) {
            Category::updateOrCreate(
                ['name' => $row['name']],
                $row
            );
        }
    }
}