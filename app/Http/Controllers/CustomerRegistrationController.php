<?php

namespace App\Http\Controllers;

use App\Models\Address;
use App\Models\Client;
use App\Models\Household;
use Illuminate\Http\Request;

class CustomerRegistrationController extends Controller
{
    // Show registration form (index)
    // app/Http/Controllers/CustomerRegistrationController.php

    public function index()
    {
        // auth()->user()->id = Get logged-in user's ID from Laravel's auth system
        // Client::where('Id', X) = Find Client record where Id column = X
        // ->first() = Get first result or NULL if not found (no exception)
        // Query: SELECT * FROM Client WHERE Id = (logged_in_user_id) LIMIT 1
        $client = Client::where('Id', auth()->user()->id)->first();

        // Return view with $client variable
        // If no client exists, $client will be NULL and view shows registration form
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
            'total_members' => 'required|integer|min:1|max:20',
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
            // ===== STEP 1: CHECK IF CLIENT ALREADY EXISTS =====
            // auth()->user()->id = Currently logged-in user's ID
            // ::where() creates query, ::first() executes it and returns one record or NULL
            // If client already registered, redirect to update instead of creating duplicate
            $existingClient = Client::where('Id', auth()->user()->id)->first();
            if ($existingClient) {
                // Client already exists, call update() method instead (prevents duplicate)
                return $this->update($request, auth()->user()->id);
            }

            // ===== STEP 2: CREATE ADDRESS RECORD =====
            // new Address() = Instantiate model (in memory, not in DB yet)
            // $address->Street = ... = Set column values from validated form data
            // $address->save() = INSERT into Address table, returns object with Id populated
            $address = new Address;
            $address->Street = $validated['street'];
            $address->HouseNumber = $validated['house_number'];
            $address->PostalCode = $validated['postal_code'];
            $address->City = $validated['city'];
            $address->is_active = true;
            // SQL: INSERT INTO Address (Street, HouseNumber, PostalCode, City, is_active, created_at, updated_at) VALUES (...)
            $address->save();  // Now $address->Id is populated by database auto-increment

            // ===== STEP 3: CREATE CLIENT RECORD =====
            // UNIQUE DESIGN: Client.Id = User.id (intentional link to Laravel users table)
            // This makes: one User → one Client relationship
            // Query will be: INSERT INTO Client (Id, FirstName, LastName, Phone, AddressId, is_active, created_at, updated_at)
            $client = new Client;
            $client->Id = auth()->user()->id;  // Link to User.id - THIS IS THE KEY LINKING PATTERN
            $client->FirstName = $validated['first_name'];
            $client->LastName = $validated['last_name'];
            $client->Phone = $validated['phone'];
            $client->AddressId = $address->Id;  // Foreign key pointing to Address record we just created
            $client->is_active = true;
            // SQL: INSERT INTO Client (Id, FirstName, LastName, Phone, AddressId, is_active, created_at, updated_at) VALUES (1, 'Jan', 'Jansen', '0201234567', 1, true, NOW(), NOW())
            $client->save();

            // ===== STEP 4: CREATE HOUSEHOLD RECORD =====
            // Household = Family unit belonging to this Client
            // One Client has exactly ONE Household (one-to-one relationship)
            // CASCADE DELETE: If Client is deleted, Household is auto-deleted (foreign key constraint)
            $household = new Household;
            $household->ClientId = $client->Id;  // Link to Client (foreign key)
            $household->TotalMembers = $validated['total_members'];  // How many people in family
            $household->RegistrationDate = now();  // Current timestamp (Laravel's now() function)
            $household->is_active = true;
            // SQL: INSERT INTO Household (ClientId, TotalMembers, RegistrationDate, is_active, created_at, updated_at) VALUES (1, 3, NOW(), true, NOW(), NOW())
            $household->save();

            // ===== SUCCESS REDIRECT =====
            // redirect() = Create redirect response
            // ->route() = Go to named route 'customersregistration.index'
            // ->with() = Pass session data ('success' message shows once, then disappears)
            return redirect()->route('customersregistration.index')
                ->with('success', 'Registratie succesvol opgeslagen!');

        } catch (\Exception $e) {
            // ===== ERROR HANDLING =====
            // \Exception $e = Catch ANY exception (database error, validation error, etc.)
            // back() = Redirect user back to previous page (form page)
            // ->withErrors() = Pass errors to view (shows in {{ $errors->first('error') }})
            // ->withInput() = Preserve user's form data so they don't have to retype
            // $e->getMessage() = Get error message from exception
            return back()
                ->withErrors(['error' => 'Er is een fout opgetreden: '.$e->getMessage()])
                ->withInput();  // Keep form data in session for user to see
        }
    }

    public function update(Request $request, $id)
    {
        // ===== STEP 1: VALIDATE INPUT =====
        // Same validation as store(), but without required 'total_members' field (can be optional for updates)
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
            // ===== STEP 2: FIND CLIENT (or throw 404 if not found) =====
            // findOrFail($id) = Find by primary key (Id), throw exception if not found
            // This automatically returns 404 response if client doesn't exist
            $client = Client::findOrFail($id);

            // ===== STEP 3: UPDATE ADDRESS =====
            // Get Address record linked to this Client via AddressId foreign key
            $address = Address::findOrFail($client->AddressId);
            // update(array) = Update multiple columns in one query
            // Eloquent automatically sets updated_at column to current timestamp
            // SQL: UPDATE Address SET Street='...', HouseNumber='...', PostalCode='...', City='...', updated_at=NOW() WHERE Id=X
            $address->update([
                'Street' => $validated['street'],
                'HouseNumber' => $validated['house_number'],
                'PostalCode' => $validated['postal_code'],
                'City' => $validated['city'],
            ]);

            // ===== STEP 4: UPDATE CLIENT =====
            // SQL: UPDATE Client SET FirstName='...', LastName='...', Phone='...', updated_at=NOW() WHERE Id=X
            $client->update([
                'FirstName' => $validated['first_name'],
                'LastName' => $validated['last_name'],
                'Phone' => $validated['phone'],
            ]);

            // ===== STEP 5: UPDATE HOUSEHOLD =====
            // Find Household where ClientId matches (should only be one per client)
            // ->first() returns NULL if not found (doesn't throw exception)
            $household = Household::where('ClientId', $id)->first();
            if ($household) {
                // Only update if household exists (defensive check)
                // SQL: UPDATE Household SET TotalMembers=3, updated_at=NOW() WHERE ClientId=1
                $household->update([
                    'TotalMembers' => $validated['total_members'],
                ]);
            }

            // ===== SUCCESS REDIRECT =====
            return redirect()->route('customersregistration.index')
                ->with('success', 'Gegevens succesvol bijgewerkt!');

        } catch (\Exception $e) {
            // ===== ERROR HANDLING - Same as store() =====
            // back() = Return to previous page (the registration form they were editing)
            // withErrors() = Display error message to user
            // withInput() = Preserve form data so user doesn't lose what they typed
            return back()
                ->withErrors(['error' => 'Er is een fout opgetreden: '.$e->getMessage()])
                ->withInput();
        }
    }
}
