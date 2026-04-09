<?php

namespace App\Models;

class Inventory
{
    protected $table = 'inventory';
    protected $primaryKey = 'id';
    protected $fillable = [
        'product_id',
        'quantity',
        'location',
        'last_updated'
    ];

    public function __construct()
    {
        // Constructor
    }
}
