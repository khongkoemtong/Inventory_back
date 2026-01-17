<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    // database/seeders/DatabaseSeeder.php

public function run(): void
{
    // បង្កើតទិន្នន័យក្នុងតារាងដែលជា "មេ" គេមុនគេ
    \App\Models\User::factory(5)->create(); // បង្កើត User ៥ នាក់
    
    // បន្ទាប់មកសរសេរ Seeder ឱ្យ Category និង Supplier (បើអ្នកមាន Seeder របស់វា)
    $this->call([
        CategorySeeder::class,
        SupplierSeeder::class,
        ProductSeeder::class, // Product ត្រូវនៅក្រោយគេបង្អស់ ព្រោះវាត្រូវការ ID ពីលើ
    ]);
}
}
