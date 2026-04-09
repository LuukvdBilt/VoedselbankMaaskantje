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
        DB::statement('DROP PROCEDURE IF EXISTS sp_getAllSuppliers;');

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

        DB::statement('DROP PROCEDURE IF EXISTS sp_createSupplier;');

        DB::statement('
            CREATE PROCEDURE sp_createSupplier(
                IN p_CompanyName VARCHAR(255),
                IN p_FirstName VARCHAR(100),
                IN p_LastName VARCHAR(100),
                IN p_Email VARCHAR(255),
                IN p_Street VARCHAR(255),
                IN p_HouseNumber INT,
                IN p_PostalCode VARCHAR(10),
                IN p_City VARCHAR(100),
                IN p_Phone VARCHAR(20)
            )
            BEGIN
                DECLARE v_AddressId INT;
                DECLARE v_ContactId INT;
                DECLARE v_SupplierId INT;
                DECLARE v_UserId BIGINT UNSIGNED;
                
                INSERT INTO users (name, email, password, created_at, updated_at)
                VALUES (CONCAT(p_FirstName, \' \', p_LastName), p_Email, \'PlaceholderPassword\', NOW(), NOW());
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
        ');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement('DROP PROCEDURE IF EXISTS sp_getAllSuppliers');
        DB::statement('DROP PROCEDURE IF EXISTS sp_createSupplier;');
    }
};
