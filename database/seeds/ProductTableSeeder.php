<?php

use App\Product;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProductTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('products')->insert([
            'id' => 1,
            'name' => 'Cabbage',
            'available_stock' => '100',
        ]);

        DB::table('products')->insert([
            'id' => 2,
            'name' => 'Steak',
            'available_stock' => '100',
        ]);

        DB::table('products')->insert([
            'id' => 3,
            'name' => 'Carrots',
            'available_stock' => '100',
        ]);

        DB::table('products')->insert([
            'id' => 4,
            'name' => 'Apple',
            'available_stock' => '100',
        ]);
    }
}
