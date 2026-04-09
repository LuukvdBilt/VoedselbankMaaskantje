-- ============================================
-- CLIENT & HOUSEHOLD STORED PROCEDURES
-- ============================================

-- Get all clients (customers)
DELIMITER $$
CREATE PROCEDURE sp_GetAllClients()
BEGIN
    SELECT 
        c.Id,
        c.FirstName,
        c.LastName,
        c.Phone,
        a.Street,
        a.HouseNumber,
        a.PostalCode,
        a.City,
        c.is_active,
        c.note,
        c.created_at
    FROM Client c
    JOIN Address a ON c.AddressId = a.Id
    WHERE c.is_active = 1
    ORDER BY c.created_at DESC;
END $$
DELIMITER ;

-- Get client by ID with household info
DELIMITER $$
CREATE PROCEDURE sp_GetClientById(IN p_id INT)
BEGIN
    SELECT 
        c.Id,
        c.FirstName,
        c.LastName,
        c.Phone,
        a.Id as AddressId,
        a.Street,
        a.HouseNumber,
        a.PostalCode,
        a.City,
        h.Id as HouseholdId,
        h.TotalMembers,
        h.RegistrationDate,
        c.is_active,
        c.note
    FROM Client c
    LEFT JOIN Address a ON c.AddressId = a.Id
    LEFT JOIN Household h ON h.ClientId = c.Id AND h.is_active = 1
    WHERE c.Id = p_id AND c.is_active = 1
    LIMIT 1;
END $$
DELIMITER ;

-- Get household members
DELIMITER $$
CREATE PROCEDURE sp_GetHouseholdMembers(IN p_household_id INT)
BEGIN
    SELECT 
        hm.Id,
        hm.ClientId,
        c.FirstName,
        c.LastName,
        hm.Relation,
        hm.DateOfBirth,
        hm.is_active
    FROM HouseholdMember hm
    JOIN Client c ON hm.ClientId = c.Id
    WHERE hm.HouseholdId = p_household_id AND hm.is_active = 1
    ORDER BY hm.created_at ASC;
END $$
DELIMITER ;

-- Update client (with address)
DELIMITER $$
CREATE PROCEDURE sp_UpdateClient(
    IN p_id INT,
    IN p_first_name VARCHAR(255),
    IN p_last_name VARCHAR(255),
    IN p_phone VARCHAR(12),
    IN p_street VARCHAR(255),
    IN p_house_number VARCHAR(10),
    IN p_postal_code VARCHAR(20),
    IN p_city VARCHAR(255),
    IN p_total_members INT,
    OUT p_success BIT
)
BEGIN
    DECLARE EXIT HANDLER FOR SQLEXCEPTION
    BEGIN
        SET p_success = 0;
        ROLLBACK;
    END;
    
    START TRANSACTION;
    
    -- Get address ID from client
    SELECT c.AddressId INTO @address_id
    FROM Client c
    WHERE c.Id = p_id;
    
    -- Update Client table
    UPDATE Client
    SET FirstName = p_first_name,
        LastName = p_last_name,
        Phone = p_phone,
        updated_at = NOW(6)
    WHERE Id = p_id AND is_active = 1;
    
    -- Update Address table
    UPDATE Address
    SET Street = p_street,
        HouseNumber = p_house_number,
        PostalCode = p_postal_code,
        City = p_city,
        updated_at = NOW(6)
    WHERE Id = @address_id;
    
    -- Update Household total members
    UPDATE Household
    SET TotalMembers = p_total_members,
        updated_at = NOW(6)
    WHERE ClientId = p_id AND is_active = 1;
    
    COMMIT;
    SET p_success = 1;
END $$
DELIMITER ;

-- ============================================
-- FOOD PACKAGE DISTRIBUTION PROCEDURES
-- ============================================

-- Get all distributions for a household
DELIMITER $$
CREATE PROCEDURE sp_GetHouseholdDistributions(IN p_household_id INT)
BEGIN
    SELECT 
        fpd.Id,
        fpd.HouseholdId,
        fpd.FoodPackageId,
        fp.Name as PackageName,
        fp.Description as PackageDescription,
        fpd.DistributionDate,
        fpd.VolunteerId,
        CONCAT(ct.FirstName, ' ', ct.LastName) as VolunteerName,
        fpd.is_active,
        fpd.note,
        fpd.created_at
    FROM FoodPackageDistribution fpd
    JOIN FoodPackages fp ON fpd.FoodPackageId = fp.Id
    LEFT JOIN Contact ct ON fpd.VolunteerId = ct.Id
    WHERE fpd.HouseholdId = p_household_id AND fpd.is_active = 1
    ORDER BY fpd.DistributionDate DESC;
END $$
DELIMITER ;

-- Create new distribution
DELIMITER $$
CREATE PROCEDURE sp_CreateDistribution(
    IN p_household_id INT,
    IN p_food_package_id INT,
    IN p_volunteer_id INT,
    OUT p_id INT
)
BEGIN
    INSERT INTO FoodPackageDistribution (
        HouseholdId,
        FoodPackageId,
        DistributionDate,
        VolunteerId,
        is_active,
        created_at
    ) VALUES (
        p_household_id,
        p_food_package_id,
        NOW(6),
        p_volunteer_id,
        1,
        NOW(6)
    );
    
    SET p_id = LAST_INSERT_ID();
END $$
DELIMITER ;

-- Update distribution
DELIMITER $$
CREATE PROCEDURE sp_UpdateDistribution(
    IN p_id INT,
    IN p_food_package_id INT,
    IN p_note TEXT,
    OUT p_success BIT
)
BEGIN
    UPDATE FoodPackageDistribution
    SET FoodPackageId = p_food_package_id,
        note = p_note,
        updated_at = NOW(6)
    WHERE Id = p_id AND is_active = 1;
    
    SET p_success = IF(ROW_COUNT() > 0, 1, 0);
END $$
DELIMITER ;

-- Delete/Deactivate distribution
DELIMITER $$
CREATE PROCEDURE sp_DeleteDistribution(
    IN p_id INT,
    OUT p_success BIT
)
BEGIN
    UPDATE FoodPackageDistribution
    SET is_active = 0,
        updated_at = NOW(6)
    WHERE Id = p_id;
    
    SET p_success = IF(ROW_COUNT() > 0, 1, 0);
END $$
DELIMITER ;

-- Get all food packages
DELIMITER $$
CREATE PROCEDURE sp_GetAllFoodPackages()
BEGIN
    SELECT 
        Id,
        Name,
        Description,
        is_active
    FROM FoodPackages
    WHERE is_active = 1
    ORDER BY Name ASC;
END $$
DELIMITER ;