<?php

namespace App\Http\Controllers;

// app/Http/Controllers/CustomerOrderController.php

use App\Models\Client;
use App\Models\FoodPackageDistribution;
use App\Models\FoodPackage;
use Illuminate\Http\Request;

class CustomerOrderController extends Controller
{
    // Show all orders for a customer
    public function index($clientId)
    {
        $client = Client::getClientById($clientId);

        if (!$client) {
            return view('errors.404', ['message' => 'Deze pagina bestaat niet.']);
        }

        $orders = FoodPackageDistribution::getHouseholdDistributions($client->HouseholdId);

        return view('customers.orders.index', [
            'client' => $client,
            'orders' => $orders
        ]);
    }

    // Show create order form
    public function create($clientId)
    {
        $client = Client::getClientById($clientId);

        if (!$client) {
            return view('errors.404', ['message' => 'Deze pagina bestaat niet.']);
        }

        $packages = FoodPackage::getAllPackages();

        return view('customers.orders.create', [
            'client' => $client,
            'packages' => $packages
        ]);
    }

    // Store new order
    public function store(Request $request, $clientId)
    {
        $client = Client::getClientById($clientId);

        if (!$client) {
            return back()->withErrors(['error' => 'Klant niet gevonden.']);
        }

        $validated = $request->validate([
            'food_package_id' => 'required|integer'
        ], [
            'food_package_id.required' => 'Selecteer een voedselpakket.'
        ]);

        $orderId = FoodPackageDistribution::createDistribution(
            $client->HouseholdId,
            $validated['food_package_id']
        );

        if ($orderId) {
            return redirect()->route('customers.orders.index', $clientId)
                ->with('success', 'Bestelling succesvol aangemaakt!');
        }

        return back()->withErrors(['error' => 'De ingevulde gegevens zijn niet geldig. Vul alle verplichte velden in.']);
    }

    // Show edit order form
    public function edit($clientId, $orderId)
    {
        $client = Client::getClientById($clientId);

        if (!$client) {
            return view('errors.404', ['message' => 'Deze pagina bestaat niet.']);
        }

        $order = FoodPackageDistribution::find($orderId);

        if (!$order || $order->HouseholdId != $client->HouseholdId) {
            return view('errors.404', ['message' => 'Bestelling niet gevonden.']);
        }

        $packages = FoodPackage::getAllPackages();

        return view('customers.orders.edit', [
            'client' => $client,
            'order' => $order,
            'packages' => $packages
        ]);
    }

    // Update order
    public function update(Request $request, $clientId, $orderId)
    {
        $validated = $request->validate([
            'food_package_id' => 'required|integer'
        ], [
            'food_package_id.required' => 'Selecteer een voedselpakket.'
        ]);

        $success = FoodPackageDistribution::updateDistribution(
            $orderId,
            $validated['food_package_id'],
            $request->input('note')
        );

        if ($success) {
            return redirect()->route('customers.orders.index', $clientId)
                ->with('success', 'Bestelling succesvol bijgewerkt!');
        }

        return back()->withErrors(['error' => 'De ingevulde gegevens zijn niet geldig. Vul alle verplichte velden in.']);
    }

    // Delete order
    public function destroy($clientId, $orderId)
    {
        $success = FoodPackageDistribution::deleteDistribution($orderId);

        if ($success) {
            return redirect()->route('customers.orders.index', $clientId)
                ->with('success', 'Bestelling verwijderd.');
        }

        return back()->withErrors(['error' => 'Bestelling kon niet worden verwijderd.']);
    }
}