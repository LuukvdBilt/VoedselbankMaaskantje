DROP DATABASE IF EXISTS FoodBankDB;
CREATE DATABASE FoodBankDB;
USE FoodBankDB;

-- =====================
-- ADDRESS
-- =====================
CREATE TABLE Address (
    Id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    Street VARCHAR(255) NOT NULL,
    HouseNumber INT NOT NULL,
    Addition VARCHAR(10) NULL,
    PostalCode VARCHAR(40) NOT NULL,
    City VARCHAR(100) NOT NULL,
    created_at DATETIME(6) DEFAULT CURRENT_TIMESTAMP(6),
    updated_at DATETIME(6) NULL ON UPDATE CURRENT_TIMESTAMP(6),
    is_active BOOLEAN DEFAULT TRUE,
    note VARCHAR(255) NULL
);

-- =====================
-- CONTACT
-- =====================
CREATE TABLE Contact (
    Id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    UserId BIGINT UNSIGNED NOT NULL,
    FirstName VARCHAR(100) NOT NULL,
    LastName VARCHAR(100) NOT NULL,
    Phone VARCHAR(20) NULL,
    AddressId BIGINT UNSIGNED NULL,
    created_at DATETIME(6) DEFAULT CURRENT_TIMESTAMP(6),
    updated_at DATETIME(6) NULL ON UPDATE CURRENT_TIMESTAMP(6),
    is_active BOOLEAN DEFAULT TRUE,
    note VARCHAR(255) NULL,

    CONSTRAINT fk_contact_user
        FOREIGN KEY (UserId) REFERENCES users(id) ON DELETE CASCADE,

    CONSTRAINT fk_contact_address
        FOREIGN KEY (AddressId) REFERENCES Address(Id) ON DELETE SET NULL
);

-- =====================
-- CLIENT
-- =====================
CREATE TABLE Client (
    Id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    FirstName VARCHAR(100) NOT NULL,
    LastName VARCHAR(100) NOT NULL,
    Phone VARCHAR(20) NULL,
    AddressId BIGINT UNSIGNED NULL,
    created_at DATETIME(6) DEFAULT CURRENT_TIMESTAMP(6),
    updated_at DATETIME(6) NULL ON UPDATE CURRENT_TIMESTAMP(6),
    is_active BOOLEAN DEFAULT TRUE,
    note VARCHAR(255) NULL,

    CONSTRAINT fk_client_address
        FOREIGN KEY (AddressId) REFERENCES Address(Id) ON DELETE SET NULL
);

-- =====================
-- CATEGORY
-- =====================
CREATE TABLE Category (
    Id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    Name VARCHAR(100) NOT NULL UNIQUE,
    created_at DATETIME(6) DEFAULT CURRENT_TIMESTAMP(6),
    updated_at DATETIME(6) NULL,
    is_active BOOLEAN DEFAULT TRUE,
    note VARCHAR(255) NULL
);

-- =====================
-- SUPPLIER
-- =====================
CREATE TABLE Supplier (
    Id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    CompanyName VARCHAR(255) NOT NULL,
    ContactId BIGINT UNSIGNED NOT NULL,
    created_at DATETIME(6) DEFAULT CURRENT_TIMESTAMP(6),
    updated_at DATETIME(6) NULL,
    is_active BOOLEAN DEFAULT TRUE,
    note VARCHAR(255) NULL,

    CONSTRAINT fk_supplier_contact
        FOREIGN KEY (ContactId) REFERENCES Contact(Id) ON DELETE CASCADE
);

-- =====================
-- PRODUCT
-- =====================
CREATE TABLE Product (
    Id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    Barcode VARCHAR(100) NOT NULL UNIQUE,
    ProductName VARCHAR(255) NOT NULL,
    CategoryId BIGINT UNSIGNED NOT NULL,
    SupplierId BIGINT UNSIGNED NOT NULL,
    created_at DATETIME(6) DEFAULT CURRENT_TIMESTAMP(6),
    updated_at DATETIME(6) NULL,
    is_active BOOLEAN DEFAULT TRUE,
    note VARCHAR(255) NULL,

    CONSTRAINT fk_product_category
        FOREIGN KEY (CategoryId) REFERENCES Category(Id),

    CONSTRAINT fk_product_supplier
        FOREIGN KEY (SupplierId) REFERENCES Supplier(Id)
);

