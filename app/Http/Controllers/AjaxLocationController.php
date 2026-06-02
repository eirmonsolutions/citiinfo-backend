<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class AjaxLocationController extends Controller
{
    public function cityByCoords(Request $request)
    {
        $lat = $request->query('lat');
        $lng = $request->query('lng');

        $key = config('services.google_maps.key');

        $url = "https://maps.googleapis.com/maps/api/geocode/json?latlng={$lat},{$lng}&key={$key}";
        $res = \Illuminate\Support\Facades\Http::get($url)->json();

        $city = null;

        if (!empty($res['results'][0]['address_components'])) {
            foreach ($res['results'][0]['address_components'] as $comp) {
                if (in_array('locality', $comp['types']) || in_array('postal_town', $comp['types'])) {
                    $city = $comp['long_name'];
                    break;
                }
            }
        }

        return response()->json(['city' => $city]);
    }
}
