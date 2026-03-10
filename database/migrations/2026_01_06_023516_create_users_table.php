<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
   public function up(): void
{
    Schema::create('users', function (Blueprint $table) {
        $table->id();
        $table->string('username')->unique(); 
        $table->string('email')->unique();
        $table->string('password');
        $table->string('full_name');
        
        // កែមកត្រឹមនេះវិញបង
        $table->unsignedBigInteger('role_id')->default(2); 
        $table->foreign('role_id')->references('id')->on('role')->onDelete('cascade');
        
        $table->string('phone')->nullable();
        $table->integer('status')->default(1)->nullable();
        $table->string('image')->nullable(); // បន្ថែមសម្រាប់រូបភាព Profile ដូចក្នុង Schema
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user');
    }
};
