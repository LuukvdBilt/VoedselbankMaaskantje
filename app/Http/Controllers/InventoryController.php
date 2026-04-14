<?php

namespace App\Http\Controllers;

use App\Models\Inventory;
use App\Models\ProductModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;

class InventoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            Log::info('Fetching inventory');
            $deletecode = 1234;
            $lock = false;
            Log::debug('Lock status', ['lock' => $lock]);
            if ($lock === false) {
                Log::info('Executing GetInventory stored procedure');
                $inventory = DB::select('CALL GetInventory()');
                Log::info('Inventory fetched successfully', ['count' => count($inventory)]);
                Log::debug('Inventory data retrieved', ['inventory_count' => count($inventory)]);

                return view('Inventory.inventory', compact('inventory', 'deletecode'));
            } else {
                Log::warning('Inventory fetch locked');
                Log::warning('Cannot fetch inventory: system is locked');
                $inventory = '';

                return view('Inventory.inventory', compact('inventory', 'deletecode'));
            }
        } catch (\Exception $e) {
            Log::error('Error fetching inventory', ['error' => $e->getMessage()]);
            Log::error('Exception details', ['exception' => get_class($e), 'code' => $e->getCode()]);

            return back()
                ->withErrors(['error' => 'Er ging iets mis bij het ophalen van het inventory.'])
                ->withInput();
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('Inventory.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Stap 1: Valideren
        $validated = $request->validate([
            'ProductName' => [
                'required',
                'string',
                'max:255',
                Rule::unique('Product', 'ProductName'), // <-- juiste tabel + kolom
            ],
            'Barcode' => 'required|string|max:255',
            'Category' => 'required|string|max:255',
            'Supplier' => 'required|string|max:255',
            'Quantity' => 'required|integer|min:0|max:10000',
            'ExpirationDate' => 'required|date',
            'InventoryNote' => 'nullable|string',
            'ProductNote' => 'nullable|string',
        ], [
            'ProductName.unique' => 'Deze productnaam bestaat al.',
        ]);

        try {
            // Stap 2: Stored procedure aanroepen
            Log::info('Inserting inventory item', ['product' => $validated['ProductName']]);
            DB::statement('CALL InsertInventory(?, ?, ?, ?, ?, ?, ?, ?)', [
                $validated['ProductName'],
                $validated['Barcode'],
                $validated['Category'],
                $validated['Supplier'],
                $validated['Quantity'],
                $validated['ExpirationDate'],
                $validated['InventoryNote'],
                $validated['ProductNote'],
            ]);

            Log::info('Inventory item created successfully', ['product' => $validated['ProductName']]);

            return redirect()
                ->route('inventory.index')
                ->with('success', 'Inventory item created successfully.');

        } catch (\Exception $e) {
            Log::error('Error creating inventory item', ['error' => $e->getMessage()]);

            return back()
                ->withErrors(['error' => 'Er ging iets mis bij het opslaan van het inventory item.'])
                ->withInput();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $inventoryItem = DB::table('Inventory as i')
            ->join('Product as p', 'i.ProductId', '=', 'p.Id')
            ->join('Category as c', 'p.CategoryId', '=', 'c.Id')
            ->leftJoin('Supplier as s', 'i.SupplierId', '=', 's.Id')
            ->select([
                'i.Id',
                'p.ProductName',
                'p.Barcode',
                'c.Name as Category',
                's.CompanyName as Supplier',
                'i.Quantity',
                'i.ExpirationDate',
                'i.note as InventoryNote',
                'p.note as ProductNote',
            ])
            ->where('i.Id', $id)
            ->first();

        abort_unless($inventoryItem !== null, 404);

        return view('Inventory.edit', compact('inventoryItem'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        // Haal het huidige inventory item op
        $inventory = Inventory::findOrFail($id);

        // Haal het gekoppelde product op (FK)
        $product = ProductModel::findOrFail($inventory->ProductId);

        // Valideer invoer
        $validated = $request->validate([
            'ProductName' => [
                'required',
                'string',
                'max:255',
                // Uniek in Product-tabel, maar sla de huidige naam over
                Rule::unique('Product', 'ProductName')->ignore($product->ProductId, 'ProductId'),
            ],
            'Barcode' => 'required|string|max:255',
            'Category' => 'required|string|max:255',
            'Supplier' => 'required|string|max:255',
            'Quantity' => 'required|integer|min:0',
            'ExpirationDate' => 'required|date',
            'InventoryNote' => 'nullable|string',
            'ProductNote' => 'nullable|string',
        ], [
            'ProductName.unique' => 'Deze productnaam bestaat al.',
        ]);

        try {
            // Stored procedure aanroepen
            DB::statement('CALL updateInventory(?, ?, ?, ?, ?, ?, ?, ?, ?)', [
                $id,
                $validated['ProductName'],
                $validated['Barcode'],
                $validated['Category'],
                $validated['Supplier'],
                $validated['Quantity'],
                $validated['ExpirationDate'],
                $validated['InventoryNote'],
                $validated['ProductNote'],
            ]);

            return redirect()
                ->route('inventory.index')
                ->with('success', 'Inventory item updated successfully.');

        } catch (\Exception $e) {

            return back()
                ->withErrors(['error' => 'Er ging iets mis bij het bijwerken van het inventory item.'])
                ->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        DB::statement('CALL DeleteInventoryById(?)', [$id]);

        return redirect()->route('inventory.index')->with('success', 'Inventory item deleted successfully.');
    }
}
