CREATE USER IF NOT EXISTS gatechUser@localhost IDENTIFIED BY 'gatech123';
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
   UserId int(16) unsigned NOT NULL AUTO_INCREMENT,
   Username varchar(100) NOT NULL,
   Password varchar(30) NOT NULL,
   FirstName varchar(100) NOT NULL,
   LastName varchar(100) NOT NULL,
   Email varchar(100) NOT NULL,
   UserType varchar(50) NOT NULL,
   PRIMARY KEY (UserID),
   UNIQUE KEY Username (Username),
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

CREATE TABLE UserSite (
   UserId int(16) unsigned NOT NULL,
   SiteId int(10) NOT NULL,
   PRIMARY KEY (UserId,SiteId),
   FOREIGN KEY (UserId) REFERENCES User (UserId),
   CONSTRAINT fk_UserSite_UserId_User_UserId FOREIGN KEY (UserId) REFERENCES `User` (UserId)  ON DELETE CASCADE,
   CONSTRAINT fk_UserSite_SiteId_Site_SiteId FOREIGN KEY (SiteId) REFERENCES Site (SiteId)  ON DELETE CASCADE
   );

CREATE TABLE ClientService (
   ClientId int(16) unsigned NOT NULL,
   SiteId int(16) unsigned NOT NULL,
   UserId int(16) unsigned NOT NULL,
   ServiceDateTime date NOT NULL,
   Description varchar(2000) NOT NULL,
   Note varchar(2000),
   PRIMARY KEY (ClientId,SiteId,UserId,ServiceDateTime),
   CONSTRAINT fk_ClientService_ClientId_Client_ClientId  FOREIGN KEY (ClientId) REFERENCES Client (ClientId) ON DELETE CASCADE,
   CONSTRAINT fk_ClientService_SiteId_Site_SiteId FOREIGN KEY (SiteId) REFERENCES Site (SiteId)  ON DELETE CASCADE,
   CONSTRAINT fk_ClientService_UserId_User_UserId FOREIGN KEY (UserId) REFERENCES `User` (UserId)  ON DELETE CASCADE
   );
   
CREATE TABLE ClientLog (
   ClientId int(16) unsigned NOT NULL,
   UserId int(16) unsigned NOT NULL,
   ModifiedDateTime date NOT NULL,
   FieldModified varchar(50) NOT NULL,
   PreviousValue varchar(2000),
   CONSTRAINT fk_ClientLog_ClientId_Client_ClientId FOREIGN KEY (ClientId) REFERENCES Client (ClientId) ON DELETE CASCADE,
   CONSTRAINT fk_ClientLog_UserId_User_UserId FOREIGN KEY (UserId) REFERENCES `User` (UserId)  ON DELETE CASCADE
   );

--create service related tables
CREATE TABLE provides (
	SiteId varchar(50) NOT NULL,
	FacilityId varchar(50) NOT NULL,
	UNIQUE(SiteId, FacilityId),
	FOREIGN KEY (SiteId) REFERENCES Site (SiteId),
	FOREIGN KEY (FacilityId) REFERENCES Service (FacilityId)
	);

CREATE TABLE Service (
	FacilityId varchar(50) NOT NULL,
	PRIMARY KEY (FacilityId));
	
CREATE TABLE ClientService (
	FacilityId varchar(50) NOT NULL,
	FacilityName varchar(100) NOT NULL,
	EligibilityCondition varchar(200) NOT NULL,
	HoursOfOperation varchar(50) NOT NULL,
	PRIMARY KEY (FacilityId),
	FOREIGN KEY (FacilityId) REFERENCES Service (FacilityId)
	);

CREATE TABLE Shelter (
	ShelterId varchar(50) NOT NULL,
	BunkType varchar(50) NOT NULL,
	BunkCountMale int NOT NULL,
	BunkCountFemale int NOT NULL,
	BunkCountMixed int NOT NULL,
	PRIMARY KEY (ShelterId),
	FOREIGN KEY (ShelterId) REFERENCES ClientService (FacilityId)
	);
	
CREATE TABLE FoodPantry (
	FoodPantryId varchar(50) NOT NULL,
	PRIMARY KEY (FoodPantryId),
	FOREIGN KEY (FoodPantryId) REFERENCES Service (FacilityName)
	);
	
	
CREATE TABLE SoupKitchen (
	FacilityName varchar(100) NOT NULL,
	EligibilityCondition varchar(200) NOT NULL,
	HoursOfOperation varchar(50) NOT NULL,
	SeatCount int NOT NULL,
	PRIMARY KEY (FacilityName),
	FOREIGN KEY (FacilityName) REFERENCES Service (FacilityName)
	);
	
CREATE TABLE FoodBank (
	FacilityName varchar(100) NOT NULL,
	PRIMARY KEY (FacilityName),
	FOREIGN KEY (FacilityName) REFERENCES Service (FacilityName)
	);
	
CREATE TABLE stores (
	FacilityName varchar(100) NOT NULL,
	ItemId varchar(50) NOT NULL,
	AvailableQuantity int NOT NULL,
	UNIQUE(FacilityName, ItemId),
	FOREIGN KEY (FacilityName) REFERENCES Service (FacilityName),
	FOREIGN KEY (ItemId) REFERENCES Item (ItemId)
	);
	
