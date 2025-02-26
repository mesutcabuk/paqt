<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class GeoDistanceService
{
    /**
     * Get the driving distance between two addresses using Google Maps API.
     *
     * @param string $origin The starting address (or lat,long coordinates).
     * @param string $destination The destination address (or lat,long coordinates).
     * @return int|null Distance in meters, or null if failed.
     */
    public static function getDistance($origin, $destination)
    {
        $apiKey = config('services.google_maps.api_key');
        $baseUrl = config('services.google_maps.base_url');

        $url = "{$baseUrl}?origins=" . urlencode($origin) . "&destinations=" . urlencode($destination) . "&key=" . $apiKey;

        Log::info('Requesting distance from Google Maps API', ['url' => $url]);

        $response = Http::get($url);

        if ($response->failed()) {
            Log::error('Google Maps API request failed', ['response' => $response->body()]);
            return null;
        }

        $data = $response->json();

        if (isset($data['rows'][0]['elements'][0]['distance']['value'])) {
            return $data['rows'][0]['elements'][0]['distance']['value']; // Distance in meters
        }

        Log::warning('Google Maps API response does not contain expected data', ['response' => $data]);
        return null;
    }
}
