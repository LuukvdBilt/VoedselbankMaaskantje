USE FoodBankDB;

DROP PROCEDURE IF EXISTS sp_getAllSuppliers;

DELIMITER $$

CREATE PROCEDURE sp_getAllSuppliers()
BEGIN
    SELECT
        s.Id,
        CONCAT(a.Street, ' ', a.HouseNumber, ', ', a.PostalCode, ' ', a.City) AS Address,
        c.Phone,
        CONCAT(c.FirstName, ' ', c.LastName) AS FullName,
        s.CompanyName
    FROM Supplier s
    LEFT JOIN Contact c ON s.ContactId = c.Id
    LEFT JOIN Address a ON c.AddressId = a.Id;
END$$

DELIMITER ;

CALL sp_getAllSuppliers();
