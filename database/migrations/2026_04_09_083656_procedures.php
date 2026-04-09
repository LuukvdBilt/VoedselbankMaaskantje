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

        DB::statement("DROP PROCEDURE IF EXISTS GetInventory;");

        DB::statement("
            CREATE PROCEDURE GetInventory()
            BEGIN
                SELECT 
                    i.Id AS InventoryId,
                    p.ProductName,
                    p.Barcode,
                    c.Name AS Category,
                    s.CompanyName AS Supplier,
                    i.Quantity,
                    i.ExpirationDate,
                    i.note AS InventoryNote,
                    p.note AS ProductNote
                FROM Inventory i
                INNER JOIN Product p ON i.ProductId = p.Id
                INNER JOIN Category c ON p.CategoryId = c.Id
                LEFT JOIN Supplier s ON i.SupplierId = s.Id
                WHERE i.is_active = 1
                ORDER BY p.ProductName;
            END;
        ");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement('DROP PROCEDURE IF EXISTS GetAllSuppliers');
        DB::statement('DROP PROCEDURE IF EXISTS GetInventory');
    }
};