-- =====================
-- ALLERGIES
-- =====================
CREATE TABLE Allergies (
    Id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    Name VARCHAR(255) NOT NULL UNIQUE,
    Description VARCHAR(255) NULL,
    created_at DATETIME(6) DEFAULT CURRENT_TIMESTAMP(6),
    updated_at DATETIME(6) NULL,
    is_active BOOLEAN DEFAULT TRUE,
    note VARCHAR(255) NULL
);

-- =====================
-- FOOD PACKAGES
-- =====================
CREATE TABLE FoodPackages (
    Id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    Name VARCHAR(255) NOT NULL,
    Description VARCHAR(255) NULL,
    created_at DATETIME(6) DEFAULT CURRENT_TIMESTAMP(6),
    updated_at DATETIME(6) NULL,
    is_active BOOLEAN DEFAULT TRUE,
    note VARCHAR(255) NULL
);

-- =====================
-- FOOD PACKAGE ↔ PRODUCT
-- =====================
CREATE TABLE FoodPackage_Product (
    Id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    FoodPackageId BIGINT UNSIGNED NOT NULL,
    ProductId BIGINT UNSIGNED NOT NULL,
    Quantity INT DEFAULT 1,
    created_at DATETIME(6) DEFAULT CURRENT_TIMESTAMP(6),
    updated_at DATETIME(6) NULL,
    is_active BOOLEAN DEFAULT TRUE,
    note VARCHAR(255) NULL,

    UNIQUE (FoodPackageId, ProductId),

    FOREIGN KEY (FoodPackageId) REFERENCES FoodPackages(Id) ON DELETE CASCADE,
    FOREIGN KEY (ProductId) REFERENCES Product(Id) ON DELETE CASCADE
);

-- =====================
-- FOOD PACKAGE ↔ ALLERGY
-- =====================
CREATE TABLE FoodPackage_Allergy (
    Id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    FoodPackageId BIGINT UNSIGNED NOT NULL,
    AllergyId BIGINT UNSIGNED NOT NULL,
    created_at DATETIME(6) DEFAULT CURRENT_TIMESTAMP(6),
    updated_at DATETIME(6) NULL,
    is_active BOOLEAN DEFAULT TRUE,
    note VARCHAR(255) NULL,

    UNIQUE (FoodPackageId, AllergyId),

    FOREIGN KEY (FoodPackageId) REFERENCES FoodPackages(Id) ON DELETE CASCADE,
    FOREIGN KEY (AllergyId) REFERENCES Allergies(Id) ON DELETE CASCADE
);

-- =====================
-- INVENTORY
-- =====================
CREATE TABLE Inventory (
    Id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    ProductId BIGINT UNSIGNED NOT NULL,
    SupplierId BIGINT UNSIGNED NULL,
    Quantity INT DEFAULT 0,
    ExpirationDate DATETIME NULL,
    created_at DATETIME(6) DEFAULT CURRENT_TIMESTAMP(6),
    updated_at DATETIME(6) NULL,
    is_active BOOLEAN DEFAULT TRUE,
    note VARCHAR(255) NULL,

    FOREIGN KEY (ProductId) REFERENCES Product(Id),
    FOREIGN KEY (SupplierId) REFERENCES Supplier(Id) ON DELETE SET NULL
);

