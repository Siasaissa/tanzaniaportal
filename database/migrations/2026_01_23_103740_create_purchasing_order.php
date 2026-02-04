<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('purchase_orders', function (Blueprint $table) {
            $table->id();
            
            // Company reference
            $table->unsignedBigInteger('company_id');
            
            // PO Information
            $table->string('po_number')->unique();
            $table->date('po_date');
            $table->date('expected_delivery_date')->nullable();
            
            // Supplier Information
            $table->string('supplier_name');
            $table->string('supplier_email')->nullable();
            $table->string('supplier_phone')->nullable();
            $table->text('supplier_address')->nullable();
            $table->string('supplier_contact_person')->nullable();
            
            // Items (store as JSON)
            $table->json('items');
            
            // Financial Information
            $table->decimal('subtotal', 12, 2)->default(0);
            $table->decimal('tax_rate', 5, 2)->default(0);
            $table->decimal('tax_amount', 12, 2)->default(0);
            $table->decimal('discount', 12, 2)->default(0);
            $table->decimal('shipping_cost', 12, 2)->default(0);
            $table->decimal('total_amount', 12, 2);
            
            // Status & Tracking
            $table->enum('status', [
                'draft', 
                'pending_approval', 
                'approved', 
                'ordered', 
                'partial_received', 
                'completed', 
                'cancelled'
            ])->default('draft');
            
            $table->enum('payment_terms', [
                'net_15',
                'net_30',
                'net_45',
                'net_60',
                'upon_delivery',
                'advance_payment'
            ])->default('net_30');
            
            $table->enum('delivery_method', [
                'pickup',
                'delivery',
                'courier',
                'freight'
            ])->nullable();
            
            // Shipping Information
            $table->text('shipping_address')->nullable();
            $table->string('shipping_instructions')->nullable();
            
            // Approval Information
            $table->unsignedBigInteger('approved_by')->nullable();
            $table->timestamp('approved_at')->nullable();
            
            // Notes
            $table->text('notes')->nullable();
            $table->text('terms_conditions')->nullable();
            
            // Timestamps
            $table->timestamps();
            $table->softDeletes();
            
            // Foreign keys
            $table->foreign('company_id')->references('id')->on('companies')->onDelete('cascade');
            $table->foreign('approved_by')->references('id')->on('users')->onDelete('set null');
            
            // Indexes
            $table->index('po_number');
            $table->index('status');
            $table->index('po_date');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('purchase_orders');
    }
};