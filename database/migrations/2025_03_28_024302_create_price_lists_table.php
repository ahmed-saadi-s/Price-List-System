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
        Schema::create('price_lists', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
            $table->string('country_code')->nullable(); 
            //foreign key referencing the 'code' column in the 'countries' table
            $table->foreign('country_code')->references('code')->on('countries')->onDelete('set null'); 
            $table->string('currency_code')->nullable(); 
            //foreign key referencing the 'code' column in the 'currencies' table
            $table->foreign('currency_code')->references('code')->on('currencies')->onDelete('set null');
            $table->decimal('price', 8, 2);
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->integer('priority')->default(1); // Lower number = higher priority

            // Indexes for performance
            $table->index(['product_id', 'country_code', 'currency_code']);
            $table->index(['start_date', 'end_date']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('price_lists');
    }
};
