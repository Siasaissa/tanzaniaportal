<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('purchase_orders', function (Blueprint $table) {
            // Add receipt tracking columns
            $table->integer('total_items_ordered')->default(0)->after('total_amount');
            $table->integer('total_items_received')->default(0)->after('total_items_ordered');
            $table->integer('total_quantity_ordered')->default(0)->after('total_items_received');
            $table->integer('total_quantity_received')->default(0)->after('total_quantity_ordered');
            $table->decimal('amount_received', 12, 2)->default(0)->after('total_quantity_received');
            
            // Receipt status
            $table->enum('receipt_status', [
                'not_received',
                'partial',
                'completed',
                'over_received'
            ])->default('not_received')->after('amount_received');
            
            // Last receipt date
            $table->date('last_receipt_date')->nullable()->after('receipt_status');
        });
    }

    public function down(): void
    {
        Schema::table('purchase_orders', function (Blueprint $table) {
            $table->dropColumn([
                'total_items_ordered',
                'total_items_received',
                'total_quantity_ordered',
                'total_quantity_received',
                'amount_received',
                'receipt_status',
                'last_receipt_date'
            ]);
        });
    }
};