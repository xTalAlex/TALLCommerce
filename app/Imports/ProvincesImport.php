<?php

namespace App\Imports;

use App\Models\Province;
use Illuminate\Validation\Rule;
use Maatwebsite\Excel\Concerns\ToModel;

class ProvincesImport implements ToModel
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        if (strlen($row[1]) != 2 || $row[1] == "-1" ) {
            return null;
        }

        return new Province([
            'name'     => $row[2],
            'code'    => $row[1], 
            'country' => $row[8],
            'region' => $row[4],
        ]);
    }
}
