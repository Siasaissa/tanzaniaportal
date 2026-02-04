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
        Schema::create('quotations', function (Blueprint $table) {
            $table->id();
            
            // Link to companies table
            $table->unsignedBigInteger('company_id');
            
            // Basic quotation info
            $table->string('quotation_number');
            $table->date('date');
            
            // Client info
            $table->string('client_name');
            $table->string('client_email')->nullable();
            $table->string('client_phone')->nullable();
            
            // Simple items (store as JSON)
            $table->json('items')->nullable();
            
            // Total amount
            $table->decimal('total', 10, 2);
            
            // Notes
            $table->text('notes')->nullable();
            
            // Timestamps
            $table->timestamps();
            
            // Foreign key
            $table->foreign('company_id')
                  ->references('id')
                  ->on('companies')
                  ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('quotations');
    }
};
