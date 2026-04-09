<?php

namespace App\Models;

// app/Models/Client.php

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Client extends Model
{
    protected $table = 'Client';
    public $timestamps = true;
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    protected $fillable = [
        'FirstName',
        'LastName',
        'Phone',
        'AddressId',
        'is_active',
        'note'
    ];

    protected $casts = [
        'is_active' => 'boolean'
    ];

    // Get all clients
    public static function getAllClients()
    {
        return DB::select('CALL sp_GetAllClients()');
    }

    // Get client by ID
    public static function getClientById($id)
    {
        $result = DB::select('CALL sp_GetClientById(?)', [$id]);
        return $result ? $result[0] : null;
    }

    // Get household members
    public static function getMembers($householdId)
    {
        return DB::select('CALL sp_GetHouseholdMembers(?)', [$householdId]);
    }

    // Update client info
    public static function updateClientInfo($id, $data)
    {
        DB::select('CALL sp_UpdateClient(?, ?, ?, ?, ?, ?, ?, ?, ?, @success)', [
            $id,
            $data['first_name'] ?? null,
            $data['last_name'] ?? null,
            $data['phone'] ?? null,
            $data['street'] ?? null,
            $data['house_number'] ?? null,
            $data['postal_code'] ?? null,
            $data['city'] ?? null,
            $data['total_members'] ?? 1,
        ]);

        $result = DB::select('SELECT @success as success');
        return (bool) $result[0]->success;
    }

    // Relationships
    public function address()
    {
        return $this->belongsTo(Address::class, 'AddressId', 'Id');
    }

    public function household()
    {
        return $this->hasOne(Household::class, 'ClientId', 'Id');
    }

    public function members()
    {
        return $this->hasMany(HouseholdMember::class, 'ClientId', 'Id');
    }
}