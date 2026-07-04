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
        $properties = \DB::table('properties')->where('description', 'like', '%<table%')->get();

        foreach ($properties as $property) {
            // Parse the HTML table using DOMDocument
            $dom = new \DOMDocument();
            libxml_use_internal_errors(true);
            $dom->loadHTML('<?xml encoding="utf-8" ?>' . $property->description);
            libxml_clear_errors();
            
            $tables = $dom->getElementsByTagName('table');
            if ($tables->length === 0) {
                continue;
            }
            
            $table = $tables->item(0);
            $rows = $table->getElementsByTagName('tr');
            
            foreach ($rows as $row) {
                $cols = $row->getElementsByTagName('td');
                if ($cols->length < 3) {
                    continue;
                }
                
                $configName = trim($cols->item(0)->textContent);
                $areaText = trim($cols->item(1)->textContent);
                $priceText = trim($cols->item(2)->textContent);
                
                preg_match('/(\d+(?:\.5)?)\s*BHK/i', $configName, $bhkMatch);
                if (empty($bhkMatch[1])) {
                    continue;
                }
                
                $bedrooms = (int)$bhkMatch[1];
                
                preg_match('/([\d,]+)\s*(?:sq|sf)/i', $areaText, $areaMatch);
                $area = null;
                if (!empty($areaMatch[1])) {
                    $area = (int)str_replace(',', '', $areaMatch[1]);
                }
                
                $cleanPrice = str_replace(['₹', '*', ',', ' '], '', $priceText);
                $priceVal = 0;
                
                if (preg_match('/([\d\.]+)\s*(?:Cr|Crore)/i', $cleanPrice, $priceMatch)) {
                    $priceVal = (int)(floatval($priceMatch[1]) * 10000000);
                } elseif (preg_match('/([\d\.]+)\s*(?:L|Lakh|Lac)/i', $cleanPrice, $priceMatch)) {
                    $priceVal = (int)(floatval($priceMatch[1]) * 100000);
                } else {
                    preg_match('/([\d\.]+)/', $cleanPrice, $priceMatch);
                    if (!empty($priceMatch[1])) {
                        $priceVal = (int)floatval($priceMatch[1]);
                    }
                }
                
                if ($bedrooms && $priceVal) {
                    $configId = \DB::table('property_configurations')
                        ->where('property_id', $property->id)
                        ->where('bedrooms', $bedrooms)
                        ->value('id');
                        
                    if ($configId) {
                        \DB::table('property_configurations')
                            ->where('id', $configId)
                            ->update([
                                'price' => $priceVal,
                                'area' => $area ?: $property->area,
                                'area_unit' => 'sq_ft',
                                'updated_at' => now(),
                            ]);
                    } else {
                        \DB::table('property_configurations')->insert([
                            'property_id' => $property->id,
                            'name' => "{$bedrooms} BHK",
                            'price' => $priceVal,
                            'area' => $area ?: $property->area,
                            'area_unit' => 'sq_ft',
                            'bedrooms' => $bedrooms,
                            'bathrooms' => $bedrooms,
                            'status' => 'available',
                            'created_at' => now(),
                            'updated_at' => now(),
                        ]);
                    }
                }
            }

            // Remove the table and its wrappers from the description field to prevent redundancy
            $newDescription = preg_replace('/<table[^>]*>.*?<\/table>/is', '', $property->description);
            $newDescription = preg_replace('/<figure class="wp-block-table">.*?<\/figure>/is', '', $newDescription);
            $newDescription = str_replace('<figure class="wp-block-table"></figure>', '', $newDescription);
            
            // Clean up any heading blocks matching "Configurations & Pricing"
            $newDescription = preg_replace('/(?:<!--\s*wp:heading\s*(?:{[^}]*})?\s*-->\s*)?<h\d[^>]*>.*?Configurations\s*(?:&|&amp;)\s*Pricing.*?<\/h\d>(?:\s*<!--\s*\/wp:heading\s*-->)?/is', '', $newDescription);
            
            \DB::table('properties')
                ->where('id', $property->id)
                ->update([
                    'description' => trim($newDescription),
                    'updated_at' => now(),
                ]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // No-op
    }
};
