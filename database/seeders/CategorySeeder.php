<?php

namespace Database\Seeders;

use App\Models\Category;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = [
            'Reggeli',
            'Ebéd',
            'Vacsora'
        ];

        $models = null;

        foreach ($data as $value){
            $models[] = [
                'name'          => $value,
                'created_at'    => Carbon::now(),
                'updated_at'    => Carbon::now()
            ];
        }

        Category::insert($models);
    }
}
