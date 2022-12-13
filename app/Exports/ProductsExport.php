<?php

namespace App\Exports;

use App\Models\Product;
use Maatwebsite\Excel\Concerns\FromQuery;

class ProductsExport implements FromQuery
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function query()
    {
        return Product::withTrashed();
    }
}
