<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FoodpackageModel extends Model
{
    protected $table = 'FoodPackages';
    protected $primaryKey = 'Id';

    protected $fillable = [
        'Name',
        'Description',
        'is_active',
        'note'
    ];

    /**
     * Relatie: voedselpakket heeft meerdere allergieën.
     */
    public function allergies()
{
    return $this->belongsToMany(
        AllergiesModel::class,
        'FoodPackage_Allergies',
        'FoodPackageId',
        'AllergiesId'
    );
}


    /**
     * Relatie: voedselpakket bevat meerdere producten.
     */
    public function products()
    {
        return $this->belongsToMany(
            ProductModel::class,
            'FoodPackage_Products',
            'FoodPackageId',
            'ProductId'
        );
    }
}
