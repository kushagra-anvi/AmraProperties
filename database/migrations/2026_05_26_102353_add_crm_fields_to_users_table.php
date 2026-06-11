<?php

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
        Schema::table('users', function (Blueprint $table) {
            $table->string('role')->default('sales_team')->after('password')->index();
            $table->string('phone')->nullable()->after('role');
            $table->string('location')->nullable()->after('phone');
            $table->json('service_areas')->nullable()->after('location');
            $table->boolean('is_active')->default(true)->after('service_areas');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'role',
                'phone',
                'location',
                'service_areas',
                'is_active',
            ]);
        });
    }
};
