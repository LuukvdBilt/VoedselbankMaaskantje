DELIMITER $$

CREATE PROCEDURE updateInventory(
    IN p_InventoryId INT,
    IN p_ProductName VARCHAR(255),
    IN p_Barcode VARCHAR(255),
    IN p_Category VARCHAR(255),
    IN p_Supplier VARCHAR(255),
    IN p_Quantity INT,
    IN p_ExpirationDate DATE,
    IN p_InventoryNote TEXT,
    IN p_ProductNote TEXT
)
BEGIN
    -- Update product info
    UPDATE Product
    SET 
        Name = p_ProductName,
        Barcode = p_Barcode,
        Note = p_ProductNote
    WHERE Id = (
        SELECT ProductId FROM Inventory WHERE Id = p_InventoryId
    );

    -- Update category
    UPDATE Category
    SET Name = p_Category
    WHERE Id = (
        SELECT CategoryId FROM Product 
        WHERE Id = (SELECT ProductId FROM Inventory WHERE Id = p_InventoryId)
    );

    -- Update supplier
    UPDATE Supplier
    SET Name = p_Supplier
    WHERE Id = (
        SELECT SupplierId FROM Inventory WHERE Id = p_InventoryId
    );

    -- Update inventory record
    UPDATE Inventory
    SET 
        Quantity = p_Quantity,
        ExpirationDate = p_ExpirationDate,
        Note = p_InventoryNote
    WHERE Id = p_InventoryId;
END $$

DELIMITER ;
