<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

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

    /**
     * Retrieve all suppliers from the database via stored procedure.
     */
    public function getAllSuppliers(): array
    {
        try {
            return DB::select('CALL sp_GetAllSuppliers()');
        } catch (\Exception $e) {
            Log::error('Error fetching all suppliers', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            throw $e;
        }
    }

    /**
     * Create a new supplier with associated contact information via stored procedure.
     */
    public function createSupplier(array $data): void
    {
        try {
            DB::insert('CALL sp_createSupplier(?, ?, ?, ?, ?, ?, ?, ?, ?)', [
                $data['CompanyName'],
                $data['FirstName'],
                $data['LastName'],
                $data['Email'],
                $data['Phone'],
                $data['Street'],
                $data['HouseNumber'],
                $data['PostalCode'],
                $data['City'],
            ]);

            Log::info('Supplier created successfully', [
                'company_name' => $data['CompanyName'],
            ]);
        } catch (\Exception $e) {
            Log::error('Error creating supplier', [
                'error' => $e->getMessage(),
                'data' => $data,
                'trace' => $e->getTraceAsString(),
            ]);
            throw $e;
        }
    }

  public function getSupplierById($id)
    {
        try {
            $result = DB::select('CALL sp_GetSupplierById(?)', [$id]);
            return $result[0] ?? null;
        } catch (\Exception $e) {
            Log::error('Error fetching supplier by ID', ['error' => $e->getMessage(), 'id' => $id]);
            throw $e;
        }
    }

    public function updateSupplier($id, array $data): bool
    {
        try {
            // Stored procedure uitvoeren
            DB::statement('CALL sp_updateSupplier(?, ?, ?, ?, ?, ?, ?, ?, ?, ?)', [
                $id,
                $data['CompanyName'],
                $data['FirstName'],
                $data['LastName'],
                $data['Street'],
                $data['HouseNumber'],
                $data['PostalCode'],
                $data['City'],
                $data['Phone'],
                $data['Email'],
            ]);

            Log::info('Supplier updated successfully', ['id' => $id, 'company_name' => $data['CompanyName']]);

            return true;
        } catch (\Exception $e) {
            Log::error('Error updating supplier', [
                'error' => $e->getMessage(),
                'id' => $id,
                'data' => $data,
            ]);
            return false;
        }
    }

    public function deleteSupplier($id): bool
    {
        try {
            DB::statement('CALL sp_deleteSupplier(?)', [$id]);
            Log::info('Supplier deleted successfully', ['id' => $id]);
            return true;
        } catch (\Exception $e) {
            Log::error('Error deleting supplier', [
                'error' => $e->getMessage(),
                'id' => $id,
            ]);
            return false;
        }
    }

    public function contact()
    {
        return $this->belongsTo(ContactModel::class, 'ContactId');
    }
}
