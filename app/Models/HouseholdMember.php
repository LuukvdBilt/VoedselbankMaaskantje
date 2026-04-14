// app/Models/HouseholdMember.php

<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class HouseholdMember extends Model
{
    protected $table = 'HouseholdMember';

    protected $primaryKey = 'Id';

    public $timestamps = true;

    const CREATED_AT = 'created_at';

    const UPDATED_AT = 'updated_at';

    protected $fillable = [
        'HouseholdId',
        'ClientId',
        'Relation',
        'DateOfBirth',
        'is_active',
        'note',
    ];

    protected $casts = [
        'DateOfBirth' => 'date',
        'is_active' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // Get household
    public function household()
    {
        return $this->belongsTo(Household::class, 'HouseholdId', 'Id');
    }

    // Get client (family member)
    public function client()
    {
        return $this->belongsTo(Client::class, 'ClientId', 'Id');
    }

    // Scope to get only active members
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    // Get member full name
    public function getFullNameAttribute()
    {
        return $this->client ? "{$this->client->FirstName} {$this->client->LastName}" : '';
    }

    // Get age from date of birth
    public function getAgeAttribute()
    {
        if (! $this->DateOfBirth) {
            return null;
        }

        return Carbon::parse($this->DateOfBirth)->age;
    }
}
