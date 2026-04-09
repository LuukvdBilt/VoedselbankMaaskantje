<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rules\In;
use App\Models\Inventory;

class InventoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $deletecode = 1234;
        $lock = false;
        if ($lock === false) {
            $inventory = DB::select('CALL GetInventory()');
            return view('Inventory.inventory', compact('inventory', 'deletecode'));
        } else {
            $inventory = "";
            return view('Inventory.inventory', compact('inventory', 'deletecode'));
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
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
        $inventoryItem = Inventory::find($id);
        return view('Inventory.edit', compact('inventoryItem'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validated = $request->validate([
            'ProductName' => 'required|string|max:255',
            'Barcode' => 'required|string|max:255',
            'Category' => 'required|string|max:255',
            'Supplier' => 'required|string|max:255',
            'Quantity' => 'required|integer|min:0',
            'ExpirationDate' => 'required|date',
            'InventoryNote' => 'nullable|string',
            'ProductNote' => 'nullable|string',
        ]);

        DB::statement('CALL UpdateInventory(?, ?, ?, ?, ?, ?, ?, ?, ?)', [
            $id,
            $validated['ProductName'],
            $validated['Barcode'],
            $validated['Category'],
            $validated['Supplier'],
            $validated['Quantity'],
            $validated['ExpirationDate'],
            $validated['InventoryNote'],
            $validated['ProductNote']
        ]);

        return redirect()->route('inventory.index')->with('success', 'Inventory item updated successfully.');
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
