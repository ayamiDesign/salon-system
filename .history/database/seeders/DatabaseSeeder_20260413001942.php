<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Database\Seeders\UsersTableSeeder;
use Database\Seeders\CategoriesTableSeeder;
use Database\Seeders\FaqsTableSeeder;
use Database\Seeders\FaqHistoriesTableSeeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            UsersTableSeeder::class,
            CategoriesTableSeeder::class,
            FaqsTableSeeder::class,
            FaqHistoriesTableSeeder::class,
        ]);
    }
}