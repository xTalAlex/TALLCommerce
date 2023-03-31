<?php

namespace Database\Seeders;

use App\Models\ShippingPrice;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class ShippingPricesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        ShippingPrice::create([
            'name'          => 'Standard',
            'price'         => 0.00, 
            'active'        => true,
            'min_spend'     => 50.00,
            'min_days'      => 2,
            'max_days'      => 4,
        ]);

        ShippingPrice::create([
            'name'          => 'Express',
            'price'         => 10.00,  
            'active'        => true, 
            'min_days'      => 2,
            'max_days'      => 2,
        ]);
    }
}
