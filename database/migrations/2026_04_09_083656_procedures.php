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
        DB::statement('DROP PROCEDURE IF EXISTS DeleteInventoryById;');
        DB::statement('DROP PROCEDURE IF EXISTS updateInventory;');
        DB::statement('DROP PROCEDURE IF EXISTS InsertInventory;');
        DB::statement('DROP PROCEDURE IF EXISTS sp_getSupplierById;');
        DB::statement('DROP PROCEDURE IF EXISTS sp_updateSupplier;');
        DB::statement('DROP PROCEDURE IF EXISTS sp_createSupplier;');
        DB::statement('DROP PROCEDURE IF EXISTS sp_deleteSupplier;');
        DB::statement('DROP PROCEDURE IF EXISTS sp_getAllSuppliers;');
        DB::statement('DROP PROCEDURE IF EXISTS sp_getAllAllergies;');
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
                LEFT JOIN Product p ON i.ProductId = p.Id
                LEFT JOIN Category c ON p.CategoryId = c.Id
                LEFT JOIN Supplier s ON i.SupplierId = s.Id
                WHERE i.is_active = 1
                ORDER BY p.ProductName;
            END;
        ');

        /*
        |--------------------------------------------------------------------------
        | CREATE DeleteProductById
        |--------------------------------------------------------------------------
        */
        DB::statement('
            CREATE PROCEDURE DeleteInventoryById(IN inventoryId INT)
                BEGIN
                    DELETE FROM Inventory WHERE Id = inventoryId;
                END;

        ');

        /*
        |--------------------------------------------------------------------------
        | CREATE UpdateInventory
        |--------------------------------------------------------------------------
        */
        DB::statement('
            CREATE PROCEDURE updateInventory(
                IN p_InventoryId BIGINT,
                IN p_ProductName VARCHAR(255),
                IN p_Barcode VARCHAR(100),
                IN p_Category VARCHAR(255),
                IN p_Supplier VARCHAR(255),
                IN p_Quantity INT,
                IN p_ExpirationDate DATETIME,
                IN p_InventoryNote VARCHAR(255),
                IN p_ProductNote VARCHAR(255)
            )
            BEGIN
                DECLARE v_ProductId BIGINT;
                DECLARE v_CategoryId BIGINT;
                DECLARE v_SupplierId BIGINT;

                -- Haal IDs op
                SELECT ProductId, SupplierId INTO v_ProductId, v_SupplierId
                FROM Inventory
                WHERE Id = p_InventoryId;

                SELECT CategoryId INTO v_CategoryId
                FROM Product
                WHERE Id = v_ProductId;

                -- Update product
                UPDATE Product
                SET 
                    ProductName = p_ProductName,
                    Barcode = p_Barcode,
                    note = p_ProductNote
                WHERE Id = v_ProductId;

                -- Update category
                UPDATE Category
                SET Name = p_Category
                WHERE Id = v_CategoryId;

                -- Update supplier
                UPDATE Supplier
                SET CompanyName = p_Supplier
                WHERE Id = v_SupplierId;

                -- Update inventory
                UPDATE Inventory
                SET 
                    Quantity = p_Quantity,
                    ExpirationDate = p_ExpirationDate,
                    note = p_InventoryNote
                WHERE Id = p_InventoryId;

            END
        ');

        /*
        |--------------------------------------------------------------------------
        | CREATE InsertInventory
        |--------------------------------------------------------------------------
        */
        DB::statement('
            CREATE PROCEDURE InsertInventory(
                IN p_ProductName VARCHAR(255),
                IN p_Barcode VARCHAR(100),
                IN p_Category VARCHAR(255),
                IN p_Supplier VARCHAR(255),
                IN p_Quantity INT,
                IN p_ExpirationDate DATETIME,
                IN p_InventoryNote VARCHAR(255),
                IN p_ProductNote VARCHAR(255)
            )
            BEGIN
                DECLARE v_CategoryId BIGINT;
                DECLARE v_SupplierId BIGINT;
                DECLARE v_ProductId BIGINT;

                -- CATEGORY: bestaat deze al?
                SELECT Id INTO v_CategoryId
                FROM Category
                WHERE Name COLLATE utf8mb4_unicode_ci = p_Category COLLATE utf8mb4_unicode_ci
                LIMIT 1;

                -- Zo niet → aanmaken
                IF v_CategoryId IS NULL THEN
                    INSERT INTO Category (Name)
                    VALUES (p_Category);

                    SET v_CategoryId = LAST_INSERT_ID();
                END IF;

                -- SUPPLIER: bestaat deze al?
                SELECT Id INTO v_SupplierId
                FROM Supplier
                WHERE CompanyName COLLATE utf8mb4_unicode_ci = p_Supplier COLLATE utf8mb4_unicode_ci
                LIMIT 1;

                -- Zo niet → aanmaken (ContactId verplicht → dummy contact)
                IF v_SupplierId IS NULL THEN
                    INSERT INTO Contact (UserId, FirstName, LastName)
                    VALUES (1, p_Supplier, \'AutoGenerated\');

                    INSERT INTO Supplier (CompanyName, ContactId)
                    VALUES (p_Supplier, LAST_INSERT_ID());

                    SET v_SupplierId = LAST_INSERT_ID();
                END IF;

                -- PRODUCT aanmaken
                INSERT INTO Product (Barcode, ProductName, CategoryId, SupplierId, note)
                VALUES (p_Barcode, p_ProductName, v_CategoryId, v_SupplierId, p_ProductNote);

                SET v_ProductId = LAST_INSERT_ID();

                -- INVENTORY aanmaken
                INSERT INTO Inventory (ProductId, SupplierId, Quantity, ExpirationDate, note)
                VALUES (v_ProductId, v_SupplierId, p_Quantity, p_ExpirationDate, p_InventoryNote);

            END
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
        DB::statement('DROP PROCEDURE IF EXISTS DeleteInventoryById');
        DB::statement('DROP PROCEDURE IF EXISTS updateInventory;');
        DB::statement('DROP PROCEDURE IF EXISTS InsertInventory;');
    }
};
