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
        Schema::create('b2_c_leads', function (Blueprint $table) {
            $table->id();
            $table->string('source_platform')->default('website')->index();
            $table->timestamp('lead_created_at')->useCurrent()->index();
            $table->string('name');
            $table->string('phone')->nullable();
            $table->string('email')->nullable();
            $table->string('city')->nullable()->index();
            $table->string('pincode')->nullable();
            $table->decimal('budget_min', 15, 2)->nullable();
            $table->decimal('budget_max', 15, 2)->nullable();
            $table->json('preferred_locations')->nullable();
            $table->string('property_type')->nullable()->index();
            $table->string('configuration')->nullable()->index();
            $table->string('status')->default('new')->index();
            $table->text('remark')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('b2_c_leads');
    }
};
