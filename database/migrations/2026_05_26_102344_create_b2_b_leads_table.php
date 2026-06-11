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
        Schema::create('b2_b_leads', function (Blueprint $table) {
            $table->id();
            $table->string('category')->default('agent')->index();
            $table->string('company_name');
            $table->string('contact_person_name')->nullable();
            $table->string('contact_number')->nullable();
            $table->string('whatsapp_number')->nullable();
            $table->string('email')->nullable();
            $table->text('office_address')->nullable();
            $table->json('service_areas')->nullable();
            $table->string('city')->nullable()->index();
            $table->decimal('project_ticket_size_min', 15, 2)->nullable();
            $table->decimal('project_ticket_size_max', 15, 2)->nullable();
            $table->string('source_platform')->default('manual')->index();
            $table->timestamp('lead_created_at')->useCurrent()->index();
            $table->foreignId('assigned_sales_person_id')->nullable()->constrained('sales_people')->nullOnDelete();
            $table->foreignId('converted_partner_id')->nullable()->constrained('partners')->nullOnDelete();
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
        Schema::dropIfExists('b2_b_leads');
    }
};
