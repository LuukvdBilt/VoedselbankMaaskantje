DROP DATABASE IF EXISTS FoodBankDB;

USE FoodBankDB;

-- Address (gedeeld door Contact en Client)
CREATE TABLE Address (
     Id INT AUTO_INCREMENT PRIMARY KEY NOT NULL
    ,Street VARCHAR(255) NOT NULL
    ,HouseNumber VARCHAR(10) NOT NULL
    ,PostalCode VARCHAR(20) NOT NULL
    ,City VARCHAR(255) NOT NULL
    ,created_at DATETIME(6) NOT NULL DEFAULT NOW(6)
    ,updated_at DATETIME(6) NULL
    ,is_active BIT NOT NULL DEFAULT 1
    ,note VARCHAR(255) NULL
);

-- Contact (voor medewerkers, vrijwilligers en leverancierscontacten)
-- heeft UserId omdat ze een systeemaccount hebben
CREATE TABLE Contact (
     Id INT AUTO_INCREMENT PRIMARY KEY NOT NULL
    ,UserId BIGINT NOT NULL
    ,FirstName VARCHAR(255) NOT NULL
    ,LastName VARCHAR(255) NOT NULL
    ,Phone VARCHAR(12) NOT NULL
    ,AddressId INT NOT NULL
    ,created_at DATETIME(6) NOT NULL DEFAULT NOW(6)
    ,updated_at DATETIME(6) NULL
    ,is_active BIT NOT NULL DEFAULT 1
    ,note VARCHAR(255) NULL
    ,FOREIGN KEY (AddressId) REFERENCES Address(Id)
    ,FOREIGN KEY (UserId) REFERENCES users(id)
);

-- Client (alleen voor klanten/gezinnen)
-- geen UserId omdat klanten geen systeemaccount hebben
CREATE TABLE Client (
     Id INT AUTO_INCREMENT PRIMARY KEY NOT NULL
    ,FirstName VARCHAR(255) NOT NULL
    ,LastName VARCHAR(255) NOT NULL
    ,Phone VARCHAR(12) NOT NULL
    ,AddressId INT NOT NULL
    ,created_at DATETIME(6) NOT NULL DEFAULT NOW(6)
    ,updated_at DATETIME(6) NULL
    ,is_active BIT NOT NULL DEFAULT 1
    ,note VARCHAR(255) NULL
    ,FOREIGN KEY (AddressId) REFERENCES Address(Id)
);

-- Category
CREATE TABLE Category (
     Id INT AUTO_INCREMENT PRIMARY KEY NOT NULL
    ,Name VARCHAR(255) NOT NULL UNIQUE
    ,created_at DATETIME(6) NOT NULL DEFAULT NOW(6)
    ,updated_at DATETIME(6) NULL
    ,is_active BIT NOT NULL DEFAULT 1
    ,note VARCHAR(255) NULL
);

-- Suppliers gekoppeld aan Contact (niet aan Client)
CREATE TABLE Suppliers (
     Id INT AUTO_INCREMENT PRIMARY KEY NOT NULL
    ,CompanyName VARCHAR(255) NOT NULL
    ,ContactId INT NOT NULL
    ,created_at DATETIME(6) NOT NULL DEFAULT NOW(6)
    ,updated_at DATETIME(6) NULL
    ,is_active BIT NOT NULL DEFAULT 1
    ,note VARCHAR(255) NULL
    ,FOREIGN KEY (ContactId) REFERENCES Contact(Id)
);

-- Product
CREATE TABLE Product (
     Id INT AUTO_INCREMENT PRIMARY KEY NOT NULL
    ,Barcode VARCHAR(100) NOT NULL UNIQUE
    ,ProductName VARCHAR(255) NOT NULL
    ,CategoryId INT NOT NULL
    ,SupplierId INT NOT NULL
    ,created_at DATETIME(6) NOT NULL DEFAULT NOW(6)
    ,updated_at DATETIME(6) NULL
    ,is_active BIT NOT NULL DEFAULT 1
    ,note VARCHAR(255) NULL
    ,FOREIGN KEY (CategoryId) REFERENCES Category(Id)
    ,FOREIGN KEY (SupplierId) REFERENCES Suppliers(Id)
);

-- Allergies
CREATE TABLE Allergies (
     Id INT AUTO_INCREMENT PRIMARY KEY NOT NULL
    ,Name VARCHAR(255) NOT NULL UNIQUE
    ,Description VARCHAR(255) NOT NULL
    ,created_at DATETIME(6) NOT NULL DEFAULT NOW(6)
    ,updated_at DATETIME(6) NULL
    ,is_active BIT NOT NULL DEFAULT 1
    ,note VARCHAR(255) NULL
);

