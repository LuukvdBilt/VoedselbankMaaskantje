<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\FoodPackageDistribution;
use Illuminate\Http\Request;

class CustomerOrderController extends Controller
{
    public function index($clientId)
    {
        $client = Client::findOrFail($clientId);
        
        // Get distributions for this client's household
        $distributions = FoodPackageDistribution::whereHas('household', function ($query) use ($clientId) {
            $query->where('ClientId', $clientId);
        })->with('foodPackage', 'household')->get();

        return view('customersorders.index', [
            'client' => $client,
            'distributions' => $distributions
        ]);
    }

    public function create($clientId)
    {
        $client = Client::findOrFail($clientId);
        return view('customersorders.create', ['client' => $client]);
    }

    public function store(Request $request, $clientId)
    {
        $validated = $request->validate([
            'food_package_id' => 'required|exists:FoodPackage,Id',
            'quantity' => 'required|integer|min:1',
        ]);

        $household = Household::where('ClientId', $clientId)->firstOrFail();

        FoodPackageDistribution::create([
            'HouseholdId' => $household->Id,
            'FoodPackageId' => $validated['food_package_id'],
            'DistributionDate' => now(),
            'Quantity' => $validated['quantity'],
            'is_active' => true,
        ]);

        return redirect()->route('customersorders.index', $clientId)
            ->with('success', 'Bestelling succesvol geplaatst!');
    }
}