<?php

namespace App\Models;

use Database\Factories\ProductFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
        'note',
    ];

    protected static function newFactory(): ProductFactory
    {
        return ProductFactory::new();
    }
}
