<?php

namespace App\Imports;

use App\Models\Product;
use Maatwebsite\Excel\Concerns\ToModel;

class ProductsImport implements ToModel
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        return new Product([
            'sku'     => $row[0],
            'name'    => ucwords(strtolower($row[1])),
            'original_price' => (float) str_replace(',', '.', $row[5]),
            'selling_price' => (float) str_replace(',', '.', $row[5]),
            'hidden' => false,
        ]);
    }
}
