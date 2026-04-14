<?php

namespace App\Http\Controllers;

use App\Models\ContactModel;
use App\Models\SupplierModel;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

/**
 * SupplierController
 *
 * Handles all CRUD operations for suppliers including listing, creating,
 * updating, and deleting supplier records with validation and logging.
 */
class SupplierController extends Controller
{
    /**
     * Constructor with dependency injection.
     *
     * @param  SupplierModel  $supplier  The supplier model instance
     */
    public function __construct(private SupplierModel $supplier) {}

    /**
     * Display a paginated list of all suppliers.
     *
     * Retrieves all suppliers from the database and paginates them
     * based on the requested page number.
     *
     * @param  Request  $request  The HTTP request instance
     * @return View|RedirectResponse The supplier index view with paginated data or redirect on error
     */
    public function index(Request $request): View|RedirectResponse
    {
        try {
            // Set the number of suppliers per page
            $perPage = 6;

            // Get the requested page number from query string, default to page 1
            $page = $request->get('page', 1);

            // Fetch all suppliers from the database and convert to collection
            $allSuppliers = collect($this->supplier->getAllSuppliers());

            $totalIsActive = $allSuppliers->where('IsActive', 1)->count();

            // Calculate the offset based on page number and items per page
            $offset = ($page - 1) * $perPage;

            // Slice the collection to get items for the current page
            $suppliers = $allSuppliers->slice($offset, $perPage)->values();

            // Create a proper Laravel paginator instance for consistent UI rendering
            $suppliersPaginated = new LengthAwarePaginator(
                $suppliers,
                $allSuppliers->count(),
                $perPage,
                $page,
                ['path' => $request->url(), 'query' => $request->query()]
            );

            // Log successful retrieval for monitoring
            Log::info('Suppliers retrieved successfully', ['page' => $page, 'total' => $allSuppliers->count()]);

            // Return the view with paginated suppliers
            return view('supplier.index', [
                'suppliers' => $suppliersPaginated, 
                'IsActive' => $totalIsActive
                ]);
                
        } catch (\Exception $e) {
            // Log any unexpected errors with full context for debugging
            Log::error('Error retrieving suppliers', [
                'error' => $e->getMessage(),

            ]);

            // Redirect back to index with user-friendly error message
            return redirect()->route('supplier.index')->with('error', 'Er is een fout opgetreden bij het ophalen van leveranciers.');
        }
    }

    /**
     * Show the form for creating a new supplier.
     *
     * Loads the create view and passes existing suppliers for reference
     * during supplier creation.
     *
     * @return View The supplier creation form view
     */
    public function create()
    {
        try {
            // Retrieve all existing suppliers to display in the form if needed
            $suppliers = $this->supplier->getAllSuppliers();

            // Log form access for monitoring
            Log::info('Create supplier form loaded');

            // Return view with supplier list
            return view('supplier.create', [
                'suppliers' => $suppliers,
            ]);
        } catch (\Exception $e) {
            // Log form loading errors
            Log::error('Error loading create supplier form', [
                'error' => $e->getMessage(),

            ]);

            // Redirect to index with error message if form fails to load
            return redirect()->route('supplier.index')->with('error', 'Er is een fout opgetreden bij het laden van het formulier.');
        }
    }

    /**
     * Store a newly created supplier in the database.
     *
     * Validates supplier data, checks for duplicates, and persists
     * the new supplier record to the database.
     *
     * @param  Request  $request  The HTTP request containing supplier data
     * @return RedirectResponse Redirect to index on success or back on failure
     */
    public function store(Request $request)
    {
        try {
            // Validate all incoming supplier data with custom error messages
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

            // Normalize postal code: remove spaces and convert to uppercase for consistency
            $validated['PostalCode'] = strtoupper(str_replace(' ', '', $validated['PostalCode']));

            // Check if a supplier with the same name and phone already exists to prevent duplicates
            $exists = ContactModel::where('FirstName', $validated['FirstName'])
                ->where('LastName', $validated['LastName'])
                ->exists();

            // Return error if duplicate supplier is found
            if ($exists) {
                Log::warning('Attempt to create duplicate supplier', [
                    'first_name' => $validated['FirstName'],
                    'last_name' => $validated['LastName'],
                ]);

                return redirect()->back()
                    ->withInput()
                    ->with('error', 'Deze leverancier bestaat al.');
            }
            else {

            // Create new supplier in the database
            $this->supplier->createSupplier($validated);

            // Log successful creation with supplier details
            Log::info('Supplier created successfully', [
                'company_name' => $validated['CompanyName'],
                'name' => $validated['FirstName'].' '.$validated['LastName'],
            ]);

            // Redirect to index with success message
            return redirect()->route('supplier.index')
                ->with('success', 'Leverancier succesvol aangemaakt.');
            }
        } catch (ValidationException $e) {
            // Re-throw validation exceptions to be handled by Laravel's validation error handler
            throw $e;
        } catch (\Exception $e) {
            // Log any unexpected errors during supplier creation
            Log::error('Error creating supplier', [
                'error' => $e->getMessage(),

            ]);

            // Return to form with user input preserved and error message
            return redirect()->back()
                ->withInput()
                ->with('error', 'Er is een fout opgetreden.');
        }
    }

    /**
     * Display the specified supplier.
     *
     * @param  SupplierModel  $supplier  The supplier model instance
     * @return void Currently not implemented
     */
    public function show(SupplierModel $supplier)
    {
        //
    }

