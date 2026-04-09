<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;


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

    public function getAllSuppliers(): array
    {
        return DB::select('CALL sp_GetAllSuppliers()');
    }

    public function createSupplier(array $data): void
    {
        DB::insert('CALL sp_CreateSupplier(?, ?, ?, ?, ?, ?, ?, ?, ?)', [
            $data['CompanyName'],
            $data['FirstName'],
            $data['LastName'],
            $data['Email'],
            $data['Phone'],
            $data['Street'],
            $data['HouseNumber'],
            $data['PostalCode'],
            $data['City']
         ]);
    }

    public function contact()
    {
        return $this->belongsTo(ContactModel::class, 'ContactId');
    }
}