-- =====================
-- HOUSEHOLD
-- =====================
CREATE TABLE Household (
    Id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    ClientId BIGINT UNSIGNED NOT NULL,
    TotalMembers INT DEFAULT 1,
    RegistrationDate DATETIME DEFAULT CURRENT_TIMESTAMP,
    created_at DATETIME(6) DEFAULT CURRENT_TIMESTAMP(6),
    updated_at DATETIME(6) NULL,
    is_active BOOLEAN DEFAULT TRUE,
    note VARCHAR(255) NULL,

    FOREIGN KEY (ClientId) REFERENCES Client(Id) ON DELETE CASCADE
);

-- =====================
-- HOUSEHOLD MEMBER
-- =====================
CREATE TABLE HouseholdMember (
    Id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    HouseholdId BIGINT UNSIGNED NOT NULL,
    FirstName VARCHAR(255) NOT NULL,
    LastName VARCHAR(255) NULL,
    Relation VARCHAR(50) NULL,
    DateOfBirth DATE NULL,
    created_at DATETIME(6) DEFAULT CURRENT_TIMESTAMP(6),
    updated_at DATETIME(6) NULL,
    is_active BOOLEAN DEFAULT TRUE,
    note VARCHAR(255) NULL,

    FOREIGN KEY (HouseholdId) REFERENCES Household(Id) ON DELETE CASCADE
);

-- =====================
-- DISTRIBUTION
-- =====================
CREATE TABLE FoodPackageDistribution (
    Id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    HouseholdId BIGINT UNSIGNED NOT NULL,
    FoodPackageId BIGINT UNSIGNED NOT NULL,
    VolunteerId BIGINT UNSIGNED NULL,
    DistributionDate DATETIME DEFAULT CURRENT_TIMESTAMP,
    created_at DATETIME(6) DEFAULT CURRENT_TIMESTAMP(6),
    updated_at DATETIME(6) NULL,
    is_active BOOLEAN DEFAULT TRUE,
    note VARCHAR(255) NULL,

    FOREIGN KEY (HouseholdId) REFERENCES Household(Id) ON DELETE CASCADE,
    FOREIGN KEY (FoodPackageId) REFERENCES FoodPackages(Id),
    FOREIGN KEY (VolunteerId) REFERENCES Contact(Id) ON DELETE SET NULL
);

USE FoodBankDB;

-- =====================
-- ADDRESS
-- =====================
INSERT INTO Address (Id, Street, HouseNumber, PostalCode, City) VALUES
(1, 'Hoofdstraat', 1, '1234AB', 'Utrecht'),
(2, 'Dorpsweg', 12, '2345BC', 'Amsterdam'),
(3, 'Kerklaan', 5, '3456CD', 'Rotterdam'),
(4, 'Schoolstraat', 22, '4567DE', 'Den Haag'),
(5, 'Stationsweg', 8, '5678EF', 'Eindhoven');

-- =====================
-- USERS (Laravel)
-- =====================
INSERT INTO users (id, name, email, password) VALUES
(1, 'Admin User', 'admin@test.nl', 'password'),
(2, 'John Doe', 'john@test.nl', 'password'),
(3, 'Jane Smith', 'jane@test.nl', 'password'),
(4, 'Mike Brown', 'mike@test.nl', 'password'),
(5, 'Lisa White', 'lisa@test.nl', 'password');

-- =====================
-- CONTACT
-- =====================
INSERT INTO Contact (Id, UserId, FirstName, LastName, Phone, AddressId) VALUES
(1, 1, 'Admin', 'User', '0611111111', 1),
(2, 2, 'John', 'Doe', '0622222222', 2),
(3, 3, 'Jane', 'Smith', '0633333333', 3),
(4, 4, 'Mike', 'Brown', '0644444444', 4),
(5, 5, 'Lisa', 'White', '0655555555', 5);

-- =====================
-- CLIENT
-- =====================
INSERT INTO Client (Id, FirstName, LastName, Phone, AddressId) VALUES
(1, 'Ali', 'Khan', '0612345678', 1),
(2, 'Sara', 'Jansen', '0623456789', 2),
(3, 'Tom', 'Bakker', '0634567890', 3),
(4, 'Emma', 'Visser', '0645678901', 4),
(5, 'Noah', 'De Vries', '0656789012', 5);

