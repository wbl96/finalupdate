<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('admins', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->unsignedBigInteger('department_id')->nullable();
            $table->enum('status', ['active', 'inactive'])->default('inactive');
            $table->rememberToken();
            $table->timestamps();

            $table->foreign('department_id')->references('id')->on('departments')->onUpdate('cascade')->onDelete('set null');
        });

        Schema::create('admins_password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });

        DB::table('admins')->insert([
            'name' => 'مدير النظام',
            'email' => 'admin@admin.com',
            'password' => Hash::make('12345678'),
            'department_id' => 1,
            'status' => 'active'
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('admins', function (Blueprint $table) {
            $table->dropForeign(['department_id']);
        });
        Schema::dropIfExists('admins');
        Schema::dropIfExists('admins_password_reset_tokens');
    }
};
