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
            Log::error('Error retrieving suppliers', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

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
            Log::error('Error loading create supplier form', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return redirect()->route('supplier.index')->with('error', 'Er is een fout opgetreden bij het laden van het formulier.');
        }
    }

    /**
     * Store a newly created supplier in the database.
     */
    public function store(Request $request)
    {
        try {
            // Validate supplier data
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

            // Normalize postal code: remove spaces and convert to uppercase
            $validated['PostalCode'] = strtoupper(str_replace(' ', '', $validated['PostalCode']));

            // Check if supplier already exists
            $exists = ContactModel::where('FirstName', $validated['FirstName'])
                ->where('LastName', $validated['LastName'])
                ->where('Phone', $validated['Phone'])
                ->exists();

            if ($exists) {
                Log::warning('Attempt to create duplicate supplier', [
                    'first_name' => $validated['FirstName'],
                    'last_name' => $validated['LastName'],
                ]);

                return redirect()->back()
                    ->withInput()
                    ->with('error', 'Deze leverancier bestaat al.');
            }

            // Create new supplier
            $this->supplier->createSupplier($validated);

            Log::info('Supplier created successfully', [
                'company_name' => $validated['CompanyName'],
                'name' => $validated['FirstName'].' '.$validated['LastName'],
            ]);

            return redirect()->route('supplier.index')
                ->with('success', 'Leverancier succesvol aangemaakt.');
        } catch (ValidationException $e) {
            // Re-throw validation exceptions to be handled by Laravel
            throw $e;
        } catch (\Exception $e) {
            Log::error('Error creating supplier', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

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
     * Show the form for editing the specified supplier.
     */
    public function edit($id)
    {
        try {
            $supplier = $this->supplier->getSupplierById($id);

            if (! $supplier) {
                Log::warning('Attempt to edit non-existent supplier', ['supplier_id' => $id]);

                return redirect()->route('supplier.index')->with('error', 'Leverancier niet gevonden.');
            }

            Log::info('Edit supplier form loaded', ['supplier_id' => $id]);

            return view('supplier.edit', ['supplier' => $supplier]);
        } catch (\Exception $e) {
            Log::error('Error loading edit supplier form', [
                'error' => $e->getMessage(),
                'supplier_id' => $id,
                'trace' => $e->getTraceAsString(),
            ]);

            return redirect()->route('supplier.index')->with('error', 'Er is een fout opgetreden bij het laden van het formulier.');
        }
    }

    /**
     * Update the specified supplier in the database.
     */
    public function update(Request $request, $id)
    {
        try {
            // Validate supplier data
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

            // Normalize postal code: remove spaces and convert to uppercase
            $validated['PostalCode'] = strtoupper(str_replace(' ', '', $validated['PostalCode']));
            // Normalize phone: remove spaces, dashes and dots
            $validated['Phone'] = str_replace([' ', '-', '.'], '', $validated['Phone']);

            // Check if supplier exists
            $supplier = $this->supplier->getSupplierById($id);

            if (! $supplier) {
                Log::warning('Attempt to update non-existent supplier', ['supplier_id' => $id]);

                return redirect()->route('supplier.index')->with('error', 'Leverancier niet gevonden.');
            }

            // Check if supplier is active
            $isActive = SupplierModel::where('id', $id)->value('is_active') ?? 0;

            if ($isActive === false || $isActive === 0) {
                Log::warning('Attempt to update inactive supplier', ['supplier_id' => $id]);

                return redirect()->route('supplier.index')->with('error', 'Leverancier is inactief en kan niet worden bijgewerkt. Verwijder de leverancier.');
            }

            // Update supplier
            $updated = $this->supplier->updateSupplier($id, $validated);

            if (! $updated) {
                Log::warning('No changes made to supplier', ['supplier_id' => $id]);

                return redirect()->back()->withInput()
                    ->with('error', 'Geen wijzigingen doorgevoerd of fout opgetreden.');
            }

            Log::info('Supplier updated successfully', [
                'supplier_id' => $id,
                'company_name' => $validated['CompanyName'],
            ]);

            return redirect()->route('supplier.index')
                ->with('success', 'Leverancier succesvol bijgewerkt.');
        } catch (ValidationException $e) {
            // Re-throw validation exceptions to be handled by Laravel
            throw $e;
        } catch (\Exception $e) {
            Log::error('Error updating supplier', [
                'error' => $e->getMessage(),
                'supplier_id' => $id,
                'trace' => $e->getTraceAsString(),
            ]);

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
