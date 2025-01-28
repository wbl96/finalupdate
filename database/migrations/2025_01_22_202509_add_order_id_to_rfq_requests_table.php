<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddOrderIdToRfqRequestsTable extends Migration
{
    public function up()
    {
        Schema::table('rfq_requests', function (Blueprint $table) {
            $table->foreignId('order_id')->nullable()
                  ->after('status')
                  ->constrained('orders')
                  ->nullOnDelete();
        });
    }

    public function down()
    {
        Schema::table('rfq_requests', function (Blueprint $table) {
            $table->dropForeign(['order_id']);
            $table->dropColumn('order_id');
        });
    }
}