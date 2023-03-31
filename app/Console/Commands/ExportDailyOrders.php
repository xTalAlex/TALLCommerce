<?php

namespace App\Console\Commands;

use App\Models\OrderStatus;
use App\Exports\OrdersExport;
use Illuminate\Console\Command;
use App\Exports\OrderProductsExport;
use Maatwebsite\Excel\Facades\Excel;

class ExportDailyOrders extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'export:daily-orders';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Export daily orders to csv';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        dump('Exporting daily orders...');
        Excel::store(new OrdersExport(
            yesterday()->startOfDay(), 
            today()->startOfDay(), 
            [OrderStatus::where('name','like','paid')->first()->id]), 
            'data/export/daily_orders-'.(yesterday()->format('d_m_y')).'.csv', 
            config('filesystems.default') ,
            \Maatwebsite\Excel\Excel::CSV, [
            'visibility' => 'private',
        ]);
        dump('Orders exported');
    }
}
