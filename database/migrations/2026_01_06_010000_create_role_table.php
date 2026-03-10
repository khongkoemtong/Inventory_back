<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    
     
    public function up(): void
{
    Schema::create('role', function (Blueprint $table) {
        $table->id();
        $table->string('name'); // Admin, User, SuperAdmin
        $table->timestamps();
    });

    
    DB::table('role')->insert([
        ['id' => 1, 'name' => 'Admin'],
        ['id' => 2, 'name' => 'User'],
        ['id' => 4, 'name' => 'SuperAdmin'],
    ]);
}


    public function down(): void
    {
        Schema::dropIfExists('role');
    }
};
