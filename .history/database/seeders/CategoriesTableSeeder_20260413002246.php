<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategoriesTableSeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            ['name' => '受付', 'sort_order' => 1],
            ['name' => '接客', 'sort_order' => 2],
            ['name' => '施術', 'sort_order' => 3],
            ['name' => '会計', 'sort_order' => 4],
            ['name' => '予約', 'sort_order' => 5],
            ['name' => '清掃', 'sort_order' => 6],
            ['name' => 'トラブル対応', 'sort_order' => 7],
            ['name' => '新人向け', 'sort_order' => 8],
        ];

        foreach ($categories as $category) {
            Category::updateOrCreate(
                ['name' => $category['name']],
                $category
            );
        }
    }
}