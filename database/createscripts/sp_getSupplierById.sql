USE FoodBankDB;

DROP PROCEDURE IF EXISTS sp_getSupplierById;

DELIMITER $$

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
END $$

DELIMITER ;

CALL sp_getSupplierById(1);