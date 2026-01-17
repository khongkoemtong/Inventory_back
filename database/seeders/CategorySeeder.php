<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        DB::table('categories')->insert([
            ['name' => 'Electronics', 'description' => 'Gadgets and devices', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Fashion', 'description' => 'Clothing and accessories', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Furniture', 'description' => 'Home and office furniture', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }
}