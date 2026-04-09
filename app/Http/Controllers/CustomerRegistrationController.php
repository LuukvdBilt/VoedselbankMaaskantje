<?php

namespace App\Http\Controllers;


use App\Models\Client;
use App\Models\Address;
use App\Models\Household;
use Illuminate\Http\Request;

class CustomerRegistrationController extends Controller
{
    // Show registration form (index)
    // app/Http/Controllers/CustomerRegistrationController.php

public function index()
{
    $client = Client::where('Id', auth()->user()->id)->first();
    return view('customersregistration.index', ['client' => $client]);
}

public function store(Request $request)
{
    $validated = $request->validate([
        'first_name' => 'required|string|max:255',
        'last_name' => 'required|string|max:255',
        'phone' => 'required|string|max:12',
        'street' => 'required|string|max:255',
        'house_number' => 'required|string|max:10',
        'postal_code' => 'required|string|max:20',
        'city' => 'required|string|max:255',
        'total_members' => 'required|integer|min:1|max:20'
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

    try {
        // Check if client already exists
        $existingClient = Client::where('Id', auth()->user()->id)->first();
        if ($existingClient) {
            // UPDATE existing client instead of error
            return $this->update($request, auth()->user()->id);
        }

        // Create Address
        $address = new Address();
        $address->Street = $validated['street'];
        $address->HouseNumber = $validated['house_number'];
        $address->PostalCode = $validated['postal_code'];
        $address->City = $validated['city'];
        $address->is_active = true;
        $address->save();

        // Create Client with User ID
        $client = new Client();
        $client->Id = auth()->user()->id;
        $client->FirstName = $validated['first_name'];
        $client->LastName = $validated['last_name'];
        $client->Phone = $validated['phone'];
        $client->AddressId = $address->Id;
        $client->is_active = true;
        $client->save();

        // Create Household
        $household = new Household();
        $household->ClientId = $client->Id;
        $household->TotalMembers = $validated['total_members'];
        $household->RegistrationDate = now();
        $household->is_active = true;
        $household->save();

        return redirect()->route('customersregistration.index')
            ->with('success', 'Registratie succesvol opgeslagen!');
    } catch (\Exception $e) {
        return back()->withErrors(['error' => 'Er is een fout opgetreden: ' . $e->getMessage()])->withInput();
    }
}

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
        'total_members' => 'required|integer|min:1',
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

    try {
        $client = Client::findOrFail($id);
        
        // Update Address
        $address = Address::findOrFail($client->AddressId);
        $address->update([
            'Street' => $validated['street'],
            'HouseNumber' => $validated['house_number'],
            'PostalCode' => $validated['postal_code'],
            'City' => $validated['city'],
        ]);

        // Update Client
        $client->update([
            'FirstName' => $validated['first_name'],
            'LastName' => $validated['last_name'],
            'Phone' => $validated['phone'],
        ]);

        // Update Household
        $household = Household::where('ClientId', $id)->first();
        if ($household) {
            $household->update([
                'TotalMembers' => $validated['total_members'],
            ]);
        }

        return redirect()->route('customersregistration.index')
            ->with('success', 'Gegevens succesvol bijgewerkt!');
    } catch (\Exception $e) {
        return back()->withErrors(['error' => 'Er is een fout opgetreden: ' . $e->getMessage()])->withInput();
    }
}
}