-- FoodPackages (Composition eruit, wordt koppeltabel FoodPackage_Products)
CREATE TABLE FoodPackages (
     Id INT AUTO_INCREMENT PRIMARY KEY NOT NULL
    ,Name VARCHAR(255) NOT NULL
    ,Description VARCHAR(255) NOT NULL
    ,created_at DATETIME(6) NOT NULL DEFAULT NOW(6)
    ,updated_at DATETIME(6) NULL
    ,is_active BIT NOT NULL DEFAULT 1
    ,note VARCHAR(255) NULL
);

-- FoodPackage_Products (vervangt Composition VARCHAR)
CREATE TABLE FoodPackage_Products (
     Id INT AUTO_INCREMENT PRIMARY KEY NOT NULL
    ,FoodPackageId INT NOT NULL
    ,ProductId INT NOT NULL
    ,Quantity INT NOT NULL DEFAULT 1
    ,created_at DATETIME(6) NOT NULL DEFAULT NOW(6)
    ,updated_at DATETIME(6) NULL
    ,is_active BIT NOT NULL DEFAULT 1
    ,note VARCHAR(255) NULL
    ,FOREIGN KEY (FoodPackageId) REFERENCES FoodPackages(Id)
    ,FOREIGN KEY (ProductId) REFERENCES Product(Id)
);

-- FoodPackage_Allergies (many-to-many, AllergiesId NOT NULL)
CREATE TABLE FoodPackage_Allergies (
     Id INT AUTO_INCREMENT PRIMARY KEY NOT NULL
    ,FoodPackageId INT NOT NULL
    ,AllergiesId INT NOT NULL
    ,created_at DATETIME(6) NOT NULL DEFAULT NOW(6)
    ,updated_at DATETIME(6) NULL
    ,is_active BIT NOT NULL DEFAULT 1
    ,note VARCHAR(255) NULL
    ,FOREIGN KEY (FoodPackageId) REFERENCES FoodPackages(Id)
    ,FOREIGN KEY (AllergiesId) REFERENCES Allergies(Id)
);

-- Inventory (SupplierId terug, want je wilt weten van wie je iets hebt ingekocht)
CREATE TABLE Inventory (
     Id INT AUTO_INCREMENT PRIMARY KEY NOT NULL
    ,ProductId INT NOT NULL
    ,Quantity INT NOT NULL DEFAULT 0
    ,SupplierId INT NULL
    ,ExpirationDate DATETIME(6) NULL
    ,created_at DATETIME(6) NOT NULL DEFAULT NOW(6)
    ,updated_at DATETIME(6) NULL
    ,is_active BIT NOT NULL DEFAULT 1
    ,note VARCHAR(255) NULL
    ,FOREIGN KEY (ProductId) REFERENCES Product(Id)
    ,FOREIGN KEY (SupplierId) REFERENCES Suppliers(Id)
);

-- Household gekoppeld aan Client
CREATE TABLE Household (
     Id INT AUTO_INCREMENT PRIMARY KEY NOT NULL
    ,ClientId INT NOT NULL
    ,TotalMembers INT NOT NULL DEFAULT 1
    ,RegistrationDate DATETIME(6) NOT NULL DEFAULT NOW(6)
    ,created_at DATETIME(6) NOT NULL DEFAULT NOW(6)
    ,updated_at DATETIME(6) NULL
    ,is_active BIT NOT NULL DEFAULT 1
    ,note VARCHAR(255) NULL
    ,FOREIGN KEY (ClientId) REFERENCES Client(Id)
);

-- HouseholdMember gekoppeld aan Client
CREATE TABLE HouseholdMember (
     Id INT AUTO_INCREMENT PRIMARY KEY NOT NULL
    ,HouseholdId INT NOT NULL
    ,ClientId INT NOT NULL
    ,Relation VARCHAR(50) NOT NULL
    ,DateOfBirth DATE NULL
    ,created_at DATETIME(6) NOT NULL DEFAULT NOW(6)
    ,updated_at DATETIME(6) NULL
    ,is_active BIT NOT NULL DEFAULT 1
    ,note VARCHAR(255) NULL
    ,FOREIGN KEY (HouseholdId) REFERENCES Household(Id)
    ,FOREIGN KEY (ClientId) REFERENCES Client(Id)
);

-- FoodPackageDistribution: vrijwilliger via Contact, gezin via Household
CREATE TABLE FoodPackageDistribution (
     Id INT AUTO_INCREMENT PRIMARY KEY NOT NULL
    ,HouseholdId INT NOT NULL
    ,FoodPackageId INT NOT NULL
    ,DistributionDate DATETIME(6) NOT NULL DEFAULT NOW(6)
    ,VolunteerId INT NULL
    ,created_at DATETIME(6) NOT NULL DEFAULT NOW(6)
    ,updated_at DATETIME(6) NULL
    ,is_active BIT NOT NULL DEFAULT 1
    ,note VARCHAR(255) NULL
    ,FOREIGN KEY (HouseholdId) REFERENCES Household(Id)
    ,FOREIGN KEY (FoodPackageId) REFERENCES FoodPackages(Id)
    ,FOREIGN KEY (VolunteerId) REFERENCES Contact(Id)
);