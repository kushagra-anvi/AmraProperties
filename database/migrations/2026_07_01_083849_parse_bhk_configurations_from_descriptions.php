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
        $properties = \DB::table('properties')->get();

        foreach ($properties as $property) {
            $text = $property->title . ' ' . $property->description;
            preg_match_all('/\b(\d+(?:\.5)?)\s*BHK\b/i', $text, $matches);

            if (!empty($matches[1])) {
                $bhks = array_unique($matches[1]);
                sort($bhks);

                foreach ($bhks as $bhk) {
                    $name = $bhk . ' BHK';
                    $exists = \DB::table('property_configurations')
                        ->where('property_id', $property->id)
                        ->where('name', $name)
                        ->exists();

                    if (!$exists) {
                        \DB::table('property_configurations')->insert([
                            'property_id' => $property->id,
                            'name' => $name,
                            'price' => $property->price,
                            'area' => $property->area,
                            'area_unit' => $property->area_unit ?? 'sq_ft',
                            'bedrooms' => (int)$bhk,
                            'bathrooms' => $property->bathrooms ?? (int)$bhk,
                            'status' => 'available',
                            'created_at' => now(),
                            'updated_at' => now(),
                        ]);
                    }
                }
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Data migration, no-op on rollback to prevent accidental data loss of manual changes
    }
};
