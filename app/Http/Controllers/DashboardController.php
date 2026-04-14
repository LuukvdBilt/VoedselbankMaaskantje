<?php

namespace App\Http\Controllers;

use App\Models\AllergiesModel;
use App\Models\FoodPackageDistribution;
use App\Models\Inventory;
use App\Models\SupplierModel;
use Illuminate\Contracts\View\View;

class DashboardController extends Controller
{
    public function __invoke(): View
    {
        $supplierCount = SupplierModel::where('is_active', true)->count();
        $inventoryItemCount = Inventory::where('is_active', true)->count();
        $inventoryStockTotal = Inventory::where('is_active', true)->sum('Quantity');
        $allergyCount = AllergiesModel::where('is_active', true)->count();
        $activeOrderCount = FoodPackageDistribution::where('is_active', true)->count();

        return view('dashboard', [
            'supplierCount' => $supplierCount,
            'inventoryItemCount' => $inventoryItemCount,
            'inventoryStockTotal' => $inventoryStockTotal,
            'allergyCount' => $allergyCount,
            'activeOrderCount' => $activeOrderCount,
        ]);
    }
}
