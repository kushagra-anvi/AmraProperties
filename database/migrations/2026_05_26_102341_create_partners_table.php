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
        Schema::create('partners', function (Blueprint $table) {
            $table->id();
            $table->string('type')->default('agent')->index();
            $table->string('company_name');
            $table->string('contact_person')->nullable();
            $table->string('phone')->nullable();
            $table->string('email')->nullable();
            $table->text('office_address')->nullable();
            $table->json('service_areas')->nullable();
            $table->string('city')->nullable()->index();
            $table->string('package')->default('free')->index();
            $table->date('package_purchase_date')->nullable();
            $table->date('renewal_date')->nullable()->index();
            $table->foreignId('assigned_sales_person_id')->nullable()->constrained('sales_people')->nullOnDelete();
            $table->string('lead_source')->nullable()->index();
            $table->text('remark')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('partners');
    }
};
