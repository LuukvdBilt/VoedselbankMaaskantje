DELIMITER $$

CREATE PROCEDURE UpdateInventory(
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

END $$

DELIMITER ;
