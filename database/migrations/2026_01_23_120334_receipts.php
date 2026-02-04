<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('receipts', function (Blueprint $table) {
            $table->id();
            
            // Company reference
            $table->unsignedBigInteger('company_id');
            
            // Receipt Information
            $table->string('receipt_number')->unique();
            $table->unsignedBigInteger('purchase_order_id');
            $table->date('receipt_date');
            
            // Received By
            $table->unsignedBigInteger('received_by')->nullable();
            $table->string('received_by_name')->nullable();
            
            // Supplier Information (copied from PO for reference)
            $table->string('supplier_name');
            $table->string('supplier_contact_person')->nullable();
            
            // Receipt Items (JSON)
            $table->json('items');
            
            // Quantity Information
            $table->integer('total_items_received')->default(0);
            $table->integer('total_quantity_received')->default(0);
            
            // Financial Information
            $table->decimal('total_amount', 12, 2)->default(0);
            
            // Status & Tracking
            $table->enum('status', [
                'draft',
                'partial',
                'completed',
                'verified',
                'cancelled'
            ])->default('draft');
            
            $table->enum('receipt_type', [
                'full_delivery',
                'partial_delivery',
                'return',
                'damaged_goods'
            ])->default('full_delivery');
            
            // Delivery Information
            $table->string('delivery_note_number')->nullable();
            $table->string('vehicle_number')->nullable();
            $table->string('driver_name')->nullable();
            $table->string('driver_contact')->nullable();
            
            // Quality & Condition
            $table->text('quality_notes')->nullable();
            $table->enum('condition', [
                'excellent',
                'good',
                'fair',
                'poor',
                'damaged'
            ])->default('good');
            
            // Storage Information
            $table->string('storage_location')->nullable();
            $table->string('bin_location')->nullable();
            
            // Verification
            $table->unsignedBigInteger('verified_by')->nullable();
            $table->timestamp('verified_at')->nullable();
            $table->text('verification_notes')->nullable();
            
            // Notes
            $table->text('notes')->nullable();
            $table->text('return_reason')->nullable();
            
            // Timestamps
            $table->timestamps();
            $table->softDeletes();
            
            // Foreign keys
            $table->foreign('company_id')->references('id')->on('companies')->onDelete('cascade');
            $table->foreign('purchase_order_id')->references('id')->on('purchase_orders')->onDelete('cascade');
            $table->foreign('received_by')->references('id')->on('users')->onDelete('set null');
            $table->foreign('verified_by')->references('id')->on('users')->onDelete('set null');
            
            // Indexes
            $table->index('receipt_number');
            $table->index('purchase_order_id');
            $table->index('receipt_date');
            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('receipts');
    }
};