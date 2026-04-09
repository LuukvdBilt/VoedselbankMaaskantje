USE FoodBankDB;

DROP PROCEDURE IF EXISTS sp_getAllSuppliers;

DELIMITER $$

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
    JOIN Contact c ON s.ContactId = c.Id
    JOIN Address a ON c.AddressId = a.Id;
END$$

DELIMITER ;

CALL sp_getAllSuppliers();