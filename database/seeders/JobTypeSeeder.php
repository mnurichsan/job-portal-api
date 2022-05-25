<?php

namespace Database\Seeders;

use App\Models\TypeJob;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class JobTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = ['Full time','Magang','Part time','Freelance'];

        foreach($data as $d){
                TypeJob::create([
                    'name' => $d,
                    'slug' => Str::slug($d)
                ]);
        }
    }
}
