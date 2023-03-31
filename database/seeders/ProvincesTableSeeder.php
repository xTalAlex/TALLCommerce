<?php

namespace Database\Seeders;

use App\Models\Province;
use Illuminate\Database\Seeder;
use App\Imports\ProvincesImport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class ProvincesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Excel::import(new ProvincesImport, '/data/import/provinces.csv');

        Province::whereIn('code', [ 'MI', 'PV', 'VA'])->update(['is_active' => true]);
    }
}
