DROP PROCEDURE IF EXISTS sp_deleteSupplier;

DELIMITER $$

CREATE PROCEDURE sp_deleteSupplier (
    IN p_Id INT
)
BEGIN
    DELETE FROM Supplier WHERE Id = p_Id;
END $$

DELIMITER ;

CALL sp_deleteSupplier(1);