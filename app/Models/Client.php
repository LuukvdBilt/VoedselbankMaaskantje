<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    // ===== TABLE CONFIGURATION =====
    protected $table = 'Client';          // MySQL table name (doesn't auto-pluralize)

    protected $primaryKey = 'Id';         // Primary key column (default is 'id', we use 'Id')

    // ===== TIMESTAMPS =====
    public $timestamps = true;            // Enable created_at/updated_at columns

    const CREATED_AT = 'created_at';      // Column name for creation timestamp

    const UPDATED_AT = 'updated_at';      // Column name for update timestamp

    // ===== MASS ASSIGNMENT =====
    // Specify which columns can be mass-assigned (security feature - prevents bulk injection)
    protected $fillable = [
        'FirstName',                      // Customer's first name
        'LastName',                       // Customer's last name
        'Phone',                          // Contact phone number
        'AddressId',                      // Foreign key → Address table
        'HouseholdId',                    // (Optional) Denormalized field for quick lookups
        'is_active',                      // Boolean: true=active customer, false=inactive
        'note',                            // Additional notes about customer
    ];

    // ===== TYPE CASTING =====
    // Automatically convert to correct data types
    protected $casts = [
        'is_active' => 'boolean',         // Store as 0/1, retrieve as true/false
        'created_at' => 'datetime',       // Convert timestamp string to Carbon object
        'updated_at' => 'datetime',        // Same as above
    ];

    // ===== RELATIONSHIPS =====

    /**
     * RELATIONSHIP 1: Belongs To One Address
     * One Client has ONE Address (many Client can share same Address)
     * Foreign Key: Client.AddressId → Address.Id
     */
    public function address()
    {
        // belongsTo(Model, foreignKey, primaryKey)
        // SQL joins: Client.AddressId = Address.Id
        return $this->belongsTo(Address::class, 'AddressId', 'Id');
    }
    // Usage: $client->address  or  $client->address->PostalCode
    // Returns: Address model or NULL if AddressId is null

    /**
     * RELATIONSHIP 2: Has One Household
     * One Client has ONE Household (one-to-one, one household per family unit)
     * Foreign Key: Household.ClientId → Client.Id
     */
    public function household()
    {
        // hasOne(Model, foreignKey, localKey)
        // SQL: Household.ClientId = Client.Id
        return $this->hasOne(Household::class, 'ClientId', 'Id');
    }
    // Usage: $client->household
    // Returns: Household model or NULL if no household exists

    /**
     * RELATIONSHIP 3: Has Many Through (Indirect relationship)
     * One Client has MANY HouseholdMembers through their Household
     * Pattern: Client → Household → HouseholdMembers
     * Example: Client has spouse and 2 kids (3 members total)
     */
    public function members()
    {
        // hasManyThrough(targetModel, intermediateModel,
        //                foreignKeyOnIntermediate, foreignKeyOnTarget,
        //                localKey, localKeyOnIntermediate)
        return $this->hasManyThrough(
            HouseholdMember::class,      // What we want: HouseholdMembers
            Household::class,             // Go through: Household
            'ClientId',                   // Household.ClientId points back to Client
            'HouseholdId',               // HouseholdMember.HouseholdId points to Household
            'Id',                        // Client.Id (local key)
            'Id'                         // Household.Id (intermediate local key)
        );
    }
    // Usage: $client->members (returns collection of HouseholdMembers)
    // SQL: SELECT * FROM HouseholdMember WHERE HouseholdId IN (SELECT Id FROM Household WHERE ClientId = 1)

    // ===== ATTRIBUTE ACCESSORS (Virtual Attributes) =====
    // These allow: $client->street instead of $client->address->street
    // Null-safe operator ?-> prevents "Trying to get property of null" errors

    /**
     * Get street from related Address
     *
     * @return string|null
     */
    public function getStreetAttribute()
    {
        // ?-> operator = Null-safe navigation (PHP 8+)
        // Returns NULL if $this->address is null, otherwise returns Street value
        return $this->address?->Street;
    }

    /**
     * Get house number from related Address
     *
     * @return string|null
     */
    public function getHouseNumberAttribute()
    {
        return $this->address?->HouseNumber;
    }

    /**
     * Get postal code from related Address
     *
     * @return string|null
     */
    public function getPostalCodeAttribute()
    {
        return $this->address?->PostalCode;
    }

    /**
     * Get city from related Address
     *
     * @return string|null
     */
    public function getCityAttribute()
    {
        return $this->address?->City;
    }

    /**
     * Get total household members from related Household
     *
     * @return int|null
     */
    public function getTotalMembersAttribute()
    {
        return $this->household?->TotalMembers;
    }

    // ===== STATIC HELPER METHODS =====
    // These are convenient shortcuts for common queries

    /**
     * Get single client by ID with relationships loaded
     *
     * @param  int  $id  Client.Id
     * @return Client|null
     */
    public static function getClientById($id)
    {
        // find($id) returns model or NULL (doesn't throw)
        // SQL: SELECT * FROM Client WHERE Id = $id LIMIT 1
        return self::find($id);
    }

    /**
     * Get all clients with address and household data loaded
     * Prevents N+1 query problem by eager loading relationships
     *
     * @return Collection<Client>
     */
    public static function getAllClients()
    {
        // with() = Eager load relationships (gets them in one query instead of N queries)
        // SQL: SELECT * FROM Client; SELECT * FROM Address WHERE Id IN (...); SELECT * FROM Household WHERE ClientId IN (...)
        return self::with('address', 'household')->get();
    }

    /**
     * Get all household members for a specific household
     *
     * @param  int  $householdId  Household.Id
     * @return Collection<HouseholdMember>
     */
    public static function getMembers($householdId)
    {
        // Query HouseholdMember table directly, not through Client
        return HouseholdMember::where('HouseholdId', $householdId)->get();
    }

    /**
     * Update client info across multiple tables (Client, Address, Household)
     * Uses try-catch for error handling, returns boolean instead of throwing
     *
     * @param  int  $id  Client.Id
     * @param  array  $data  Array with keys: first_name, last_name, phone, street, house_number, postal_code, city, total_members
     * @return bool true on success, false on failure
     */
    public static function updateClientInfo($id, $data)
    {
        try {
            // FIND OR THROW: If client not found, throw ModelNotFoundException
            $client = self::findOrFail($id);

            // UPDATE CLIENT: Update personal info
            // SQL: UPDATE Client SET FirstName='...', LastName='...', Phone='...', updated_at=NOW() WHERE Id=X
            $client->update([
                'FirstName' => $data['first_name'],
                'LastName' => $data['last_name'],
                'Phone' => $data['phone'],
            ]);

            // UPDATE ADDRESS: If address exists, update its fields
            // Defensive check: $client->address might be NULL
            if ($client->address) {
                // SQL: UPDATE Address SET Street='...', HouseNumber='...', PostalCode='...', City='...', updated_at=NOW() WHERE Id=X
                $client->address->update([
                    'Street' => $data['street'],
                    'HouseNumber' => $data['house_number'],
                    'PostalCode' => $data['postal_code'],
                    'City' => $data['city'],
                ]);
            }

            // UPDATE HOUSEHOLD: If household exists, update member count
            // Defensive check: $client->household might be NULL
            if ($client->household) {
                // SQL: UPDATE Household SET TotalMembers=X, updated_at=NOW() WHERE Id=Y
                $client->household->update([
                    'TotalMembers' => $data['total_members'],
                ]);
            }

            // SUCCESS: All updates completed without exception
            return true;

        } catch (\Exception $e) {
            // ERROR: Any exception caught and logged
            // Return false instead of throwing so caller can decide what to do
            // Example: database error, validation error, record not found, etc.
            return false;
        }
    }
}
