<?php

namespace Database\Seeders;

use App\Models\Tag;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class TagsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $tags = ['vegano', 'vegetariano', 'senza glutine', 'bio'];
        foreach($tags as $tag)
            Tag::create([
                'name' => $tag
            ]);
    }
}
