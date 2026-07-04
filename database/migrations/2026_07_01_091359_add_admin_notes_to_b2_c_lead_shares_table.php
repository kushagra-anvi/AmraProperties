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
        Schema::table('b2_c_lead_shares', function (Blueprint $table) {
            $table->text('admin_notes')->nullable()->after('remark');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('b2_c_lead_shares', function (Blueprint $table) {
            $table->dropColumn('admin_notes');
        });
    }
};
