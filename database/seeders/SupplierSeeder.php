<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SupplierSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('suppliers')->insert([
            [
                'name' => 'Apple Official',
                'contact_name' => 'tingtong',
                'address' => 'Phnom Penh, Cambodia',
                'phone'=>'098765432',
                'email'=>'tingtong12@gmail.com',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Samsung Global',
                'contact_name' => 'jonh',
                'address' => 'Seoul, South Korea',
                'phone'=>'08765432',
                'email'=>'jonh@gmail2.com',
                'created_at' => now(),
                'updated_at' => now()
            ],
        ]);
    }
}