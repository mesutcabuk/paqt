<?php 
namespace App\Http\Controllers;

use App\Models\Resident;
use Illuminate\Http\Request;
use App\Helpers\YamlConfig;
use Illuminate\Support\Facades\Log;

class BudgetController extends Controller
{
    /**
     * Reset budgets for all residents.
     * resets the remaining budget for all active residents
     * to the default value defined in the configuration.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function resetBudgets()
    {
        $defaultBudget = YamlConfig::get('budget.default_km', 5000);

        // Log the reset action
        Log::info('Resetting all resident budgets', ['default_budget' => $defaultBudget]);

        Resident::whereNotNull('id')->update(['remaining_budget' => $defaultBudget]);

        return response()->json(['message' => 'All active budgets have been reset'], 200);
    }

    /**
     * Reset budget for a single resident.
     * This function resets the remaining budget for a specific resident.
     *
     * @param int $residentId
     * @return \Illuminate\Http\JsonResponse
     */
    public function resetResidentBudget($residentId)
    {
        $defaultBudget = YamlConfig::get('budget.default_km', 5000);
        $resident = Resident::find($residentId);

        if (!$resident) {
            Log::warning('Attempted to reset budget for non-existent resident', ['resident_id' => $residentId]);
            return response()->json(['error' => 'Resident not found'], 404);
        }

        // Log individual reset action
        Log::info('Resetting budget for a resident', ['resident_id' => $residentId, 'default_budget' => $defaultBudget]);

        $resident->update(['remaining_budget' => $defaultBudget]);

        return response()->json(['message' => 'Resident budget has been reset', 'resident_id' => $residentId], 200);
    }
}
