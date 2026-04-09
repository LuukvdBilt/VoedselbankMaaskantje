<?php

namespace App\Http\Controllers;

use App\Models\SupplierModel;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;

class SupplierController extends Controller
{
    private $SupplierModel;

    public function __construct()
    {
        $this->SupplierModel = new SupplierModel;
    }

    public function index(Request $request)
    {
        $perPage = 6;
        $page = $request->get('page', 1);

        $allSuppliers = collect($this->SupplierModel->getAllSuppliers());
        $offset = ($page - 1) * $perPage;
        $suppliers = $allSuppliers->slice($offset, $perPage)->values();

        $suppliersPaginated = new LengthAwarePaginator(
            $suppliers,
            $allSuppliers->count(),
            $perPage,
            $page,
            ['path' => $request->url(), 'query' => $request->query()]
        );

        return view('supplier.index', ['suppliers' => $suppliersPaginated]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $suppliers = $this->SupplierModel->getAllSuppliers();

        return view('supplier.create', [
            'suppliers' => $suppliers,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'CompanyName' => 'required|string|max:255',
            'FirstName' => 'required|string|max:255',
            'LastName' => 'required|string|max:255',
            'Email' => 'required|email|max:255',
            'Phone' => 'required|string|max:255',
            'Street' => 'required|string|max:255',
            'HouseNumber' => 'required|string|max:255',
            'PostalCode' => 'required|string|max:255',
            'City' => 'required|string|max:255',
        ]);

        $this->SupplierModel->createSupplier($validated);

        if ($validated) {
            return redirect()->route('supplier.index')->with('success', 'Supplier created successfully.');
        } else {
            return redirect()->back()->with('error', 'Failed to create supplier. Please try again.');
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
