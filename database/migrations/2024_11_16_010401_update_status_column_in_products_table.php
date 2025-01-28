<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::table('products')->whereNotIn('status', ['active', 'inactive'])->update(['status' => 'inactive']);
        
        Schema::table('products', function (Blueprint $table) {
            $table->enum('status', ['active', 'inactive'])->default('inactive')->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->enum('status', ['active', 'inactive', 'new'])->default('new')->change();
        });
    }
};
