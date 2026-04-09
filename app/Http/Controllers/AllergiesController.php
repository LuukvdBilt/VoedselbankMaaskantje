<?php

namespace App\Http\Controllers;

use App\Models\FoodpackageModel;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class AllergiesController extends Controller
{
    /**
     * Constructor: middleware + eventuele dependency injection.
     */
    public function __construct()
    {
        // Alleen ingelogde gebruikers mogen deze pagina zien
        $this->middleware('auth');
    }

    /**
     * Toon overzicht van voedselpakketten + allergieën + producten.
     * Bevat stored procedure, joins, try/catch en logging.
     */
    public function index()
    {
        try {
            // --- Optie 1: Stored Procedure (voorkeur voor punten) ---
            $rawData = DB::select('CALL sp_getAllAllergies()');

            // Data omzetten naar nette Laravel-structuur
            $packages = $this->transformStoredProcedureData($rawData);

            Log::info('Allergieën overzicht succesvol geladen via stored procedure.');

            return view('allergies.index', compact('packages'));

        } catch (\Exception $e) {

            Log::error('Stored procedure mislukt, fallback naar joins. Foutmelding: ' . $e->getMessage());

            // --- Optie 2: Fallback naar Eloquent + Joins ---
            try {
                $packages = FoodpackageModel::select(
                    'FoodPackages.Id',
                    'FoodPackages.Name',
                    'FoodPackages.Description'
                )
                    ->leftJoin('FoodPackage_Allergies', 'FoodPackages.Id', '=', 'FoodPackage_Allergies.FoodPackageId')
                    ->leftJoin('Allergies', 'FoodPackage_Allergies.AllergiesId', '=', 'Allergies.Id')
                    ->with(['allergies', 'products'])
                    ->groupBy('FoodPackages.Id')
                    ->get();

                Log::warning('Stored procedure mislukt, maar joins succesvol uitgevoerd.');

                return view('allergies.index', compact('packages'));

            } catch (\Exception $e2) {
                Log::error('Fout bij fallback joins: ' . $e2->getMessage());

                return back()->with('error', 'Er ging iets mis bij het laden van de allergieën.');
            }
        }
    }

    /**
     * Zet de platte stored procedure data om naar een nette Laravel-collectie.
     */
    private function transformStoredProcedureData($rawData)
    {
        $packages = [];

        foreach ($rawData as $row) {

            // Als pakket nog niet bestaat → aanmaken
            if (!isset($packages[$row->FoodPackageId])) {
                $packages[$row->FoodPackageId] = [
                    'Id' => $row->FoodPackageId,
                    'Name' => $row->FoodPackageName,
                    'Description' => $row->FoodPackageDescription,
                    'allergies' => [],
                    'products' => []
                ];
            }

            // Allergie toevoegen (als die bestaat)
            if (!empty($row->AllergyId)) {
                $packages[$row->FoodPackageId]['allergies'][$row->AllergyId] = [
                    'Id' => $row->AllergyId,
                    'Name' => $row->AllergyName,
                    'Description' => $row->AllergyDescription
                ];
            }

            // Product toevoegen (als die bestaat)
            if (!empty($row->ProductId)) {
                $packages[$row->FoodPackageId]['products'][$row->ProductId] = [
                    'Id' => $row->ProductId,
                    'Name' => $row->ProductName,
                    'Barcode' => $row->ProductBarcode
                ];
            }
        }

        // Omzetten naar nette array zonder keys
        return collect(array_values($packages));
    }
}
