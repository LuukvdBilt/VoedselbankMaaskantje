DROP PROCEDURE IF EXISTS sp_getAllAllergies;

DELIMITER $$

CREATE PROCEDURE sp_getAllAllergies()
BEGIN
    SELECT 
        a.Id,
        a.Name,
        a.Description,

        COUNT(DISTINCT fpa.FoodPackageId) AS TotalFoodPackages,
        COUNT(DISTINCT fpp.ProductId) AS TotalProducts

    FROM Allergies a

    LEFT JOIN FoodPackage_Allergies fpa
        ON fpa.AllergiesId = a.Id

    LEFT JOIN FoodPackage_Products fpp
        ON fpp.FoodPackageId = fpa.FoodPackageId

    GROUP BY a.Id, a.Name, a.Description
    ORDER BY a.Name;
END$$

DELIMITER ;
