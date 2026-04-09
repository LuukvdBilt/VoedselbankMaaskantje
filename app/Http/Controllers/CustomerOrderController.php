<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\FoodPackageDistribution;
use App\Models\FoodpackageModel;
use App\Models\Household;
use Illuminate\Http\Request;

class CustomerOrderController extends Controller
{
    public function index($clientId)
    {
        $client = Client::findOrFail($clientId);

        $distributions = FoodPackageDistribution::whereHas('household', function ($query) use ($clientId) {
            $query->where('ClientId', $clientId);
        })
            ->with([
                'foodPackage' => function ($q) {
                    $q->with(['products', 'allergies']);
                },
                'household' => function ($q) {
                    $q->with('client.address');
                },
            ])
            ->orderBy('created_at', 'desc')
            ->get();

        return view('customersorders.index', [
            'client' => $client,
            'distributions' => $distributions,
        ]);
    }

    public function create($clientId)
    {
        $client = Client::findOrFail($clientId);
        $foodPackages = FoodpackageModel::where('is_active', true)
            ->with(['products', 'allergies'])
            ->get();

        return view('customersorders.create', [
            'client' => $client,
            'foodPackages' => $foodPackages,
        ]);
    }

    public function store(Request $request, $clientId)
    {
        $validated = $request->validate([
            'food_package_id' => 'required|exists:FoodPackages,Id',
            'wishes' => 'nullable|string|max:500',
            'postal_code' => 'nullable|string|max:20',
            'house_number' => 'nullable|string|max:10',
            'note' => 'nullable|string|max:500',
        ]);

        $household = Household::where('ClientId', $clientId)->firstOrFail();

        FoodPackageDistribution::create([
            'HouseholdId' => $household->Id,
            'FoodPackageId' => $validated['food_package_id'],
            'DistributionDate' => now(),
            'is_active' => true,
            'note' => $validated['note'] ?? null,
        ]);

        // Update address if postal_code or house_number provided
        if ($validated['postal_code'] || $validated['house_number']) {
            $client = Client::findOrFail($clientId);
            if ($client->address) {
                $client->address->update([
                    'PostalCode' => $validated['postal_code'] ?? $client->address->PostalCode,
                    'HouseNumber' => $validated['house_number'] ?? $client->address->HouseNumber,
                ]);
            }
        }

        return redirect()->route('customersorders.index', ['clientId' => $clientId])
            ->with('success', 'Bestelling succesvol geplaatst!');
    }

    public function edit($clientId, $orderId)
    {
        $client = Client::findOrFail($clientId);
        $distribution = FoodPackageDistribution::findOrFail($orderId);
        $foodPackages = FoodpackageModel::where('is_active', true)
            ->with(['products', 'allergies'])
            ->get();

        // Verify this distribution belongs to the client's household
        $household = Household::where('ClientId', $clientId)->firstOrFail();
        if ($distribution->HouseholdId != $household->Id) {
            abort(403, 'Unauthorized');
        }

        return view('customersorders.edit', [
            'client' => $client,
            'distribution' => $distribution,
            'foodPackages' => $foodPackages,
        ]);
    }

    public function update(Request $request, $clientId, $orderId)
    {
        $validated = $request->validate([
            'food_package_id' => 'required|exists:FoodPackages,Id',
            'wishes' => 'nullable|string|max:500',
            'postal_code' => 'nullable|string|max:20',
            'house_number' => 'nullable|string|max:10',
            'note' => 'nullable|string|max:500',
        ]);

        $distribution = FoodPackageDistribution::findOrFail($orderId);

        $household = Household::where('ClientId', $clientId)->firstOrFail();
        if ($distribution->HouseholdId != $household->Id) {
            abort(403, 'Unauthorized');
        }

        $distribution->update([
            'FoodPackageId' => $validated['food_package_id'],
            'note' => $validated['note'] ?? null,
        ]);

        // Update address if postal_code or house_number provided
        if ($validated['postal_code'] || $validated['house_number']) {
            $client = Client::findOrFail($clientId);
            if ($client->address) {
                $client->address->update([
                    'PostalCode' => $validated['postal_code'] ?? $client->address->PostalCode,
                    'HouseNumber' => $validated['house_number'] ?? $client->address->HouseNumber,
                ]);
            }
        }

        return redirect()->route('customersorders.index', $clientId)
            ->with('success', 'Bestelling succesvol bijgewerkt!');
    }

    public function destroy($clientId, $orderId)
    {
        $distribution = FoodPackageDistribution::findOrFail($orderId);

        // Verify this distribution belongs to the client's household
        $household = Household::where('ClientId', $clientId)->firstOrFail();
        if ($distribution->HouseholdId != $household->Id) {
            abort(403, 'Unauthorized');
        }

        $distribution->delete();

        return redirect()->route('customersorders.index', $clientId)
            ->with('success', 'Bestelling verwijderd!');
    }
}
