/*
 * Phase 2 Schema File | CS6400 - Summer 2017 | Team 022
 */

CREATE USER IF NOT EXISTS gatechuser@localhost IDENTIFIED BY 'gatech123';
DROP DATABASE IF EXISTS `cs6400_su17_team022`; 
SET default_storage_engine=InnoDB;
SET NAMES utf8mb4 COLLATE utf8mb4_unicode_ci;

CREATE DATABASE IF NOT EXISTS cs6400_su17_team022 
   DEFAULT CHARACTER SET utf8mb4 
   DEFAULT COLLATE utf8mb4_unicode_ci;
USE cs6400_su17_team022;

GRANT SELECT, INSERT, UPDATE, DELETE, FILE ON *.* TO 'gatechUser'@'localhost';
GRANT ALL PRIVILEGES ON `gatechuser`.* TO 'gatechUser'@'localhost';
GRANT ALL PRIVILEGES ON `cs6400_su17_team022`.* TO 'gatechUser'@'localhost';
FLUSH PRIVILEGES;

-- Tables 

CREATE TABLE `User` (
   Username varchar(30) NOT NULL,
   Password varchar(30) NOT NULL,
   FirstName varchar(100) NOT NULL,
   LastName varchar(100) NOT NULL,
   Email varchar(100) NOT NULL,
   UserType varchar(50) NOT NULL,
   PRIMARY KEY (Username),
   UNIQUE KEY Email (Email)
   );

CREATE TABLE Client (
   ClientId int(16) unsigned NOT NULL AUTO_INCREMENT,
   FirstName varchar(100) NOT NULL,
   LastName varchar(100) NOT NULL,
   Description varchar(2000) NOT NULL,
   PhoneNumber int(30),
   PRIMARY KEY (ClientId)
   );

CREATE TABLE Site (
   SiteId int(16) unsigned NOT NULL AUTO_INCREMENT,
   ShortName varchar(100) NOT NULL,
   StreetAddress varchar(1000) NOT NULL,
   City varchar(100) NOT NULL,
   State varchar(30) NOT NULL,
   ZipCode varchar(30) NOT NULL,
   PhoneNumber int(30) NOT NULL,
   PRIMARY KEY (SiteId)
   );   

CREATE TABLE Service (
   FacilityId int(16) unsigned NOT NULL AUTO_INCREMENT,
   PRIMARY KEY (FacilityId)
   );
   
CREATE TABLE ClientService (
   FacilityId int(16) unsigned NOT NULL,
   FacilityName varchar(100) NOT NULL,
   EligibilityCondition varchar(200) NOT NULL,
   HoursOfOperation varchar(50) NOT NULL,
   PRIMARY KEY (FacilityId),
   CONSTRAINT fk_ClientService_FacilityId_Service_FacilityId FOREIGN KEY (FacilityId) REFERENCES Service (FacilityId) ON DELETE CASCADE
   );

CREATE TABLE Shelter (
   ShelterId int(16) unsigned NOT NULL,
   BunkType varchar(50) NOT NULL,
   BunkCountMale int NOT NULL,
   BunkCountFemale int NOT NULL,
   BunkCountMixed int NOT NULL,
   PRIMARY KEY (ShelterId),
   CONSTRAINT fk_Shelter_ShelterId_ClientService_FacilityId FOREIGN KEY (ShelterId) REFERENCES ClientService (FacilityId) ON DELETE CASCADE
   );
   
CREATE TABLE FoodPantry (
   FoodPantryId int(16) unsigned NOT NULL,
   PRIMARY KEY (FoodPantryId),
   CONSTRAINT fk_FoodPantry_FoodPantryId_ClientService_FacilityId FOREIGN KEY (FoodPantryId) REFERENCES ClientService (FacilityId) ON DELETE CASCADE
   );
   
CREATE TABLE SoupKitchen (
   SoupKitchenId int(16) unsigned NOT NULL,
   EligibilityCondition varchar(200) NOT NULL,
   HoursOfOperation varchar(50) NOT NULL,
   SeatCount int NOT NULL,
   PRIMARY KEY (SoupKitchenId),
   CONSTRAINT fk_SoupKitchen_SoupKitchenId_Service_FacilityId FOREIGN KEY (SoupKitchenId) REFERENCES ClientService (FacilityId) ON DELETE CASCADE   
   );
   
