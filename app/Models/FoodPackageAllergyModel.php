<?php

namespace App\Models;

use Database\Factories\FoodPackageAllergyFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FoodPackageAllergyModel extends Model
{
    use HasFactory;

    protected $table = 'FoodPackage_Allergy';

    protected $primaryKey = 'Id';

    protected $fillable = [
        'FoodPackageId',
        'AllergyId',
        'is_active',
        'note',
    ];

    protected static function newFactory(): FoodPackageAllergyFactory
    {
        return FoodPackageAllergyFactory::new();
    }
}
