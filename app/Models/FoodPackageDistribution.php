<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FoodPackageDistribution extends Model
{
    protected $table = 'FoodPackageDistribution';

    protected $primaryKey = 'Id';

    public $timestamps = true;

    const CREATED_AT = 'created_at';

    const UPDATED_AT = 'updated_at';

    protected $fillable = [
        'HouseholdId',
        'FoodPackageId',
        'VolunteerId',
        'DistributionDate',
        'is_active',
        'note',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'DistributionDate' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function household()
    {
        return $this->belongsTo(Household::class, 'HouseholdId', 'Id');
    }

    public function foodPackage()
    {
        return $this->belongsTo(FoodpackageModel::class, 'FoodPackageId', 'Id');
    }
}