CREATE TABLE FoodBank (
   FoodBankId int(16) unsigned NOT NULL,
   PRIMARY KEY (FoodBankId),
   CONSTRAINT fk_FoodBank_FoodBankId_Service_FacilityId FOREIGN KEY (FoodBankId) REFERENCES ClientService (FacilityId) ON DELETE CASCADE   
   );

CREATE TABLE Item (
   ItemId varchar(50) NOT NULL,
   PRIMARY KEY (ItemId)
   );
   
-- Table Matrix   
   
CREATE TABLE UserSite (
   Username varchar(30) NOT NULL,
   SiteId int(16) unsigned NOT NULL,
   PRIMARY KEY (Username,SiteId),
   CONSTRAINT fk_UserSite_Username_User_Username FOREIGN KEY (Username) REFERENCES `User` (Username) ON DELETE CASCADE,
   CONSTRAINT fk_UserSite_SiteId_Site_SiteId FOREIGN KEY (SiteId) REFERENCES Site (SiteId)  ON DELETE CASCADE
   );

CREATE TABLE ClientServiceUsage (
   ClientId int(16) unsigned NOT NULL,
   SiteId int(16) unsigned NOT NULL,
   FacilityId int(16) unsigned NOT NULL,
   Username varchar(30) NOT NULL,
   ServiceDateTime date NOT NULL,
   Description varchar(2000) NOT NULL,
   Note varchar(2000),
   PRIMARY KEY (ClientId,SiteId,FacilityId,Username,ServiceDateTime),
   CONSTRAINT fk_ClientServiceUsage_FacilityId_ClientService_FacilityId  FOREIGN KEY (FacilityId) REFERENCES ClientService (FacilityId) ON DELETE CASCADE,
   CONSTRAINT fk_ClientServiceUsage_ClientId_Client_ClientId  FOREIGN KEY (ClientId) REFERENCES Client (ClientId) ON DELETE CASCADE,
   CONSTRAINT fk_ClientServiceUsage_SiteId_Site_SiteId FOREIGN KEY (SiteId) REFERENCES Site (SiteId)  ON DELETE CASCADE,
   CONSTRAINT fk_ClientServiceUsage_Username_User_Username FOREIGN KEY (Username) REFERENCES `User` (Username)  ON DELETE CASCADE
   );
   
CREATE TABLE ClientLog (
   ClientId int(16) unsigned NOT NULL,
   Username varchar(30) NOT NULL,
   ModifiedDateTime date NOT NULL,
   FieldModified varchar(50) NOT NULL,
   PreviousValue varchar(2000),
   CONSTRAINT fk_ClientLog_ClientId_Client_ClientId FOREIGN KEY (ClientId) REFERENCES Client (ClientId) ON DELETE CASCADE,
   CONSTRAINT fk_ClientLog_Username_User_Username FOREIGN KEY (Username) REFERENCES `User` (Username) ON DELETE CASCADE
   );
   
CREATE TABLE Provides (
   SiteId int(16) unsigned NOT NULL,
   FacilityId int(16) unsigned NOT NULL,
   UNIQUE(SiteId, FacilityId),
   CONSTRAINT fk_Provides_SiteId_Site_SiteId FOREIGN KEY (SiteId) REFERENCES Site (SiteId) ON DELETE CASCADE, 
   CONSTRAINT fk_Provides_FacilityId_Service_FacilityId FOREIGN KEY (FacilityId) REFERENCES Service (FacilityId) ON DELETE CASCADE
   );

CREATE TABLE Stores (
   FoodBankId int(16) unsigned NOT NULL,
   ItemId varchar(50) NOT NULL,
   AvailableQuantity int(10) NOT NULL,
   UNIQUE(FoodBankId, ItemId),
   CONSTRAINT fk_Stores_FoodBankId_FoodBank_FoodBankId FOREIGN KEY (FoodBankId) REFERENCES FoodBank (FoodBankId) ON DELETE CASCADE,
   CONSTRAINT fk_Stores_ItemId_Item_ItemId FOREIGN KEY (ItemId) REFERENCES Item (ItemId) ON DELETE CASCADE
   );
   
   