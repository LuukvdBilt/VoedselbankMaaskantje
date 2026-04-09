<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ProductModel extends Model
{
    use HasFactory;
    protected $table = 'Product';
    protected $primaryKey = 'Id';

    protected $fillable = [
        'Barcode',
        'ProductName',
        'CategoryId',
        'SupplierId',
        'is_active',
        'note'
    ];

    /**
     * Relatie: product hoort bij een categorie.
     */
    public function category()
    {
        return $this->belongsTo(
            Category::class,
            'CategoryId',
            'Id'
        );
    }

    /**
     * Relatie: product hoort bij een leverancier.
     */
    public function supplier()
    {
        return $this->belongsTo(
            SupplierModel::class,
            'SupplierId',
            'Id'
        );
    }

    /**
     * Relatie: product zit in meerdere voedselpakketten.
     */
    public function foodPackages()
    {
        return $this->belongsToMany(
            FoodpackageModel::class,
            'FoodPackage_Products',
            'ProductId',
            'FoodPackageId'
        );
    }
}
