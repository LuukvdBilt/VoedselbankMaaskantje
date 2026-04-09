<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Address extends Model
{
    protected $table = 'Address';
    protected $primaryKey = 'Id';
    public $timestamps = true;
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    protected $fillable = [
        'Street',
        'HouseNumber',
        'PostalCode',
        'City',
        'is_active',
        'note'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    public function clients()
    {
        return $this->hasMany(Client::class, 'AddressId', 'Id');
    }
}