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
