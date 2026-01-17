<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{

    public function run(): void
    {
        $this->call([
            CategorySeeder::class,
            SupplierSeeder::class,
            ProductSeeder::class, // Product ត្រូវនៅក្រោយគេ ព្រោះវាត្រូវការ ID ពីពីរខាងលើ
        ]);
    }
}
