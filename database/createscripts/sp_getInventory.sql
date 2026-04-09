USE FoodBankDB;

DROP PROCEDURE IF EXISTS getInventory;

DELIMITER $$

CREATE PROCEDURE getInventory()

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
END$$

DELIMITER ;

CALL getInventory();