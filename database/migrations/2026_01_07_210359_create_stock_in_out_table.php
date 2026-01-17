<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('stock_in_out', function (Blueprint $table) {
            $table->id();
            $table->integer('product_id');
            $table->enum('type',['in','out']);
            $table->integer('quantity');
            $table->dateTime('transaction_date');
            $table->string('reference_id');
            $table->text('note');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stock_in_out');
    }
};
