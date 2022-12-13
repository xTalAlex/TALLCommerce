<?php

namespace App\Console\Commands;

use App\Imports\ProductsImport;
use Illuminate\Console\Command;
use Maatwebsite\Excel\Facades\Excel;

class ImportProducts extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'import:products';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import products from csv';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        dump('Importing products...');
        Excel::import(new ProductsImport, '/external/import/articoliweb.csv');
        dump('Products imported');
        return Command::SUCCESS;
    }
}
