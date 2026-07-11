<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('property_tags', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('property_property_tag', function (Blueprint $table) {
            $table->id();
            $table->foreignId('property_id')->constrained()->cascadeOnDelete();
            $table->foreignId('property_tag_id')->constrained()->cascadeOnDelete();
            $table->timestamps();

            $table->unique(['property_id', 'property_tag_id']);
        });

        $now = now();
        $tags = [
            'New Launch',
            'Ready to Move',
            'Under Construction',
            'RERA Approved',
            'Luxury',
            'Affordable',
            'Investment',
            'Near Metro',
            'Gated Community',
            'Premium Builder',
            'High ROI',
            'Family Friendly',
            'Commercial',
            'Resale',
            'Possession Soon',
        ];

        DB::table('property_tags')->insert(
            collect($tags)->map(fn (string $name) => [
                'name' => $name,
                'slug' => Str::slug($name),
                'is_active' => true,
                'created_at' => $now,
                'updated_at' => $now,
            ])->all()
        );
    }

    public function down(): void
    {
        Schema::dropIfExists('property_property_tag');
        Schema::dropIfExists('property_tags');
    }
};
