/*
 * Phase 3 Schema File | CS6400 - Summer 2017 | Team 022
 */

CREATE USER IF NOT EXISTS gatechuser@localhost IDENTIFIED BY 'gatech123';
DROP DATABASE IF EXISTS `cs6400_su17_team022`; 
SET default_storage_engine=InnoDB;
SET NAMES utf8mb4 COLLATE utf8mb4_unicode_ci;

CREATE DATABASE IF NOT EXISTS cs6400_su17_team022 
   DEFAULT CHARACTER SET utf8mb4 
   DEFAULT COLLATE utf8mb4_unicode_ci;
USE cs6400_su17_team022;

GRANT SELECT, INSERT, UPDATE, DELETE, FILE ON *.* TO 'gatechuser'@'localhost';
GRANT ALL PRIVILEGES ON `gatechuser`.* TO 'gatechuser'@'localhost';
GRANT ALL PRIVILEGES ON `cs6400_su17_team022`.* TO 'gatechuser'@'localhost';
FLUSH PRIVILEGES;

-- Tables 

CREATE TABLE Site (
   SiteId int(16) unsigned NOT NULL AUTO_INCREMENT,
   ShortName varchar(100) NOT NULL,
   StreetAddress varchar(1000) NOT NULL,
   City varchar(100) NOT NULL,
   State varchar(30) NOT NULL,
   ZipCode varchar(30) NOT NULL,
   PhoneNumber varchar(30) NOT NULL,
   PRIMARY KEY (SiteId)
   );   

CREATE TABLE `User` (
   Username varchar(30) NOT NULL,
   Password varchar(30) NOT NULL,
   FirstName varchar(100) NOT NULL,
   LastName varchar(100) NOT NULL,
   Email varchar(100) NOT NULL,
   UserType varchar(50) NOT NULL,
   SiteId int(16) unsigned NOT NULL,
   PRIMARY KEY (Username),
   UNIQUE KEY Email (Email),
   CONSTRAINT fk_User_SiteId_Site_SiteId FOREIGN KEY (SiteId) REFERENCES Site (SiteId) ON DELETE CASCADE
   );

CREATE TABLE Client (
   ClientId int(16) unsigned NOT NULL AUTO_INCREMENT,
   FirstName varchar(100) NOT NULL,
   LastName varchar(100) NOT NULL,
   Description varchar(2000) NOT NULL,
   PhoneNumber varchar(30),
   PRIMARY KEY (ClientId)
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
   FacilityId int(16) unsigned NOT NULL,
   BunkType varchar(50) NOT NULL,
   BunkCountMale int NOT NULL,
   BunkCountFemale int NOT NULL,
   BunkCountMixed int NOT NULL,
   PRIMARY KEY (FacilityId),
   CONSTRAINT fk_Shelter_FacilityId_ClientService_FacilityId FOREIGN KEY (FacilityId) REFERENCES ClientService (FacilityId) ON DELETE CASCADE
   );
   
CREATE TABLE FoodPantry(
   FacilityId int(16) unsigned NOT NULL,
   PRIMARY KEY (FacilityId),
   CONSTRAINT fk_FoodPantry_FacilityId_ClientService_FacilityId FOREIGN KEY (FacilityId) REFERENCES ClientService (FacilityId) ON DELETE CASCADE
   );
   
CREATE TABLE SoupKitchen (
   FacilityId int(16) unsigned NOT NULL,
   SeatCount int NOT NULL,
   PRIMARY KEY (FacilityId),
   CONSTRAINT fk_SoupKitchen_FacilityId_Service_FacilityId FOREIGN KEY (FacilityId) REFERENCES ClientService (FacilityId) ON DELETE CASCADE   
   );
   
CREATE TABLE FoodBank (
   FacilityId int(16) unsigned NOT NULL,
   PRIMARY KEY (FacilityId),
   CONSTRAINT fk_FoodBank_FacilityId_Service_FacilityId FOREIGN KEY (FacilityId) REFERENCES ClientService (FacilityId) ON DELETE CASCADE   
   );

