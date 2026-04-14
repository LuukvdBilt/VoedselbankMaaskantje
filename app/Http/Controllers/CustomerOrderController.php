<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\FoodPackageDistribution;
use App\Models\FoodpackageModel;
use App\Models\Household;
use Illuminate\Http\Request;

class CustomerOrderController extends Controller
{
    // Display all food package distributions for a client
    // app/Http/Controllers/CustomerOrderController.php

    public function index($clientId)
    {
        // Client::findOrFail($clientId) = Find Client by Id, throw 404 if not found
        // Query: SELECT * FROM Client WHERE Id = $clientId LIMIT 1
        $client = Client::findOrFail($clientId);

        // FoodPackageDistribution::whereHas() = Filter by related household
        // ->with() = Eager load relationships to avoid N+1 queries
        // Query: SELECT * FROM FoodPackageDistribution WHERE HouseholdId IN (SELECT Id FROM Household WHERE ClientId = $clientId) ORDER BY created_at DESC
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

    // Show form to create a new food package distribution
    public function create($clientId)
    {
        // Client::findOrFail($clientId) = Find Client by Id, throw 404 if not found
        // Query: SELECT * FROM Client WHERE Id = $clientId LIMIT 1
        $client = Client::findOrFail($clientId);
        
        // FoodPackage::where('is_active', true) = Get only active food packages
        // ->with() = Eager load relationships (products, allergies)
        // Query: SELECT * FROM FoodPackages WHERE is_active = true
        $foodPackages = FoodpackageModel::where('is_active', true)
            ->with(['products', 'allergies'])
            ->get();

        return view('customersorders.create', [
            'client' => $client,
            'foodPackages' => $foodPackages,
        ]);
    }

    // Store a new food package distribution in the database
    public function store(Request $request, $clientId)
    {
        // $request->validate() = Validate incoming request data
        // 'required|exists:FoodPackages,Id' = FoodPackageId must exist in FoodPackages table
        // All other fields are optional (nullable)
        $validated = $request->validate([
            'food_package_id' => 'required|exists:FoodPackages,Id',
            'postal_code' => 'nullable|string|max:20',
            'house_number' => 'nullable|string|max:10',
            'note' => 'nullable|string|max:500',
        ], [
            'food_package_id.required' => 'Voedselpakket is verplicht.',
            'food_package_id.exists' => 'Het geselecteerde voedselpakket bestaat niet.',
            'postal_code.max' => 'Postcode mag niet langer zijn dan 20 karakters.',
            'house_number.max' => 'Huisnummer mag niet langer zijn dan 10 karakters.',
            'note.max' => 'Opmerking mag niet langer zijn dan 500 karakters.',
        ]);

        try {
            // Household::where('ClientId', $clientId)->firstOrFail() = Find household for this client
            // Query: SELECT * FROM Household WHERE ClientId = $clientId LIMIT 1
            $household = Household::where('ClientId', $clientId)->firstOrFail();

            // FoodPackageDistribution::create() = Create new distribution record
            // INSERT INTO FoodPackageDistribution (HouseholdId, FoodPackageId, DistributionDate, is_active, note) VALUES (...)
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

        } catch (\Exception $e) {
            return back()
                ->withErrors(['error' => 'De ingevulde gegevens zijn niet geldig. Vul alle verplichte velden in.'])
                ->withInput();
        }
    }

    // Show form to edit an existing food package distribution
    public function edit($clientId, $orderId)
    {
        // Client::findOrFail($clientId) = Find Client by Id, throw 404 if not found
        // Query: SELECT * FROM Client WHERE Id = $clientId LIMIT 1
        $client = Client::findOrFail($clientId);
        
        // FoodPackageDistribution::findOrFail($orderId) = Find distribution by Id
        // Query: SELECT * FROM FoodPackageDistribution WHERE Id = $orderId LIMIT 1
        $distribution = FoodPackageDistribution::findOrFail($orderId);
        
        // Load active food packages with relationships
        $foodPackages = FoodpackageModel::where('is_active', true)
            ->with(['products', 'allergies'])
            ->get();

        // Verify authorization: distribution must belong to this client's household
        $household = Household::where('ClientId', $clientId)->firstOrFail();
        if ($distribution->HouseholdId != $household->Id) {
            // Return 403 Forbidden if unauthorized
            abort(403, 'Unauthorized');
        }

        return view('customersorders.edit', [
            'client' => $client,
            'distribution' => $distribution,
            'foodPackages' => $foodPackages,
        ]);
    }

    // Update an existing food package distribution
    public function update(Request $request, $clientId, $orderId)
    {
        // $request->validate() = Validate incoming request data
        $validated = $request->validate([
            'food_package_id' => 'required|exists:FoodPackages,Id',
            'postal_code' => 'nullable|string|max:20',
            'house_number' => 'nullable|string|max:10',
            'note' => 'nullable|string|max:500',
        ], [
            'food_package_id.required' => 'Voedselpakket is verplicht.',
            'food_package_id.exists' => 'Het geselecteerde voedselpakket bestaat niet.',
            'postal_code.max' => 'Postcode mag niet langer zijn dan 20 karakters.',
            'house_number.max' => 'Huisnummer mag niet langer zijn dan 10 karakters.',
            'note.max' => 'Opmerking mag niet langer zijn dan 500 karakters.',
        ]);

        try {
            // FoodPackageDistribution::findOrFail($orderId) = Find distribution by Id
            $distribution = FoodPackageDistribution::findOrFail($orderId);

            // Verify authorization: distribution must belong to this client's household
            $household = Household::where('ClientId', $clientId)->firstOrFail();
            if ($distribution->HouseholdId != $household->Id) {
                abort(403, 'Unauthorized');
            }

            // Check if order can be deleted based on status
            if ($distribution->is_active === false) {
                return back()
                    ->withErrors(['error' => 'Je kunt deze bestelling niet wijzigen vanwege de huidige status.']);
            }

            // $distribution->update() = Update distribution record
            // UPDATE FoodPackageDistribution SET FoodPackageId = ..., note = ... WHERE Id = $orderId
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

        } catch (\Exception $e) {
            return back()
                ->withErrors(['error' => 'De ingevulde gegevens zijn niet geldig. Vul alle verplichte velden in.'])
                ->withInput();
        }
    }

    // Delete a food package distribution
    public function destroy($clientId, $orderId)
    {
        try {
            // FoodPackageDistribution::findOrFail($orderId) = Find distribution by Id
            $distribution = FoodPackageDistribution::findOrFail($orderId);

            // Verify authorization: distribution must belong to this client's household
            $household = Household::where('ClientId', $clientId)->firstOrFail();
            if ($distribution->HouseholdId != $household->Id) {
                abort(403, 'Unauthorized');
            }

            // Check if order can be deleted based on status
            if ($distribution->is_active === false) {
                return redirect()->route('customersorders.index', $clientId)
                    ->with('error', 'Je kunt deze bestelling niet verwijderen vanwege de huidige status.');
            }

            // $distribution->delete() = Delete the distribution record
            // DELETE FROM FoodPackageDistribution WHERE Id = $orderId
            $distribution->delete();

            return redirect()->route('customersorders.index', $clientId)
                ->with('success', 'Bestelling verwijderd!');

        } catch (\Exception $e) {
            return redirect()->route('customersorders.index', $clientId)
                ->with('error', 'Er is een fout opgetreden bij het verwijderen van de bestelling.');
        }
    }

}
