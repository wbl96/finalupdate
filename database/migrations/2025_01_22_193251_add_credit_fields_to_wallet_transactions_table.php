<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('wallet_transactions', function (Blueprint $table) {
            $table->boolean('is_paid')->default(false)->after('type');
            $table->timestamp('due_date')->nullable()->after('is_paid');
            // تعديل حقل type ليشمل النوع الجديد
            $table->enum('type', ['credit', 'debit', 'credit_used', 'payment'])
                ->change();
        });
    }
    
    public function down()
    {
        Schema::table('wallet_transactions', function (Blueprint $table) {
            $table->dropColumn(['is_paid', 'due_date']);
            // إعادة type للقيم الأصلية
            $table->enum('type', ['credit', 'debit'])->change();
        });
    }
};
