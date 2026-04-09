<?php

namespace App\Http\Controllers;

use App\Models\ContactModel;
use App\Models\SupplierModel;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

class SupplierController extends Controller
{
    private $SupplierModel;

    public function __construct(private SupplierModel $supplier) {}

    /**
     * Display a paginated list of all suppliers.
     */
    public function index(Request $request)
    {
        try {
            $perPage = 6;
            $page = $request->get('page', 1);

            // Fetch all suppliers from the database
            $allSuppliers = collect($this->supplier->getAllSuppliers());
            $offset = ($page - 1) * $perPage;
            $suppliers = $allSuppliers->slice($offset, $perPage)->values();

            // Create paginated collection
            $suppliersPaginated = new LengthAwarePaginator(
                $suppliers,
                $allSuppliers->count(),
                $perPage,
                $page,
                ['path' => $request->url(), 'query' => $request->query()]
            );

            Log::info('Suppliers retrieved successfully', ['page' => $page, 'total' => $allSuppliers->count()]);

            return view('supplier.index', ['suppliers' => $suppliersPaginated]);
        } catch (\Exception $e) {
            Log::error('Error retrieving suppliers', ['error' => $e->getMessage()]);

            return redirect()->route('supplier.index')->with('error', 'Er is een fout opgetreden bij het ophalen van leveranciers.');
        }
    }

    /**
     * Show the form for creating a new supplier.
     */
    public function create()
    {
        try {
            $suppliers = $this->supplier->getAllSuppliers();

            Log::info('Create supplier form loaded');

            return view('supplier.create', [
                'suppliers' => $suppliers,
            ]);
        } catch (\Exception $e) {
            Log::error('Error loading create supplier form', ['error' => $e->getMessage()]);

            return redirect()->route('supplier.index')->with('error', 'Er is een fout opgetreden bij het laden van het formulier.');
        }
    }

    /**
     * Store a newly created supplier in the database.
     */
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'CompanyName' => 'required|string|max:255',
                'FirstName' => 'required|string|max:255',
                'LastName' => 'required|string|max:255',
                'Email' => 'required|email|max:255',
                // validate Dutch phone numbers (starting with +31 or 0, followed by 9 digits)
                'Phone' => [
                    'required',
                    'regex:/^(\+31|0)(6|1|2|3|4|5|7|8|9)[0-9]{8}$/',
                ],
                'Street' => 'required|string|max:255',
                'HouseNumber' => 'required|integer|min:1|max:9999',
                'PostalCode' => [
                    'required',
                    'regex:/^[1-9][0-9]{3}\s?[A-Za-z]{2}$/',
                ],
                'City' => 'required|string|max:255',
            ], [
                'PostalCode.regex' => 'Gebruik een geldige Nederlandse postcode (1234AB).',
                'Phone.regex' => 'Gebruik een geldig Nederlands telefoonnummer.',
            ]);

            $validated['PostalCode'] = strtoupper(str_replace(' ', '', $validated['PostalCode']));

            $exists = ContactModel::where('FirstName', $validated['FirstName'])
                ->where('LastName', $validated['LastName'])
                ->where('Phone', $validated['Phone'])
                ->exists();

            if ($exists) {
                return redirect()->back()
                    ->withInput()
                    ->with('error', 'Deze leverancier bestaat al.');
            }

            $this->supplier->createSupplier($validated);

            return redirect()->route('supplier.index')
                ->with('success', 'Leverancier succesvol aangemaakt.');

        } catch (ValidationException $e) {
            throw $e;
        } catch (\Exception $e) {
            Log::error('Error creating supplier', ['error' => $e->getMessage()]);

            return redirect()->back()
                ->withInput()
                ->with('error', 'Er is een fout opgetreden.');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(SupplierModel $supplier)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $supplier = $this->supplier->getSupplierById($id);

        if (! $supplier) {
            return redirect()->route('supplier.index')->with('error', 'Leverancier niet gevonden.');
        }

        return view('supplier.edit', ['supplier' => $supplier]);
    }

    public function update(Request $request, $id)
    {
        try {
            $validated = $request->validate([
                'CompanyName' => 'required|string|max:255',
                'FirstName' => 'required|string|max:255',
                'LastName' => 'required|string|max:255',
                'Email' => 'required|email|max:255',
                'Phone' => [
                    'required',
                    'regex:/^(\+31|0)(6|1|2|3|4|5|7|8|9)[0-9]{8}$/',
                ],
                'Street' => 'required|string|max:255',
                'HouseNumber' => 'required|integer|min:1|max:9999',
                'PostalCode' => [
                    'required',
                    'regex:/^[1-9][0-9]{3}\s?[A-Za-z]{2}$/',
                ],
                'City' => 'required|string|max:255',
            ], [
                'PostalCode.regex' => 'Gebruik een geldige Nederlandse postcode (1234AB).',
                'Phone.regex' => 'Gebruik een geldig Nederlands telefoonnummer.',
            ]);

            // Normaliseer postcode en telefoon
            $validated['PostalCode'] = strtoupper(str_replace(' ', '', $validated['PostalCode']));
            $validated['Phone'] = str_replace([' ', '-', '.'], '', $validated['Phone']);

            $supplier = $this->supplier->getSupplierById($id);

            $IsActive = SupplierModel::where('id', $id)->value('is_active') ?? 0;

            if ($IsActive === false) {
                return redirect()->route('supplier.index')->with('error', 'Leverancier is inactief en kan niet worden bijgewerkt. verwijder de leverancier');
            }

            $updated = $this->supplier->updateSupplier($id, $validated);

            if (! $updated) {
                return redirect()->back()->withInput()
                    ->with('error', 'Geen wijzigingen doorgevoerd of fout opgetreden.');
            }

            return redirect()->route('supplier.index')
                ->with('success', 'Leverancier succesvol bijgewerkt.');

        } catch (ValidationException $e) {
            throw $e;
        } catch (\Exception $e) {
            Log::error('Error updating supplier', ['error' => $e->getMessage(), 'id' => $id]);

            return redirect()->back()->withInput()->with('error', 'Er is een fout opgetreden.');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(SupplierModel $supplier)
    {
        //
    }
}
