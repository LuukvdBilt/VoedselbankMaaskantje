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
            // Validate incoming request data
            $validated = $request->validate([
                'CompanyName' => 'required|string|max:255',
                'FirstName' => 'required|string|max:255',
                'LastName' => 'required|string|max:255',
                'Email' => 'required|email|max:255',
                'Phone' => 'required|string|max:255',
                'Street' => 'required|string|max:255',
                'HouseNumber' => 'required|integer',
                'PostalCode' => 'required|string',
                'City' => 'required|string|max:255',
            ]);

            // Check if contact with same first name already exists
            $contactExists = ContactModel::where('FirstName', $validated['FirstName'])
                ->where('LastName', $validated['LastName'])
                ->exists();

            if ($contactExists) {
                Log::warning('Duplicate supplier attempt', ['firstName' => $validated['FirstName'], 'lastName' => $validated['LastName']]);

                return redirect()->back()->with('error', 'Deze leverancier is al bekend bij ons. Controleer de gegevens en probeer het opnieuw.');
            }

            // Create new supplier
            $this->supplier->createSupplier($validated);

            Log::info('Supplier created successfully', ['companyName' => $validated['CompanyName']]);

            return redirect()->route('supplier.index')->with('success', 'Leverancier succesvol aangemaakt.');
        } catch (ValidationException $e) {
            Log::warning('Validation failed for supplier creation', ['errors' => $e->errors()]);

            throw $e;
        } catch (\Exception $e) {
            Log::error('Error creating supplier', ['error' => $e->getMessage()]);

            return redirect()->back()->with('error', 'Er is een fout opgetreden bij het aanmaken van de leverancier.');
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
    public function edit(SupplierModel $supplier)
    {
        return view('supplier.edit', [
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, SupplierModel $supplier)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(SupplierModel $supplier)
    {
        //
    }
}
