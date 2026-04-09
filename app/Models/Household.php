// app/Models/Household.php

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\ContactModel;

class Household extends Model
{
    protected $table = 'Household';
    public $timestamps = true;
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    protected $fillable = [
        'ClientId',
        'TotalMembers',
        'RegistrationDate',
        'is_active',
        'note'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'RegistrationDate' => 'datetime'
    ];

    public function client()
    {
        return $this->belongsTo(Client::class, 'ClientId', 'Id');
    }

    public function members()
    {
        return $this->hasMany(HouseholdMember::class, 'HouseholdId', 'Id');
    }

    public function distributions()
    {
        return $this->hasMany(FoodPackageDistribution::class, 'HouseholdId', 'Id');
    }
}