<?php

namespace App\Http\Controllers;

use App\Models\SupplierModel;
use Illuminate\Http\Request;

class SupplierController extends Controller
{
    private $SupplierModel;
    public function __construct()
    {
        $this->SupplierModel = new SupplierModel();
    }

    public function index()
    {

        $suppliers = $this->SupplierModel->getAllSuppliers();
        
        return view('supplier.index', [
            'suppliers' => $suppliers
        ]);
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
