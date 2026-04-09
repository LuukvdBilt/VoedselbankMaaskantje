<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AllergiesModel extends Model
{
    protected $table = 'Allergies';
    protected $primaryKey = 'Id';

    protected $fillable = [
        'Name',
        'Description',
        'is_active',
        'note'
    ];

    public function foodPackages()
    {
        return $this->belongsToMany(
            FoodpackageModel::class,
            'FoodPackage_Allergies',
            'AllergiesId',
            'FoodPackageId'
        );
    }
}
