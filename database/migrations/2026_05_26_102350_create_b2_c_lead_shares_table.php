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
        Schema::create('b2_c_lead_shares', function (Blueprint $table) {
            $table->id();
            $table->foreignId('b2_c_lead_id')->constrained('b2_c_leads')->cascadeOnDelete();
            $table->foreignId('partner_id')->constrained()->cascadeOnDelete();
            $table->foreignId('shared_by_user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('shared_at')->useCurrent()->index();
            $table->text('remark')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('b2_c_lead_shares');
    }
};
