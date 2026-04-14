<?php

namespace App\Models;

use Database\Factories\FoodPackageProductFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FoodPackageProductModel extends Model
{
    use HasFactory;

    protected $table = 'FoodPackage_Product';

    protected $primaryKey = 'Id';

    protected $fillable = [
        'FoodPackageId',
        'ProductId',
        'Quantity',
        'is_active',
        'note',
    ];

    protected static function newFactory(): FoodPackageProductFactory
    {
        return FoodPackageProductFactory::new();
    }
}
