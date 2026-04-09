

<?php

namespace App\Http\Controllers;

// app/Http/Controllers/CustomerRegistrationController.php

use App\Models\Client;
use Illuminate\Http\Request;

class CustomerRegistrationController extends Controller
{
    // Show all clients
    public function index()
    {
        $clients = Client::getAllClients();
        return view('customers.registration.index', ['clients' => $clients]);
    }

    // Show client registration details
    public function show($id)
    {
        $client = Client::getClientById($id);

        if (!$client) {
            return view('errors.404', ['message' => 'Deze pagina bestaat niet.']);
        }

        $members = $client->HouseholdId ? Client::getMembers($client->HouseholdId) : [];

        return view('customers.registration.show', [
            'client' => $client,
            'members' => $members
        ]);
    }

    // Show edit form
    public function edit($id)
    {
        $client = Client::getClientById($id);

        if (!$client) {
            return view('errors.404', ['message' => 'Deze pagina bestaat niet.']);
        }

        return view('customers.registration.edit', ['client' => $client]);
    }

    // Update client registration
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'phone' => 'required|string|max:12',
            'street' => 'required|string|max:255',
            'house_number' => 'required|string|max:10',
            'postal_code' => 'required|string|max:20',
            'city' => 'required|string|max:255',
            'total_members' => 'required|integer|min:1'
        ], [
            'first_name.required' => 'Voornaam is verplicht.',
            'last_name.required' => 'Achternaam is verplicht.',
            'phone.required' => 'Telefoonnummer is verplicht.',
            'street.required' => 'Straat is verplicht.',
            'house_number.required' => 'Huisnummer is verplicht.',
            'postal_code.required' => 'Postcode is verplicht.',
            'city.required' => 'Stad is verplicht.',
            'total_members.required' => 'Aantal gezinsleden is verplicht.',
        ]);

        $success = Client::updateClientInfo($id, $validated);

        if ($success) {
            return redirect()->route('customers.registration.show', $id)
                ->with('success', 'Gegevens succesvol bijgewerkt!');
        }

        return back()->withErrors(['error' => 'De ingevoerde gegevens zijn niet geldig. Controleer de verplichte velden en probeer het opnieuw.']);
    }
}