<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class Client extends Model
{
    protected $table = 'Client';
    
    protected $primaryKey = 'Id';
    
    public $timestamps = true;
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';
    protected $fillable = [
        'FirstName',
        'LastName',
        'Phone',
        'AddressId',
        'HouseholdId',
        'is_active',
        'note'
    ];
    protected $casts = [
        'is_active' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];
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
        return $this->hasManyThrough(HouseholdMember::class, Household::class, 'ClientId', 'HouseholdId', 'Id', 'Id');
    }
    public function getStreetAttribute()
    {
        return $this->address?->Street;
    }
    public function getHouseNumberAttribute()
    {
        return $this->address?->HouseNumber;
    }
    public function getPostalCodeAttribute()
    {
        return $this->address?->PostalCode;
    }
    public function getCityAttribute()
    {
        return $this->address?->City;
    }
    public function getTotalMembersAttribute()
    {
        return $this->household?->TotalMembers;
    }
    public static function getClientById($id)
    {
        return self::find($id);
    }
    public static function getAllClients()
    {
        return self::with('address', 'household')->get();
    }
    public static function getMembers($householdId)
    {
        return HouseholdMember::where('HouseholdId', $householdId)->get();
    }
    public static function updateClientInfo($id, $data)
    {
        try {
            $client = self::findOrFail($id);
            $client->update([
                'FirstName' => $data['first_name'],
                'LastName' => $data['last_name'],
                'Phone' => $data['phone'],
            ]);
            if ($client->address) {
                $client->address->update([
                    'Street' => $data['street'],
                    'HouseNumber' => $data['house_number'],
                    'PostalCode' => $data['postal_code'],
                    'City' => $data['city'],
                ]);
            }
            if ($client->household) {
                $client->household->update([
                    'TotalMembers' => $data['total_members'],
                ]);
            }
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }
}
