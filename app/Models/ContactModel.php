<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ContactModel extends Model
{
    use HasFactory;

    protected $table = 'Contact';

    protected $fillable = [
        'UserId',
        'FirstName',
        'LastName',
        'Phone',
        'AddressId',
        'is_active',
        'note',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function address()
    {
        return $this->belongsTo(AddressModel::class, 'AddressId');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'UserId');
    }
}