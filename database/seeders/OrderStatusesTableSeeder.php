<?php

namespace Database\Seeders;

use App\Models\OrderStatus;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class OrderStatusesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $statuses = [
            'pending',
            'payment_failed',
            'paied',
            'shipped',
            'completed',
            'cancelled',
            'refunded',
            'disputed'
        ];

        foreach($statuses as $status)
            OrderStatus::create([
                'name' => $status
            ]);
    }
}
