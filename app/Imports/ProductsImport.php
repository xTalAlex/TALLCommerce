<?php

namespace App\Imports;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\WithUpserts;
use Maatwebsite\Excel\Concerns\WithStartRow;
use Maatwebsite\Excel\Concerns\WithProgressBar;
use Maatwebsite\Excel\Concerns\WithUpsertColumns;

class ProductsImport implements ToModel, WithStartRow, WithUpserts, WithUpsertColumns, WithProgressBar
{
    use Importable;
    
    private $categories;

    public function __construct()
    {
        $this->categories = Category::all();
    }

    /**
     * @return int
     */
    public function startRow(): int
    {
        return 2;
    }

    /**
     * @return string|array
     */
    public function uniqueBy()
    {
        return ['sku'];
    }

    /**
     * @return array
     */
    public function upsertColumns()
    {
        return ['quantity'];
    }

    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        return new Product([
            'sku'               => $row[0],
            'name'              => $row[1], //ucwords(strtolower($row[1])),
            'slug'              => Str::slug(strtolower($row[1])),
            'short_description' => $row[2],
            'tax_rate'               => $row[4] != config('cart.tax')*100 ? (float)$row[4]/100 : null,
            'original_price'    => (float) str_replace(',', '.', $row[5]),
            'selling_price'    => (float) str_replace(',', '.', $row[5]),
            'quantity'          => (int)$row[6] > 0 ? (int)$row[6] : 0,
            'hidden'            => true,
        ]);
    }
}
