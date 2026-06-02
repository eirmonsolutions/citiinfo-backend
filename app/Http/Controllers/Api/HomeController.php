<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\City;

class HomeController extends Controller
{
    public function homeCities()
    {
        $cities = [
            [
                'name' => 'Melbourne',
                'slug' => 'melbourne',
                'state_id' => 3903,
            ],
            [
                'name' => 'Brisbane',
                'slug' => 'brisbane',
                'state_id' => 3905,
            ],
            [
                'name' => 'Sydney',
                'slug' => 'sydney',
                'state_id' => 3906,
            ],
            [
                'name' => 'Perth',
                'slug' => 'perth',
                'state_id' => 3904,
            ],
        ];

        $data = collect($cities)->map(function ($city) {

            $count = \App\Models\BusinessListing::where('status', 'published')
                ->where('is_allowed', 1)
                ->where('state', $city['state_id'])
                ->count();

            return [
                'name' => $city['name'],
                'slug' => $city['slug'],
                'listings_count' => $count,
            ];
        });

        return response()->json([
            'status' => true,
            'data' => $data,
        ]);
    }
}
