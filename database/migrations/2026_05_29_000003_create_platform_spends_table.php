<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('platform_spends', function (Blueprint $table) {
            $table->id();
            $table->string('source_platform')->index();
            $table->decimal('amount', 15, 2)->default(0);
            $table->date('spent_on')->index();
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('platform_spends');
    }
};
