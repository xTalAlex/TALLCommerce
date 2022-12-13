<?php

namespace App\Console\Commands;

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
        Excel::store(new OrderProductsExport(yesterday()), 'external/export/daily_orders-'.(today()->format('dmy')).'.xlsx', config('filesystems.default') , null, [
            'visibility' => 'private',
        ]);
        dump('Orders exported');
    }
}
