<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = ['Software Enginner','Business Development','Marketing','Desain'];

        foreach($data as $d){
            Category::create([
                'name' => $d,
                'slug' => Str::slug($d)
            ]);
        }
    }
}
