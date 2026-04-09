<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class AllergiesModel extends Model
{
    use HasFactory;

    // Deze tabelnaam en primaire sleutel volgen de bestaande database-naamgeving.
    protected $table = 'Allergies';
    protected $primaryKey = 'Id';

    // Alleen deze velden mogen via mass assignment ingevuld worden.
    protected $fillable = [
        'Name',
        'Description',
        'is_active',
        'note'
    ];

    public function getAllAllergies()
    {
        // Bouw een overzicht met totalen per allergie in een enkele query
        // om extra losse tel-queries in controllers of views te voorkomen.
        return DB::table('Allergies as a')
            // Koppel allergieën aan voedselpakketten; leftJoin houdt ook allergieën zonder koppelingen zichtbaar.
            ->leftJoin('FoodPackage_Allergy as fpa', 'fpa.AllergyId', '=', 'a.Id')
            // Koppel vervolgens producten via voedselpakketten voor producttotalen per allergie.
            ->leftJoin('FoodPackage_Product as fpp', 'fpp.FoodPackageId', '=', 'fpa.FoodPackageId')
            ->select(
                'a.Id',
                'a.Name',
                'a.Description',
                // DISTINCT voorkomt dubbeltellingen door meerdere join-combinaties.
                DB::raw('COUNT(DISTINCT fpa.FoodPackageId) as TotalFoodPackages'),
                DB::raw('COUNT(DISTINCT fpp.ProductId) as TotalProducts')
            )
            // Grouping is nodig omdat we aggregate functies (COUNT) combineren met gewone kolommen.
            ->groupBy('a.Id', 'a.Name', 'a.Description')
            ->orderBy('a.Name')
            ->get();
    }

    public function foodPackages()
    {
        // Many-to-many relatie tussen allergieën en voedselpakketten via de pivot-tabel.
        return $this->belongsToMany(
            FoodpackageModel::class,
            'FoodPackage_Allergy',
            'AllergyId',
            'FoodPackageId'
        );
    }
}
