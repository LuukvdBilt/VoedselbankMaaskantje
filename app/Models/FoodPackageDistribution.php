// app/Models/FoodPackageDistribution.php

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use App\Models\Contact;

class FoodPackageDistribution extends Model
{
    protected $table = 'FoodPackageDistribution';
    public $timestamps = true;
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    protected $fillable = [
        'HouseholdId',
        'FoodPackageId',
        'DistributionDate',
        'VolunteerId',
        'is_active',
        'note'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'DistributionDate' => 'datetime'
    ];

    // Get distributions for household
    public static function getHouseholdDistributions($householdId)
    {
        return DB::select('CALL sp_GetHouseholdDistributions(?)', [$householdId]);
    }

    // Create new distribution
    public static function createDistribution($householdId, $foodPackageId, $volunteerId = null)
    {
        DB::select('CALL sp_CreateDistribution(?, ?, ?, @id)', [
            $householdId,
            $foodPackageId,
            $volunteerId
        ]);

        $idResult = DB::select('SELECT @id as id');
        return $idResult[0]->id ?? false;
    }

    // Update distribution
    public static function updateDistribution($id, $foodPackageId, $note = null)
    {
        DB::select('CALL sp_UpdateDistribution(?, ?, ?, @success)', [
            $id,
            $foodPackageId,
            $note
        ]);

        $result = DB::select('SELECT @success as success');
        return (bool) $result[0]->success;
    }

    // Delete distribution
    public static function deleteDistribution($id)
    {
        DB::select('CALL sp_DeleteDistribution(?, @success)', [$id]);
        $result = DB::select('SELECT @success as success');
        return (bool) $result[0]->success;
    }

    // Relationships
    public function household()
    {
        return $this->belongsTo(Household::class, 'HouseholdId', 'Id');
    }

    public function foodPackage()
    {
        return $this->belongsTo(FoodPackage::class, 'FoodPackageId', 'Id');
    }

    public function volunteer()
    {
        return $this->belongsTo(ContactModel::class, 'VolunteerId', 'Id');
    }
}