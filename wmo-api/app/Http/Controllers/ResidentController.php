<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Resident;
use Illuminate\Support\Facades\Log;

class ResidentController extends Controller {
    /**
     * Get all residents.
     * This function retrieves all residents from the database and returns them as JSON.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index() {
        Log::info('Fetching all residents');

        $residents = Resident::all();

        return response()->json($residents);
    }

    /**
     * Get details of a single resident.
     * Retrieves information about a specific resident using the resident ID.
     *
     * @param int $id The ID of the resident.
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id) {
        Log::info('Fetching resident details', ['resident_id' => $id]);

        $resident = Resident::find($id);

        if (!$resident) {
            Log::warning('Resident not found', ['resident_id' => $id]);
            return response()->json(['error' => 'Resident not found'], 404);
        }

        return response()->json($resident);
    }

    /**
     * Update resident details.
     * Allows updating a resident's information such as name, email, or address.
     *
     * @param \Illuminate\Http\Request $request The request object containing new data.
     * @param int $id The ID of the resident to update.
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, $id) {
        Log::info('Updating resident details', ['resident_id' => $id]);

        $resident = Resident::find($id);

        if (!$resident) {
            Log::warning('Resident not found for update', ['resident_id' => $id]);
            return response()->json(['error' => 'Resident not found'], 404);
        }

        $resident->update($request->all());

        return response()->json(['message' => 'Resident updated successfully', 'resident' => $resident]);
    }

    /**
     * Delete a resident.
     * Removes a resident from the system.
     *
     * @param int $id The ID of the resident to delete.
     * @return \Illuminate\Http\JsonResponse
     */
    public function delete($id) {
        Log::info('Deleting resident', ['resident_id' => $id]);

        $resident = Resident::find($id);

        if (!$resident) {
            Log::warning('Resident not found for deletion', ['resident_id' => $id]);
            return response()->json(['error' => 'Resident not found'], 404);
        }

        $resident->delete();

        return response()->json(['message' => 'Resident deleted successfully']);
    }

    /**
     * Get remaining budget of a resident.
     * Retrieves the remaining travel budget for a given resident.
     *
     * @param int $id The ID of the resident.
     * @return \Illuminate\Http\JsonResponse
     */
    public function getBudget($id) {
        Log::info('Fetching resident budget', ['resident_id' => $id]);

        $resident = Resident::find($id);

        if (!$resident) {
            Log::warning('Resident not found for budget check', ['resident_id' => $id]);
            return response()->json(['error' => 'Resident not found'], 404);
        }

        return response()->json(['resident_id' => $id, 'remaining_budget' => $resident->remaining_budget]);
    }

    /**
     * Reset budget for a single resident.
     * Resets the resident's remaining travel budget to the default value.
     *
     * @param int $id The ID of the resident.
     * @return \Illuminate\Http\JsonResponse
     */
    public function resetBudget($id) {
        Log::info('Resetting budget for resident', ['resident_id' => $id]);

        $resident = Resident::find($id);

        if (!$resident) {
            Log::warning('Resident not found for budget reset', ['resident_id' => $id]);
            return response()->json(['error' => 'Resident not found'], 404);
        }

        $defaultBudget = config('budget.default_km', 5000);
        $resident->remaining_budget = $defaultBudget;
        $resident->save();

        return response()->json(['message' => 'Resident budget reset successfully', 'resident_id' => $id]);
    }

    /**
     * Book a ride for a resident.
     * Logs a ride booking request, but currently does not process the booking.
     *
     * @param \Illuminate\Http\Request $request The request containing ride details.
     * @return \Illuminate\Http\JsonResponse
     */
    public function bookRide(Request $request) {
        $validated = $request->validate([
            'resident_id' => 'required|exists:residents,id',
            'pickup_address' => 'required|string',
            'destination' => 'required|string',
            'distance_km' => 'required|integer|min:1'
        ]);

        Log::info('Ride booking requested', ['resident_id' => $validated['resident_id']]);

        return response()->json(['message' => 'Ride booked successfully']);
    }
}
