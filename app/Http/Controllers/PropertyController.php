<?php

namespace App\Http\Controllers;

use App\Models\Property;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PropertyController extends Controller
{
    /**
     * Display a listing of the properties.
     */
    public function index(Request $request): View
    {
        $query = Property::where('status', 'publish');

        // Search query filtering
        if ($q = $request->input('q')) {
            $query->where(function($sub) use ($q) {
                $sub->where('title', 'like', '%' . $q . '%')
                    ->orWhere('description', 'like', '%' . $q . '%')
                    ->orWhere('developer_name', 'like', '%' . $q . '%')
                    ->orWhere('address', 'like', '%' . $q . '%')
                    ->orWhere('city', 'like', '%' . $q . '%')
                    ->orWhere('configuration', 'like', '%' . $q . '%');

                // Extract BHK query numbers (e.g. 2 BHK)
                if (preg_match_all('/(\d+)\s*bhk/i', $q, $matches)) {
                    foreach ($matches[1] as $bhkNum) {
                        $sub->orWhere('bedrooms', $bhkNum)
                            ->orWhere('configuration', 'like', '%' . $bhkNum . '%bhk%');
                    }
                }
            });
        }

        // Location filtering
        if ($location = $request->input('location')) {
            if ($location === 'mumbai') {
                $query->where(function($sub) {
                    $mumbaiCities = ['mumbai', 'thane', 'navi mumbai', 'panvel', 'dombivli', 'chembur', 'prabhadevi', 'versova', 'airoli', 'kharghar', 'kolshet', 'kapurbawdi'];
                    foreach ($mumbaiCities as $mc) {
                        $sub->orWhere('city', 'like', '%' . $mc . '%');
                    }
                });
            } elseif ($location === 'lucknow') {
                $query->where(function($sub) {
                    $mumbaiCities = ['mumbai', 'thane', 'navi mumbai', 'panvel', 'dombivli', 'chembur', 'prabhadevi', 'versova', 'airoli', 'kharghar', 'kolshet', 'kapurbawdi'];
                    foreach ($mumbaiCities as $mc) {
                        $sub->where('city', 'not like', '%' . $mc . '%');
                    }
                });
            }
        }

        // Property type filtering
        if ($type = $request->input('type')) {
            if ($type === 'flat') {
                $query->where(function($sub) {
                    $sub->where('configuration', 'like', '%bhk%')
                        ->orWhere('configuration', 'like', '%flat%')
                        ->orWhere('configuration', 'like', '%apartment%')
                        ->orWhere('title', 'like', '%flat%')
                        ->orWhere('title', 'like', '%apartment%')
                        ->orWhere('title', 'like', '%bhk%')
                        ->orWhere(function($sub2) {
                            $sub2->where('configuration', 'not like', '%villa%')
                                 ->where('configuration', 'not like', '%house%')
                                 ->where('title', 'not like', '%villa%')
                                 ->where('title', 'not like', '%house%')
                                 ->where('configuration', 'not like', '%plot%')
                                 ->where('configuration', 'not like', '%land%')
                                 ->where('title', 'not like', '%plot%')
                                 ->where('title', 'not like', '%land%');
                        });
                });
            } elseif ($type === 'villa') {
                $query->where(function($sub) {
                    $sub->where('configuration', 'like', '%villa%')
                        ->orWhere('configuration', 'like', '%house%')
                        ->orWhere('title', 'like', '%villa%')
                        ->orWhere('title', 'like', '%house%');
                })->where(function($sub) {
                    $sub->where('configuration', 'not like', '%bhk%')
                        ->where('configuration', 'not like', '%flat%')
                        ->where('configuration', 'not like', '%apartment%')
                        ->where('title', 'not like', '%bhk%')
                        ->where('title', 'not like', '%flat%')
                        ->where('title', 'not like', '%apartment%');
                });
            } elseif ($type === 'plot') {
                $query->where(function($sub) {
                    $sub->where('configuration', 'like', '%plot%')
                        ->orWhere('configuration', 'like', '%land%')
                        ->orWhere('title', 'like', '%plot%')
                        ->orWhere('title', 'like', '%land%');
                })->where(function($sub) {
                    $sub->where('configuration', 'not like', '%bhk%')
                        ->where('configuration', 'not like', '%flat%')
                        ->where('configuration', 'not like', '%apartment%')
                        ->where('title', 'not like', '%bhk%')
                        ->where('title', 'not like', '%flat%')
                        ->where('title', 'not like', '%apartment%');
                });
            }
        }

        // Budget range filtering
        if ($budget = $request->input('budget')) {
            if ($budget === '25-50') {
                $query->whereBetween('price', [2500000, 5000000]);
            } elseif ($budget === '50-100') {
                $query->whereBetween('price', [5000001, 10000000]);
            } elseif ($budget === '100+') {
                $query->where('price', '>', 10000000);
            }
        }

        $properties = $query->orderByDesc('is_featured')
            ->orderByDesc('created_at')
            ->paginate(9);

        return view('site.property', compact('properties'));
    }
}
