<?php

use App\Models\Order;
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
        Schema::table('orders', function (Blueprint $table) {
            if (Schema::hasColumn('orders',"cart_id")) {
                $table->dropForeign(["cart_id"]);
                $table->dropColumn('cart_id');
            }
            if (Schema::hasColumn('orders',"payment_status")) {
                $table->dropColumn('payment_status');
            }
            $table->enum('status', Order::$ORDER_STATUS)->default('pending')->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->foreignId('cart_id')->references('id')->on('carts')->onUpdate('cascade')->onDelete('cascade');
            $table->enum('payment_status', ['pending', 'deserved', 'paid', 'refunded'])->default('pending')->after('status');
            $table->enum('status', ['pending', 'new', 'in progress', 'received', 'refunded'])->default('pending')->change();
        });
    }
};
