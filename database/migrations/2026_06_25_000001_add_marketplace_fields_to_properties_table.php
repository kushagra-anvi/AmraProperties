<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('properties', function (Blueprint $table) {
            $table->foreignId('partner_id')->nullable()->after('id')->constrained('partners')->nullOnDelete();
            $table->foreignId('submitted_by_user_id')->nullable()->after('partner_id')->constrained('users')->nullOnDelete();
            $table->decimal('avg_price_per_sqft', 12, 2)->nullable()->after('price');
            $table->date('possession_date')->nullable()->after('avg_price_per_sqft');
            $table->string('possession_status')->nullable()->after('possession_date');
            $table->json('video_urls')->nullable()->after('gallery');
            $table->string('contact_phone')->nullable()->after('developer_name');
            $table->string('contact_whatsapp')->nullable()->after('contact_phone');
        });
    }

    public function down(): void
    {
        Schema::table('properties', function (Blueprint $table) {
            $table->dropForeign(['partner_id']);
            $table->dropForeign(['submitted_by_user_id']);
            $table->dropColumn([
                'partner_id',
                'submitted_by_user_id',
                'avg_price_per_sqft',
                'possession_date',
                'possession_status',
                'video_urls',
                'contact_phone',
                'contact_whatsapp',
            ]);
        });
    }
};
