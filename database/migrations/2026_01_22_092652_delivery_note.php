<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('delivery_notes', function (Blueprint $table) {
            $table->id();
            
            // Link to quotation
            $table->unsignedBigInteger('quotation_id');
            
            // Link to companies table
            $table->unsignedBigInteger('company_id');
            
            // Delivery note info
            $table->string('delivery_note_number');
            $table->date('delivery_date');
            $table->date('dispatch_date')->nullable();
            
            // Delivery address (could be different from client info in quotation)
            $table->string('delivery_address')->nullable();
            $table->string('delivery_contact_person')->nullable();
            $table->string('delivery_contact_phone')->nullable();
            
            // Transport details
            $table->string('vehicle_number')->nullable();
            $table->string('driver_name')->nullable();
            $table->string('driver_contact')->nullable();
            
            // Delivery items (can be modified from quotation items)
            $table->json('items')->nullable();
            
            // Status
            $table->enum('status', ['pending', 'dispatched', 'delivered', 'cancelled'])
                  ->default('pending');
            
            // Additional notes
            $table->text('delivery_notes')->nullable();
            
            // Timestamps
            $table->timestamps();
            
            // Foreign keys
            $table->foreign('quotation_id')
                  ->references('id')
                  ->on('quotations')
                  ->onDelete('cascade');
                  
            $table->foreign('company_id')
                  ->references('id')
                  ->on('companies')
                  ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('delivery_notes');
    }
};