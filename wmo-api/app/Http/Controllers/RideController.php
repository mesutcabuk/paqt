<?php 
namespace App\Http\Controllers;

use App\Models\Ride;
use App\Models\Resident;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use App\Services\GeoDistanceService;

class RideController extends Controller
{
 

    /**
     * Book a new ride for a resident with real-time distance validation.
     * This is something I used a couple years ago for a friend whi has a taxi company
     * something like, his clients was entering the pickup and destination addresses and I was checking 
     * Google Maps API to get the distance, calculate the price and give info about the traffic, etc..
     * 
     * Validates the request, checks if the resident has enough budget,
     * verifies the actual distance using Google Maps API, and creates a ride entry.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function bookRide(Request $request)
    {
        $validated = $request->validate([
            'resident_id' => 'required|exists:residents,id',
            'pickup_address' => 'required|string',
            'destination' => 'required|string',
            'distance_km' => 'required|integer|min:1'
        ]);

        $resident = Resident::find($validated['resident_id']);

        if ($resident->remaining_budget < $validated['distance_km']) {
            Log::warning('Ride booking failed - Insufficient budget', [
                'resident_id' => $validated['resident_id'],
                'requested_distance' => $validated['distance_km'],
                'remaining_budget' => $resident->remaining_budget
            ]);

            return response()->json(['error' => 'Insufficient budget'], 400);
        }

        // Validate distance using Google Maps API
        $actualDistanceMeters = GeoDistanceService::getDistance($validated['pickup_address'], $validated['destination']);

        if (is_null($actualDistanceMeters)) {
            Log::error('Ride booking failed - Unable to verify distance', [
                'resident_id' => $validated['resident_id'],
                'pickup_address' => $validated['pickup_address'],
                'destination' => $validated['destination']
            ]);
            return response()->json(['error' => 'Unable to verify distance. Please try again.'], 500);
        }

        // Convert meters to km (1 km = 1000 meters)
        $actualDistanceKm = ceil($actualDistanceMeters / 1000);

        if ($actualDistanceKm > $validated['distance_km']) {
            Log::warning('Ride booking failed - Entered distance does not match actual distance', [
                'resident_id' => $validated['resident_id'],
                'entered_distance' => $validated['distance_km'],
                'actual_distance' => $actualDistanceKm
            ]);

            return response()->json(['error' => 'Entered distance does not match actual distance of ' . $actualDistanceKm . ' km'], 400);
        }

        // Deduct budget and save the resident update
        $resident->remaining_budget -= $actualDistanceKm;
        $resident->save();

        // Create the ride entry
        $ride = Ride::create([
            'resident_id' => $validated['resident_id'],
            'pickup_address' => $validated['pickup_address'],
            'destination' => $validated['destination'],
            'distance_km' => $actualDistanceKm,
            'status' => 'Pending',
            'created_at' => Carbon::now()
        ]);

        Log::info('Ride successfully booked', [
            'ride_id' => $ride->id,
            'resident_id' => $validated['resident_id'],
            'actual_distance_km' => $actualDistanceKm
        ]);

        return response()->json(['message' => 'Ride successfully booked', 'ride' => $ride], 201);
    }


    /**
     * *** some ideas to extend the functionality ***
     * Get details of a specific ride.
     * Retrieves the ride details based on ride ID.
     *
     * @param int $id The ride ID.
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        Log::info('Fetching ride details', ['ride_id' => $id]);

        $ride = Ride::find($id);

        if (!$ride) {
            Log::warning('Ride not found', ['ride_id' => $id]);
            return response()->json(['error' => 'Ride not found'], 404);
        }

        return response()->json($ride);
    }

    /**
     * Update a ride's details. 
     * not asked in the opdracht but I've added
     * 
     * Allows updating ride status, pickup time, or other details.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id The ride ID.
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, $id)
    {
        Log::info('Updating ride details', ['ride_id' => $id]);

        $ride = Ride::find($id);

        if (!$ride) {
            Log::warning('Ride not found for update', ['ride_id' => $id]);
            return response()->json(['error' => 'Ride not found'], 404);
        }

        $ride->update($request->all());

        return response()->json(['message' => 'Ride updated successfully', 'ride' => $ride]);
    }

    /**
     * Cancel a ride.
     * not asked in the opdracht but I've added for a logic which is used right now for WMO taxi services in the nethelands
     * I know becasue my mother in law is using their service in Amsterdam
     * 
     * If canceled more than 24 hours in advance, refund deducted km.
     * If canceled less than 24 hours before, mark as revoked but do not refund.
     *
     * @param int $id The ride ID.
     * @return \Illuminate\Http\JsonResponse
     */
    public function cancelRide($id)
    {
        Log::info('Attempting to cancel ride', ['ride_id' => $id]);

        $ride = Ride::find($id);

        if (!$ride) {
            Log::warning('Ride not found for cancellation', ['ride_id' => $id]);
            return response()->json(['error' => 'Ride not found'], 404);
        }

        $resident = Resident::find($ride->resident_id);
        $timeDifference = Carbon::now()->diffInHours(Carbon::parse($ride->created_at));

        if ($timeDifference >= 24) {
            // Refund budget
            $resident->remaining_budget += $ride->distance_km;
            $ride->status = 'Cancelled';
        } else {
            // Mark as revoked, no refund
            $ride->status = 'Revoked';
        }

        $ride->save();
        $resident->save();

        Log::info('Ride cancellation processed', [
            'ride_id' => $id,
            'resident_id' => $ride->resident_id,
            'status' => $ride->status,
            'budget_refunded' => ($timeDifference >= 24) ? $ride->distance_km : 0
        ]);

        return response()->json(['message' => 'Ride cancellation processed', 'ride' => $ride]);
    }

    /**
     * Sample reporting feature : : Get all rides for a given resident.
     *
     * @param int $residentId The resident's ID.
     * @return \Illuminate\Http\JsonResponse
     */
    public function getRidesByResident($residentId)
    {
        Log::info('Fetching rides for resident', ['resident_id' => $residentId]);

        $rides = Ride::where('resident_id', $residentId)->get();

        if ($rides->isEmpty()) {
            Log::warning('No rides found for resident', ['resident_id' => $residentId]);
            return response()->json(['message' => 'No rides found for this resident'], 404);
        }

        return response()->json($rides);
    }

    /**
     * Sample reporting feature : Get all rides assigned to a specific taxi company.
     *
     * @param int $taxiId The taxi company's ID.
     * @return \Illuminate\Http\JsonResponse
     */
    public function getRidesByTaxi($taxiId)
    {
        Log::info('Fetching rides for taxi company', ['taxi_id' => $taxiId]);

        $rides = Ride::where('taxi_id', $taxiId)->get();

        if ($rides->isEmpty()) {
            Log::warning('No rides found for taxi company', ['taxi_id' => $taxiId]);
            return response()->json(['message' => 'No rides found for this taxi company'], 404);
        }

        return response()->json($rides);
    }
}

    