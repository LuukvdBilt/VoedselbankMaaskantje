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
        DB::statement('DROP PROCEDURE IF EXISTS sp_getAllSuppliers;');
        DB::statement('DROP PROCEDURE IF EXISTS sp_getAllAllergies;');
        DB::statement('DROP PROCEDURE IF EXISTS GetInventory;');

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
                u.Email,
                CONCAT(c.FirstName, ' ', c.LastName) AS FullName,
                s.CompanyName
            FROM Supplier s
            LEFT JOIN Contact c ON s.ContactId = c.Id
            LEFT JOIN Address a ON c.AddressId = a.Id
            LEFT JOIN users u ON c.UserId = u.Id;
            END;
        ");
        DB::statement('
            DROP PROCEDURE IF EXISTS sp_deleteSupplier;
        ');

        DB::statement("
            CREATE PROCEDURE sp_deleteSupplier(
                IN p_Id INT
            )
            BEGIN
                DELETE FROM Supplier WHERE Id = p_Id;
            END;
        ");

        DB::statement('
            DROP PROCEDURE IF EXISTS sp_createSupplier;
        ');

        DB::statement("
            CREATE PROCEDURE sp_createSupplier(
                IN p_CompanyName VARCHAR(255),
                IN p_FirstName VARCHAR(100),
                IN p_LastName VARCHAR(100),
                IN p_Email VARCHAR(255),
                IN p_Phone VARCHAR(20),
                IN p_Street VARCHAR(255),
                IN p_HouseNumber BIGINT,
                IN p_PostalCode VARCHAR(10),
                IN p_City VARCHAR(100)
            )
            BEGIN
                DECLARE v_AddressId INT;
                DECLARE v_ContactId INT;
                DECLARE v_SupplierId INT;
                DECLARE v_UserId BIGINT UNSIGNED;
                
                INSERT INTO users (name, email, password, created_at, updated_at)
                VALUES (CONCAT(p_FirstName, ' ', p_LastName), p_Email, 'PlaceholderPassword', NOW(), NOW());
                SET v_UserId = LAST_INSERT_ID();
                
                INSERT INTO Address (Street, HouseNumber, PostalCode, City, created_at, updated_at)
                VALUES (p_Street, p_HouseNumber, p_PostalCode, p_City, NOW(), NOW());
                SET v_AddressId = LAST_INSERT_ID();
                
                INSERT INTO Contact (FirstName, LastName, UserId, AddressId, Phone, created_at, updated_at)
                VALUES (p_FirstName, p_LastName, v_UserId, v_AddressId, p_Phone, NOW(), NOW());
                SET v_ContactId = LAST_INSERT_ID();
                
                INSERT INTO Supplier (CompanyName, ContactId, created_at, updated_at)
                VALUES (p_CompanyName, v_ContactId, NOW(), NOW());
                SET v_SupplierId = LAST_INSERT_ID();
                
                SELECT
                    s.Id,
                    s.CompanyName,
                    c.Id AS ContactId,
                    c.FirstName,
                    c.LastName,
                    c.UserId,
                    a.Id AS AddressId,
                    a.Street,
                    a.HouseNumber,
                    a.PostalCode,
                    a.City,
                    c.Phone,
                    u.Email
                FROM Supplier s
                LEFT JOIN Contact c ON s.ContactId = c.Id
                LEFT JOIN Address a ON c.AddressId = a.Id
                LEFT JOIN users u ON c.UserId = u.Id
                WHERE s.Id = v_SupplierId;
            END;
        ");

        /*
        |--------------------------------------------------------------------------
        | CREATE sp_getSupplierById
        |--------------------------------------------------------------------------
        */
        DB::statement('
            CREATE PROCEDURE sp_getSupplierById(
                IN supplierId INT
            )
            BEGIN
                SELECT
                    s.Id,
                    a.Street,
                    a.HouseNumber,
                    a.PostalCode,
                    a.City,
                    c.Phone,
                    u.Email,
                    c.FirstName,
                    c.LastName,
                    s.CompanyName
                FROM Supplier s
                LEFT JOIN Contact c ON s.ContactId = c.Id
                LEFT JOIN Address a ON c.AddressId = a.Id
                LEFT JOIN users u ON c.UserId = u.Id
                WHERE s.Id = supplierId;
            END;
        ');

        /*
        |--------------------------------------------------------------------------
        | CREATE sp_updateSupplier
        |--------------------------------------------------------------------------
        */
        DB::statement('
            CREATE PROCEDURE sp_updateSupplier(
            IN p_supplierId INT,
            IN p_companyName VARCHAR(255),
            IN p_firstName VARCHAR(100),
            IN p_lastName VARCHAR(100),
            IN p_street VARCHAR(255),
            IN p_houseNumber VARCHAR(10),
            IN p_postalCode VARCHAR(50),
            IN p_city VARCHAR(100),
            IN p_phone VARCHAR(20),
            IN p_email VARCHAR(255)
            )
            BEGIN
            DECLARE affected_rows INT DEFAULT 0;

            UPDATE Supplier s
            INNER JOIN Contact c ON s.ContactId = c.Id
            INNER JOIN Address a ON c.AddressId = a.Id
            INNER JOIN users u ON c.UserId = u.Id
            SET
                s.CompanyName = p_companyName,
                c.FirstName = p_firstName,
                c.LastName = p_lastName,
                a.Street = p_street,
                a.HouseNumber = p_houseNumber,
                a.PostalCode = p_postalCode,
                a.City = p_city,
                c.Phone = p_phone,
                u.Email = p_email
            WHERE s.Id = p_supplierId;

            SET affected_rows = ROW_COUNT();
            SELECT affected_rows AS rows_updated;
            END;
        ');

        /*
        |--------------------------------------------------------------------------
        | CREATE sp_getAllAllergies (met JOINs zoals verplicht)
        |--------------------------------------------------------------------------
        */
        DB::statement('
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
        ');

        /*
        |--------------------------------------------------------------------------
        | CREATE GetInventory
        |--------------------------------------------------------------------------
        */
        DB::statement('
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
        ');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement('DROP PROCEDURE IF EXISTS sp_getAllSuppliers;');
        DB::statement('DROP PROCEDURE IF EXISTS sp_getAllAllergies;');
        DB::statement('DROP PROCEDURE IF EXISTS GetAllSuppliers');
        DB::statement('DROP PROCEDURE IF EXISTS GetInventory');
    }
};
