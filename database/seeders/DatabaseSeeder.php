<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\App;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        if(!App::environment('production'))
        {
            $this->call([
                OrderStatusesTableSeeder::class,
                UsersTableSeeder::class,
                ShippingPricesTableSeeder::class,
                CategoriesTableSeeder::class,
                AttributesTableSeeder::class,
                TagsTableSeeder::class,
                ProvincesTableSeeder::class
            ]);
        }
        else
        {
            $this->call([
                OrderStatusesTableSeeder::class,
                ShippingPricesTableSeeder::class,
                CategoriesTableSeeder::class,
                AttributesTableSeeder::class,
                TagsTableSeeder::class,
                ProvincesTableSeeder::class
            ]);
        }

    }
}
