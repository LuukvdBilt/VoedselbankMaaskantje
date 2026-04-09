<?php

namespace App\Models;

use Database\Factories\FoodPackageFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FoodpackageModel extends Model
{
    use HasFactory;

    protected $table = 'FoodPackages';

    protected $primaryKey = 'Id';

    protected $fillable = [
        'Name',
        'Description',
        'is_active',
        'note',
    ];

    /**
     * Relatie: voedselpakket heeft meerdere allergieën.
     */
    public function allergies()
    {
        return $this->belongsToMany(
            AllergiesModel::class,
            'FoodPackage_Allergy',
            'FoodPackageId',
            'AllergyId'
        );
    }

    /**
     * Relatie: voedselpakket bevat meerdere producten.
     */
    public function products()
    {
        return $this->belongsToMany(
            ProductModel::class,
            'FoodPackage_Product',
            'FoodPackageId',
            'ProductId'
        )->withPivot('Quantity');
    }

    protected static function newFactory(): FoodPackageFactory
    {
        return FoodPackageFactory::new();
    }
}