CREATE TABLE Item (
   ItemId int(16) unsigned NOT NULL,
   Name varchar(50) NOT NULL,
   StorageType varchar(50) NOT NULL,
   ExpirationDate varchar(50) NOT NULL,
   ItemType varchar(50) NOT NULL,
   PRIMARY KEY (ItemId)
);

-- Table Matrix   
   
CREATE TABLE ClientServiceUsage (
   ClientId int(16) unsigned NOT NULL,
   SiteId int(16) unsigned NOT NULL,
   FacilityId int(16) unsigned NOT NULL,
   Username varchar(30) NOT NULL,
   ServiceDateTime datetime NOT NULL,
   Description varchar(2000) NOT NULL,
   Note varchar(2000),
   CONSTRAINT fk_ClientServiceUsage_FacilityId_ClientService_FacilityId  FOREIGN KEY (FacilityId) REFERENCES ClientService (FacilityId) ON DELETE CASCADE,
   CONSTRAINT fk_ClientServiceUsage_ClientId_Client_ClientId  FOREIGN KEY (ClientId) REFERENCES Client (ClientId) ON DELETE CASCADE,
   CONSTRAINT fk_ClientServiceUsage_SiteId_Site_SiteId FOREIGN KEY (SiteId) REFERENCES Site (SiteId)  ON DELETE CASCADE,
   CONSTRAINT fk_ClientServiceUsage_Username_User_Username FOREIGN KEY (Username) REFERENCES `User` (Username)  ON DELETE CASCADE
   );
   
CREATE TABLE ClientLog (
   ClientId int(16) unsigned NOT NULL,
   Username varchar(30) NOT NULL,
   ModifiedDateTime datetime NOT NULL,
   FieldModified varchar(50) NOT NULL,
   PreviousValue varchar(2000),
   CONSTRAINT fk_ClientLog_ClientId_Client_ClientId FOREIGN KEY (ClientId) REFERENCES Client (ClientId) ON DELETE CASCADE,
   CONSTRAINT fk_ClientLog_Username_User_Username FOREIGN KEY (Username) REFERENCES `User` (Username) ON DELETE CASCADE
   );
   
CREATE TABLE SiteToService (
   SiteId int(16) unsigned NOT NULL,
   FacilityId int(16) unsigned NOT NULL,
   UNIQUE(SiteId, FacilityId),
   CONSTRAINT fk_SiteToServiec_SiteId_Site_SiteId FOREIGN KEY (SiteId) REFERENCES Site (SiteId) ON DELETE CASCADE, 
   CONSTRAINT fk_SiteToService_FacilityId_Service_FacilityId FOREIGN KEY (FacilityId) REFERENCES Service (FacilityId) ON DELETE CASCADE
   );

CREATE TABLE FoodBankToItem (
   FacilityId int(16) unsigned NOT NULL,
   ItemId int(16) unsigned NOT NULL,
   AvailableQuantity int(10) NOT NULL,
   UNIQUE(FacilityId, ItemId),
   CONSTRAINT fk_FoodBankToItem_FacilityId_FoodBank_FacilityId FOREIGN KEY (FacilityId) REFERENCES FoodBank (FacilityId) ON DELETE CASCADE,
   CONSTRAINT fk_FoodBankToItem_ItemId_Item_ItemId FOREIGN KEY (ItemId) REFERENCES Item (ItemId) ON DELETE CASCADE
   );

CREATE TABLE Request (
   RequestID int(16) unsigned NOT NULL AUTO_INCREMENT,
   Username varchar(30) NOT NULL,
   FacilityId int(16) unsigned NOT NULL,
   ItemId int(16) unsigned NOT NULL,
   Status varchar(50) NOT NULL,
   QuantityRequested int NOT NULL,
   QuantityFulfilled int NOT NULL,
   PRIMARY KEY (RequestID, Username, FacilityId),
   CONSTRAINT fk_Request_Username_User_Username FOREIGN KEY (Username) REFERENCES `User` (Username) ON DELETE CASCADE,
   CONSTRAINT fk_Request_FacilityId_FoodBank_FacilityId FOREIGN KEY (FacilityId) REFERENCES FoodBank (FacilityId) ON DELETE CASCADE,
   CONSTRAINT fk_Request_ItemId_Item_ItemId FOREIGN KEY (ItemId) REFERENCES Item (ItemId) ON DELETE CASCADE
);

