USE FoodBankDB;

DROP PROCEDURE IF EXISTS sp_updateSupplier;

DELIMITER $$

CREATE PROCEDURE sp_updateSupplier(
    IN p_supplierId INT,
    IN p_companyName VARCHAR(255),
    IN p_firstName VARCHAR(100),
    IN p_lastName VARCHAR(100),
    IN p_street VARCHAR(255),
    IN p_houseNumber VARCHAR(10),
    IN p_postalCode VARCHAR(50),
    IN p_city VARCHAR(100),
    IN p_phone VARCHAR(20),
    IN p_email VARCHAR(255)
)
BEGIN
    DECLARE affected_rows INT DEFAULT 0;

    -- Update Supplier, Contact, Address en users
    UPDATE Supplier s
    INNER JOIN Contact c ON s.ContactId = c.Id
    INNER JOIN Address a ON c.AddressId = a.Id
    INNER JOIN users u ON c.UserId = u.Id
    SET
        s.CompanyName = p_companyName,
        c.FirstName = p_firstName,
        c.LastName = p_lastName,
        a.Street = p_street,
        a.HouseNumber = p_houseNumber,
        a.PostalCode = p_postalCode,
        a.City = p_city,
        c.Phone = p_phone,
        u.Email = p_email
    WHERE s.Id = p_supplierId;
    SELECT is_active FROM Supplier WHERE Id = p_supplierId; -- Controleer of de leverancier nog steeds actief is

    -- Haal het aantal daadwerkelijk gewijzigde rijen op
    SET affected_rows = ROW_COUNT();

    -- Retourneer dit voor debug/logging
    SELECT affected_rows AS rows_updated;
END$$

DELIMITER ;