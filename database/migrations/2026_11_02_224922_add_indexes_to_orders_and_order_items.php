<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            // Speed up filtering by status (e.g., "In Progress" vs "Completed")
            $table->index('status', 'idx_orders_status');
            // Speed up date-based filtering
            $table->index('created_at', 'idx_orders_created_at');
            // Speed up customer name / order number searches
            $table->index('customer_name', 'idx_orders_customer_name');
        });

        Schema::table('order_items', function (Blueprint $table) {
            // order_id already has FK but no explicit index — add a covering index
            // to make eager-loading orderItems fast
            $table->index('order_id', 'idx_order_items_order_id');
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropIndex('idx_orders_status');
            $table->dropIndex('idx_orders_created_at');
            $table->dropIndex('idx_orders_customer_name');
        });

        Schema::table('order_items', function (Blueprint $table) {
            $table->dropIndex('idx_order_items_order_id');
        });
    }
};
