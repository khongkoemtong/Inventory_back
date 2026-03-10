<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
   public function up()
{
    Schema::table('categories', function (Blueprint $table) {
        $table->integer('seller_id')->after('id')->nullable();
    });
    Schema::table('suppliers', function (Blueprint $table) {
        $table->integer('seller_id')->after('id')->nullable();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('categories_and_suppliers', function (Blueprint $table) {
            //
        });
    }
};