    /**
     * Show the form for editing the specified supplier.
     *
     * Retrieves the supplier by ID and loads the edit form with
     * the current supplier data.
     *
     * @param  int  $id  The supplier ID to edit
     * @return View The supplier edit form view
     */
    public function edit($id)
    {
        try {
            // Retrieve the supplier record by ID
            $supplier = $this->supplier->getSupplierById($id);

            // Check if supplier exists
            if (! $supplier) {
                Log::warning('Attempt to edit non-existent supplier', ['supplier_id' => $id]);

                return redirect()->route('supplier.index')->with('error', 'Leverancier niet gevonden.');
            }

            // Log form access for monitoring
            Log::info('Edit supplier form loaded', ['supplier_id' => $id]);

            // Return edit view with supplier data
            return view('supplier.edit', ['supplier' => $supplier]);
        } catch (\Exception $e) {
            // Log form loading errors
            Log::error('Error loading edit supplier form', [
                'error' => $e->getMessage(),
                'supplier_id' => $id,

            ]);

            // Redirect to index with error message if form fails to load
            return redirect()->route('supplier.index')->with('error', 'Er is een fout opgetreden bij het laden van het formulier.');
        }
    }

    /**
     * Update the specified supplier in the database.
     *
     * Validates supplier data, checks if supplier is active, and
     * persists the updated information to the database.
     *
     * @param  Request  $request  The HTTP request containing updated supplier data
     * @param  int  $id  The supplier ID to update
     * @return RedirectResponse Redirect to index on success or back on failure
     */
    public function update(Request $request, $id)
    {
        try {
            // Validate all incoming supplier data with custom error messages
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

            // Normalize postal code: remove spaces and convert to uppercase for consistency
            $validated['PostalCode'] = strtoupper(str_replace(' ', '', $validated['PostalCode']));

            // Normalize phone: remove spaces, dashes and dots for consistency
            $validated['Phone'] = str_replace([' ', '-', '.'], '', $validated['Phone']);

            // Retrieve the supplier to verify it exists
            $supplier = $this->supplier->getSupplierById($id);

            // Return error if supplier not found
            if (! $supplier) {
                Log::warning('Attempt to update non-existent supplier', ['supplier_id' => $id]);

                return redirect()->route('supplier.index')->with('error', 'Leverancier niet gevonden.');
            }

            // Check if supplier is active (required for updates)
            $isActive = SupplierModel::where('id', $id)->value('is_active') ?? 0;

            // Prevent updates to inactive suppliers
            if ($isActive === false || $isActive === 0) {
                Log::warning('Attempt to update inactive supplier', ['supplier_id' => $id]);

                return redirect()->route('supplier.index')->with('error', 'Leverancier is inactief en kan niet worden bijgewerkt. Verwijder de leverancier.');
            }

            // Update supplier in database
            $updated = $this->supplier->updateSupplier($id, $validated);

            // Check if update was successful
            if (! $updated) {
                Log::warning('No changes made to supplier', ['supplier_id' => $id]);

                return redirect()->back()->withInput()
                    ->with('error', 'Geen wijzigingen doorgevoerd of fout opgetreden.');
            }

            // Log successful update with supplier details
            Log::info('Supplier updated successfully', [
                'supplier_id' => $id,
                'company_name' => $validated['CompanyName'],
            ]);

            // Redirect to index with success message
            return redirect()->route('supplier.index')
                ->with('success', 'Leverancier succesvol bijgewerkt.');
        } catch (ValidationException $e) {
            // Re-throw validation exceptions to be handled by Laravel's validation error handler
            throw $e;
        } catch (\Exception $e) {
            // Log any unexpected errors during supplier update
            Log::error('Error updating supplier', [
                'error' => $e->getMessage(),
                'supplier_id' => $id,

            ]);

            // Return to form with user input preserved and error message
            return redirect()->back()->withInput()->with('error', 'Er is een fout opgetreden.');
        }
    }

    /**
     * Remove the specified supplier from storage.
     *
     * Verifies the supplier is inactive before deletion to maintain
     * data integrity for active suppliers.
     *
     * @param  SupplierModel  $supplier  The supplier model instance
     * @param  int  $id  The supplier ID to delete
     * @return RedirectResponse Redirect to index with status message
     */
    public function destroy(SupplierModel $supplier, $id)
    {
        try {
            // Check if supplier model instance exists
            if (! $supplier) {
                Log::warning('Attempt to delete non-existent supplier', ['supplier_id' => $supplier->id]);

                return redirect()->route('supplier.index')->with('error', 'Leverancier niet gevonden.');
            }

            // Check if supplier is active (only inactive suppliers can be deleted)
            $isActive = SupplierModel::where('id', $id)->value('is_active') ?? 0;

            // Prevent deletion of active suppliers
            if ($isActive === true || $isActive === 1) {
                Log::warning('Attempt to delete active supplier', ['supplier_id' => $id]);

                return redirect()->route('supplier.index')->with('error', 'Leverancier is inactief en kan niet worden verwijderd, omdat deze leverancier nogsteeds bij ons actief is.');
            }

            // Delete the supplier from database
            $this->supplier->deleteSupplier($id);

            // Log successful deletion with supplier details
            Log::info('Supplier deleted successfully', ['supplier_id' => $supplier->id]);

            // Redirect to index with success message
            return redirect()->route('supplier.index')
                ->with('success', 'Leverancier succesvol verwijderd.');
        } catch (\Exception $e) {
            // Log any unexpected errors during deletion
            Log::error('Error deleting supplier', [
                'error' => $e->getMessage(),
                'supplier_id' => $supplier->id,

            ]);

            // Redirect back with error message
            return redirect()->back()->with('error', 'Er is een fout opgetreden bij het verwijderen van de leverancier.');
        }
    }
}
