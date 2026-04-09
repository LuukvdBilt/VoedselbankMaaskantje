<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        /*
        |--------------------------------------------------------------------------
        | DROP bestaande procedures
        |--------------------------------------------------------------------------
        */
        DB::statement("DROP PROCEDURE IF EXISTS sp_getAllSuppliers;");
        DB::statement("DROP PROCEDURE IF EXISTS sp_getAllAllergies;");

        /*
        |--------------------------------------------------------------------------
        | CREATE sp_getAllSuppliers
        |--------------------------------------------------------------------------
        */
        DB::statement("
            CREATE PROCEDURE sp_getAllSuppliers()
            BEGIN
                SELECT
                    s.Id,
                    CONCAT(a.Street, ' ', a.HouseNumber, ', ', a.PostalCode, ' ', a.City) AS Address,
                    c.Phone,
                    c.FirstName,
                    c.LastName,
                    s.CompanyName
                FROM Supplier s
                LEFT JOIN Contact c ON s.ContactId = c.Id
                LEFT JOIN Address a ON c.AddressId = a.Id;
            END;
        ");

        /*
        |--------------------------------------------------------------------------
        | CREATE sp_getAllAllergies (met JOINs zoals verplicht)
        |--------------------------------------------------------------------------
        */
        DB::statement("
            CREATE PROCEDURE sp_getAllAllergies()
            BEGIN
                SELECT 
                    a.Id,
                    a.Name,
                    a.Description,
                    COUNT(DISTINCT fpa.FoodPackageId) AS TotalFoodPackages,
                    COUNT(DISTINCT fpp.ProductId) AS TotalProducts
                FROM Allergies a
                LEFT JOIN FoodPackage_Allergies fpa
                    ON fpa.AllergiesId = a.Id
                LEFT JOIN FoodPackage_Products fpp
                    ON fpp.FoodPackageId = fpa.FoodPackageId
                GROUP BY a.Id, a.Name, a.Description
                ORDER BY a.Name;
            END;
        ");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement("DROP PROCEDURE IF EXISTS sp_getAllSuppliers;");
        DB::statement("DROP PROCEDURE IF EXISTS sp_getAllAllergies;");
    }
};
