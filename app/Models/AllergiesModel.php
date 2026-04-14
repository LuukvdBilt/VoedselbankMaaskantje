<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class AllergiesModel extends Model
{
    use HasFactory;

    protected $table = 'Allergies';

    protected $primaryKey = 'Id';

    protected $fillable = [
        'Name',
        'Description',
        'is_active',
        'note',
    ];

    public function getAllAllergies()
    {
        return DB::table('Allergies as a')
            ->leftJoin('FoodPackage_Allergy as fpa', 'fpa.AllergyId', '=', 'a.Id')
            ->leftJoin('FoodPackage_Product as fpp', 'fpp.FoodPackageId', '=', 'fpa.FoodPackageId')
            ->select(
                'a.Id',
                'a.Name',
                'a.Description',
                DB::raw('COUNT(DISTINCT fpa.FoodPackageId) as TotalFoodPackages'),
                DB::raw('COUNT(DISTINCT fpp.ProductId) as TotalProducts')
            )
            ->groupBy('a.Id', 'a.Name', 'a.Description')
            ->orderBy('a.Name')
            ->get();
    }

    public function foodPackages()
    {
        return $this->belongsToMany(
            FoodpackageModel::class,
            'FoodPackage_Allergy',
            'AllergyId',
            'FoodPackageId'
        );
    }
}
