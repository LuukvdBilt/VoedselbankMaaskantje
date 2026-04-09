DROP PROCEDURE IF EXISTS sp_getAllAllergies;

DELIMITER $$

CREATE PROCEDURE sp_getAllAllergies()
BEGIN
    SELECT 
        fp.Id AS FoodPackageId,
        fp.Name AS FoodPackageName,
        fp.Description AS FoodPackageDescription,

        a.Id AS AllergyId,
        a.Name AS AllergyName,
        a.Description AS AllergyDescription,

        p.Id AS ProductId,
        p.ProductName AS ProductName,
        p.Barcode AS ProductBarcode

    FROM FoodPackages fp

    -- Allergieën koppeling
    LEFT JOIN FoodPackage_Allergies fpa 
        ON fpa.FoodPackageId = fp.Id

    LEFT JOIN Allergies a 
        ON a.Id = fpa.AllergiesId

    -- Producten koppeling
    LEFT JOIN FoodPackage_Products fpp 
        ON fpp.FoodPackageId = fp.Id

    LEFT JOIN Product p 
        ON p.Id = fpp.ProductId

    ORDER BY fp.Id, a.Name, p.ProductName;
END$$

DELIMITER ;
