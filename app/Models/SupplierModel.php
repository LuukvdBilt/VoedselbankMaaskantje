<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\ContactModel;

class SupplierModel extends Model
{
    use HasFactory;
    protected $table = 'Supplier';

    protected $fillable = [
        'CompanyName',
        'ContactId',
        'is_active',
        'note',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function contact()
    {
        return $this->belongsTo(ContactModel::class, 'ContactId');
    }
}