-- =====================
-- CATEGORY
-- =====================
INSERT INTO Category (Id, Name) VALUES
(1, 'Groenten'),
(2, 'Fruit'),
(3, 'Zuivel'),
(4, 'Vlees'),
(5, 'Dranken');

-- =====================
-- SUPPLIER
-- =====================
INSERT INTO Supplier (Id, CompanyName, ContactId) VALUES
(1, 'Albert Heijn', 1),
(2, 'Jumbo', 2),
(3, 'Lidl', 3),
(4, 'Aldi', 4),
(5, 'Plus', 5);

-- =====================
-- PRODUCT
-- =====================
INSERT INTO Product (Id, Barcode, ProductName, CategoryId, SupplierId) VALUES
(1, '111', 'Appel', 2, 1),
(2, '222', 'Melk', 3, 2),
(3, '333', 'Broccoli', 1, 3),
(4, '444', 'Kipfilet', 4, 4),
(5, '555', 'Cola', 5, 5);

-- =====================
-- ALLERGIES
-- =====================
INSERT INTO Allergies (Id, Name, Description) VALUES
(1, 'Gluten', 'Gluten allergie'),
(2, 'Lactose', 'Lactose intolerantie'),
(3, 'Noten', 'Noten allergie'),
(4, 'Soja', 'Soja allergie'),
(5, 'Vis', 'Vis allergie');

-- =====================
-- FOOD PACKAGES
-- =====================
INSERT INTO FoodPackages (Id, Name, Description) VALUES
(1, 'Basispakket', 'Standaard voedselpakket'),
(2, 'Gezinspakket', 'Voor gezinnen'),
(3, 'Vegetarisch pakket', 'Geen vlees'),
(4, 'Kindpakket', 'Voor kinderen'),
(5, 'Senior pakket', 'Voor ouderen');

-- =====================
-- FOOD PACKAGE PRODUCT
-- =====================
INSERT INTO FoodPackage_Product (FoodPackageId, ProductId, Quantity) VALUES
(1, 1, 5),
(1, 2, 2),
(2, 3, 3),
(3, 1, 4),
(4, 5, 6);

-- =====================
-- FOOD PACKAGE ALLERGY
-- =====================
INSERT INTO FoodPackage_Allergy (FoodPackageId, AllergyId) VALUES
(1, 1),
(2, 2),
(3, 3),
(4, 4),
(5, 5);

-- =====================
-- INVENTORY
-- =====================
INSERT INTO Inventory (ProductId, SupplierId, Quantity, ExpirationDate) VALUES
(1, 1, 100, '2026-12-31'),
(2, 2, 50, '2026-10-10'),
(3, 3, 75, '2026-09-01'),
(4, 4, 30, '2026-08-15'),
(5, 5, 200, '2027-01-01');

-- =====================
-- HOUSEHOLD
-- =====================
INSERT INTO Household (Id, ClientId, TotalMembers) VALUES
(1, 1, 3),
(2, 2, 4),
(3, 3, 2),
(4, 4, 5),
(5, 5, 1);

-- =====================
-- HOUSEHOLD MEMBER
-- =====================
INSERT INTO HouseholdMember (HouseholdId, FirstName, LastName, Relation) VALUES
(1, 'Ali', 'Khan', 'Hoofd'),
(2, 'Sara', 'Jansen', 'Moeder'),
(3, 'Tom', 'Bakker', 'Vader'),
(4, 'Emma', 'Visser', 'Dochter'),
(5, 'Noah', 'De Vries', 'Alleenstaand');

-- =====================
-- DISTRIBUTION
-- =====================
INSERT INTO FoodPackageDistribution (HouseholdId, FoodPackageId, VolunteerId) VALUES
(1, 1, 1),
(2, 2, 2),
(3, 3, 3),
(4, 4, 4),
(5, 5, 5);