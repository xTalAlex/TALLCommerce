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
        // $kg = Attribute::create([
        //     'name' => 'kg'
        // ]);
        // $values = ['1','1.1','1.2','1.3','1.4','1.5','1.6','1.7','1.75','1.8','1.85','2','2.1','2.5','2.8','2.9','3','3.8','4'];
        // foreach($values as $value)
        // {
        //     $kg->values()->create(['value' => $value]);
        // }

        $g = Attribute::create([
            'name' => 'g'
        ]);
        $values = ['80','150','180','200','220','250','260','270','300','320','400','500','1000','1100','1200','1300','1400','1500','1600','1700','1750','1800','1850','2000','2100','2500','2800','2900','3000','3800','4000'];
        foreach($values as $value)
        {
            $g->values()->create(['value' => $value]);
        }

        $pz = Attribute::create([
            'name' => 'Pz'
        ]);
        $values = ['1','2','3','4','5','6','8','9','10','12','14','15','16','18','20','22','24','25','35','36','45','48','50','60'];
        foreach($values as $value)
        {
            $pz->values()->create(['value' => $value]);
        }
    }
}
