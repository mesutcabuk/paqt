<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Taxi;
use App\Models\Ride;
use App\Models\Parcel;
use Illuminate\Support\Facades\Log;

class TaxiController extends Controller {
    /**
     * Get all taxi companies.
     * This function get all taxi companies from database and return them as JSON.
     * Used to list all available taxi providers.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index() {
        Log::info('Fetching all taxi companies');

        $companies = Taxi::all();

        return response()->json($companies);
    }

    /**
     * Get all rides assigned to a taxi company.
     * We look for all rides that has a taxi company id matching to given id.
     * If rides exist, return them, if not, return a warning message.
     *
     * @param int $company_id The ID of the taxi company.
     * @return \Illuminate\Http\JsonResponse JSON response with rides or error message.
     */
    public function getRides($company_id) {
        Log::info('Fetching rides for taxi company', ['company_id' => $company_id]);

        $rides = Ride::where('taxi_id', $company_id)->get();

        if ($rides->isEmpty()) {
            Log::warning('No rides found for taxi company', ['company_id' => $company_id]);
            return response()->json(['message' => 'No rides found for this taxi company'], 404);
        }

        return response()->json($rides);
    }

    /**
     * Get the taxi company responsible for a given parcel ID.
     * First we find the parcel, then check if a taxi company is assigned.
     * If found, return it, otherwise return an error.
     *
     * @param int $parcel_id The ID of the parcel.
     * @return \Illuminate\Http\JsonResponse JSON response with taxi info or error.
     */
    public function getTaxiByParcel($parcel_id) {
        Log::info('Fetching taxi company for parcel', ['parcel_id' => $parcel_id]);

        $parcel = Parcel::find($parcel_id);

        if (!$parcel) {
            Log::warning('Parcel not found', ['parcel_id' => $parcel_id]);
            return response()->json(['error' => 'Parcel not found'], 404);
        }

        $taxi = Taxi::where('parcel_id', $parcel_id)->first();

        if (!$taxi) {
            Log::warning('No taxi company assigned to parcel', ['parcel_id' => $parcel_id]);
            return response()->json(['error' => 'No taxi company assigned to this parcel'], 404);
        }

        return response()->json($taxi);
    }

    /**
     * Get the taxi company responsible for a given postcode.
     * We try to find a parcel matching the postcode.
     * If it exist, check if a taxi is assigned and return it.
     *
     * @param string $postcode The postcode to search.
     * @return \Illuminate\Http\JsonResponse JSON response with taxi or error message.
     */
    public function getTaxiByPostcode($postcode) {
        Log::info('Fetching taxi company for postcode', ['postcode' => $postcode]);

        $parcel = Parcel::where('postcode', $postcode)->first();

        if (!$parcel) {
            Log::warning('Parcel not found for postcode', ['postcode' => $postcode]);
            return response()->json(['error' => 'Parcel not found for this postcode'], 404);
        }

        $taxi = Taxi::where('parcel_id', $parcel->id)->first();

        if (!$taxi) {
            Log::warning('No taxi company assigned to postcode', ['postcode' => $postcode]);
            return response()->json(['error' => 'No taxi company assigned to this postcode'], 404);
        }

        return response()->json($taxi);
    }
}
