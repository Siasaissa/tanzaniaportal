<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('invoices', function (Blueprint $table) {
            $table->id();
            
            // Link to delivery note
            $table->unsignedBigInteger('delivery_note_id');
            
            // Link to companies table
            $table->unsignedBigInteger('company_id');
            
            // Invoice info
            $table->string('invoice_number')->unique();
            $table->date('invoice_date');
            $table->date('due_date');
            
            // Client info (copied from delivery note/quote)
            $table->string('client_name');
            $table->string('client_email')->nullable();
            $table->string('client_phone')->nullable();
            $table->text('client_address')->nullable();
            
            // Items (copied from delivery note)
            $table->json('items')->nullable();
            
            // Financial details
            $table->decimal('subtotal', 15, 2)->default(0);
            $table->decimal('tax', 15, 2)->default(0);
            $table->decimal('tax_rate', 5, 2)->default(0);
            $table->decimal('discount', 15, 2)->default(0);
            $table->decimal('total', 15, 2)->default(0);
            $table->decimal('amount_paid', 15, 2)->default(0);
            $table->decimal('balance', 15, 2)->default(0);
            
            // Payment info
            $table->enum('payment_status', ['pending', 'partial', 'paid', 'overdue', 'cancelled'])
                  ->default('pending');
            $table->enum('payment_method', ['cash', 'bank_transfer', 'cheque', 'mobile_money', 'credit_card', 'other'])
                  ->nullable();
            $table->string('transaction_reference')->nullable();
            $table->date('payment_date')->nullable();
            
            // Status
            $table->enum('status', ['draft', 'sent', 'viewed', 'paid', 'overdue', 'cancelled'])
                  ->default('draft');
            
            // Additional notes
            $table->text('notes')->nullable();
            $table->text('terms')->nullable();
            
            // Timestamps
            $table->timestamps();
            
            // Foreign keys
            $table->foreign('delivery_note_id')
                  ->references('id')
                  ->on('delivery_notes')
                  ->onDelete('restrict');
                  
            $table->foreign('company_id')
                  ->references('id')
                  ->on('companies')
                  ->onDelete('cascade');
                  
            // Indexes
            $table->index('invoice_number');
            $table->index('payment_status');
            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('invoices');
    }
};