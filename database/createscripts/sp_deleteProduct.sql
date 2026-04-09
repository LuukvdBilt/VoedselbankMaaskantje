DELIMITER $$

CREATE PROCEDURE DeleteProductById(IN productId INT)
BEGIN
    DELETE FROM Product WHERE Id = productId;
END $$

DELIMITER ;