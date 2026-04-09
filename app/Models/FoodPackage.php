// app/Models/FoodPackage.php

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class FoodPackage extends Model
{
    protected $table = 'FoodPackages';
    public $timestamps = true;
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    protected $fillable = [
        'Name',
        'Description',
        'is_active',
        'note'
    ];

    protected $casts = [
        'is_active' => 'boolean'
    ];

    // Get all food packages
    public static function getAllPackages()
    {
        return DB::select('CALL sp_GetAllFoodPackages()');
    }

    public function distributions()
    {
        return $this->hasMany(FoodPackageDistribution::class, 'FoodPackageId', 'Id');
    }
}