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
        Schema::create('departments', function (Blueprint $table) {
            $table->id();
            $table->string('name_ar');
            $table->string('name_en')->nullable();
            $table->enum('status', ['active', 'inactive'])->default('inactive');
            $table->timestamps();
        });

        $departments = [
            [
                'name_ar' => 'المشرف الاعلي',
                'name_en' => 'super admin',
                'status' => 'active'
            ],
            [
                'name_ar' => 'مدير النظام',
                'name_en' => 'admin',
                'status' => 'active'
            ],
        ];

        DB::table('departments')->insert($departments);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('departments');
    }
};
