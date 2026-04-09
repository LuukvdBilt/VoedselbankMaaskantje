<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Inventory extends Model
{
    use HasFactory;

    protected $table = 'Inventory';
    protected $primaryKey = 'Id';

    protected $fillable = [
        'ProductId',
        'SupplierId',
        'Quantity',
        'ExpirationDate',
        'is_active',
        'note',
    ];
}
