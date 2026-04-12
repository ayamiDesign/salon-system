public function run(): void
{
    $this->call([
        UsersTableSeeder::class,
        CategoriesTableSeeder::class,
        FaqsTableSeeder::class,
        FaqHistoriesTableSeeder::class,
    ]);
}