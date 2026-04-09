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
        DB::statement("DROP PROCEDURE IF EXISTS sp_getAllSuppliers;");

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
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement('DROP PROCEDURE IF EXISTS GetAllSuppliers');
    }
};