CREATE TABLE Food (
   ItemId int(16) unsigned NOT NULL,
   FoodCategory varchar(50) NOT NULL,
   PRIMARY KEY (ItemId),
   CONSTRAINT fk_Food_ItemId_Item_ItemId FOREIGN KEY (ItemId) REFERENCES Item (ItemId) ON DELETE CASCADE
);

CREATE TABLE Supply (
   ItemId int(16) unsigned NOT NULL,
   SupplyCategory varchar(50) NOT NULL,
   PRIMARY KEY (ItemId),
   CONSTRAINT fk_Supply_ItemId_Item_ItemId FOREIGN KEY (ItemId) REFERENCES Item (ItemId) ON DELETE CASCADE
);

/*
 * Phase 3 Demo Data File | CS6400 - Summer 2017 | Team 022
 */

DELIMITER @@

DROP PROCEDURE IF EXISTS insertUser @@

-- Insert User data 
CREATE PROCEDURE insertUser(maxCnt INT, usertype VARCHAR(255))
BEGIN

	DECLARE username VARCHAR (255);
	DECLARE firstname VARCHAR (255);
	DECLARE lastname VARCHAR (255);
	DECLARE email VARCHAR (255);
    DECLARE cnt INT;
	SET cnt = 1;
    WHILE cnt <= maxCnt DO
		IF (usertype = 'Employee') THEN
			SET username = CONCAT('emp',cnt);
		ELSE
			SET username = CONCAT('vol',cnt);
        END IF;
		
        SET firstname = CONCAT('Site',cnt);
        SET lastname = CONCAT(usertype,cnt);
        SET email = CONCAT(firstname,'@',lastname,'.com');
        
        insert into User (username,password,firstname,lastname,email,usertype,siteid)
		values (username,'gatech123',firstname,lastname,email,usertype,cnt);
		
		SET cnt = cnt + 1;
    END WHILE;
END @@

DROP PROCEDURE IF EXISTS insertClient @@

-- Insert Client 
CREATE PROCEDURE insertClient(maxCnt INT)
BEGIN

	DECLARE firstname VARCHAR (255);
	DECLARE lastname VARCHAR (255);
	DECLARE description VARCHAR (255);
	DECLARE username VARCHAR (255);
    DECLARE cnt INT;
	SET cnt = 1;
    WHILE cnt <= maxCnt DO
        SET lastname = CONCAT('Client',cnt);
        SET description = CONCAT('TestID',cnt);
        
        IF ((cnt % 2) = 1) THEN
        	SET firstname = 'Joe';
		ELSE
        	SET firstname = 'Jane';
		END IF;
		
		-- Insert client
		insert into Client (clientId,firstName,lastName,description)
		values (cnt,firstname,lastname,description);
		
		SET cnt = cnt + 1;
    END WHILE;
END @@

DROP PROCEDURE IF EXISTS insertClientLog @@

-- Insert ClientLog
CREATE PROCEDURE insertClientLog()
BEGIN

	insert into ClientLog (clientId,username,modifiedDateTime,fieldModified,previousValue)
	select clientId,'emp1',now(),'FirstName',concat(firstName,lastName) 
	from Client;
		
END @@

delimiter ;

delete from Site;
insert into Site (siteId,shortName,streetAddress,city,state,zipCode,phoneNumber)
values
(1,'site1','1 Street','Irvine','CA','92618','9492223331'),
(2,'site2','2 Street','Irvine','CA','92618','9492223332'),
(3,'site3','3 Street','Irvine','CA','92618','9492223333');

delete from User;
call insertUser(3,'Employee');
call insertUser(3,'Volunteer');

delete from Client;
call insertClient(12);

delete from ClientLog;
call insertClientLog();

select * from Site;
select * from User;
select * from Client;
select * from ClientLog;

