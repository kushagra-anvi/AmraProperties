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
        Schema::create('tata_call_logs', function (Blueprint $table) {
            $table->id();
            $table->string('call_id')->unique();
            $table->string('caller_number')->index();
            $table->string('tfn_number')->index();
            $table->integer('duration_seconds')->default(0);
            $table->string('status')->index();
            $table->timestamp('start_time')->index();
            $table->timestamp('end_time')->nullable();
            $table->text('recording_url')->nullable();
            $table->json('raw_data')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tata_call_logs');
    }
};
