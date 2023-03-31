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
            'draft',
            'pending',
            'payment_failed',
            'paid',
            'preparing',
            'shipped',
            'completed',
            'cancelled',
            'refunded',
            // 'disputed'
        ];

        foreach($statuses as $status)
            OrderStatus::create([
                'name' => $status
            ]);
    }
}
