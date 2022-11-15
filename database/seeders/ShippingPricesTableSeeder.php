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
            'description'   => '7 days',
            'price'         => 2.00, 
            'active'         => true,  
        ]);

        ShippingPrice::create([
            'name'          => 'Fast',
            'description'   => '2-3 days',
            'price'         => 5.00,  
            'active'         => true, 
        ]);

        ShippingPrice::create([
            'name'          => 'Express',
            'description'   => '1 day',
            'price'         => 10.00,  
            'active'         => true, 
        ]);
    }
}
