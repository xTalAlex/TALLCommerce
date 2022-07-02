<?php

namespace Database\Seeders;

use App\Models\{Attribute, AttributeValue};
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class AttributesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $colore = Attribute::create([
            'name' => 'colore'
        ]);
        $values = ['rosso','blue','verde','giallo'];
        foreach($values as $value)
        {
            $colore->values()->create(['value' => $value]);
        }

        $taglia = Attribute::create([
            'name' => 'taglia'
        ]);
        $values = ['s','m','l','xl'];
        foreach($values as $value)
        {
            $taglia->values()->create(['value' => $value]);
        }
    }
}
