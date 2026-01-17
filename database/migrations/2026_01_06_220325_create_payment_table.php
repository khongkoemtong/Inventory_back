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
        Schema::create('payment', function (Blueprint $table) { // ប្រើអក្សរ s តាមស្ដង់ដារ
            $table->id();
            
            // ភ្ជាប់ទៅកាន់តារាង orders
            $table->foreignId('order_id')->constrained('orders')->onDelete('cascade');
            
            // ចំនួនទឹកប្រាក់ដែលត្រូវបង់
            $table->decimal('amount', 12, 2);
            
            // លេខយោងពីធនាគារ (សំខាន់ខ្លាំងសម្រាប់ផ្ទៀងផ្ទាត់)
            $table->string('transaction_reference')->nullable()->comment('ABA or Wing Transaction ID');
            
            // វិធីសាស្ត្របង់ប្រាក់
            $table->enum('payment_method', ['ABA', 'Wing', 'ACLEDA', 'Khmer QR']);
            
            // ស្ថានភាពនៃការបង់ប្រាក់
            $table->enum('status', ['Pending', 'Complete', 'Failed', 'Refunded'])->default('Pending');
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};