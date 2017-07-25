/*
 * Phase 3 Schema File | CS6400 - Summer 2017 | Team 022
 */

CREATE USER IF NOT EXISTS gatechUser@localhost IDENTIFIED BY 'gatech123';
DROP DATABASE IF EXISTS `cs6400_su17_team022`; 
SET default_storage_engine=InnoDB;
SET NAMES utf8mb4 COLLATE utf8mb4_unicode_ci;

CREATE DATABASE IF NOT EXISTS cs6400_su17_team022 
   DEFAULT CHARACTER SET utf8mb4 
   DEFAULT COLLATE utf8mb4_unicode_ci;
USE cs6400_su17_team022;

GRANT SELECT, INSERT, UPDATE, DELETE, FILE ON *.* TO 'gatechUser'@'localhost';
GRANT ALL PRIVILEGES ON `gatechUser`.* TO 'gatechUser'@'localhost';
GRANT ALL PRIVILEGES ON `cs6400_su17_team022`.* TO 'gatechUser'@'localhost';
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
   SiteId int(16) unsigned NOT NULL,
   FacilityType varchar(50) NOT NULL,
   FacilityName varchar(100) NOT NULL,
   EligibilityCondition varchar(200) NOT NULL,
   HoursOfOperation varchar(50) NOT NULL,
   PRIMARY KEY (FacilityId),
   CONSTRAINT fk_ClientService_FacilityId_Service_FacilityId FOREIGN KEY (FacilityId) REFERENCES Service (FacilityId) ON DELETE CASCADE,
   CONSTRAINT fk_ClientServiec_SiteId_Site_SiteId FOREIGN KEY (SiteId) REFERENCES Site (SiteId) ON DELETE CASCADE
   );

CREATE TABLE Shelter (
   FacilityId int(16) unsigned NOT NULL,
   BunkType varchar(50) NOT NULL,
   BunkCountMale int NOT NULL,
   BunkCountFemale int NOT NULL,
   BunkCountMixed int NOT NULL,
   BunkCapacityMale int NOT NULL,
   BunkCapacityFemale int NOT NULL,
   BunkCapacityMixed int NOT NULL,
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
   SeatAvail int NOT NULL,
   SeatTotal int NOT NULL,
   PRIMARY KEY (FacilityId),
   CONSTRAINT fk_SoupKitchen_FacilityId_Service_FacilityId FOREIGN KEY (FacilityId) REFERENCES ClientService (FacilityId) ON DELETE CASCADE   
   );
   
CREATE TABLE FoodBank (
   FacilityId int(16) unsigned NOT NULL,
   FacilityName varchar(100) NOT NULL,
   PRIMARY KEY (FacilityId),
   CONSTRAINT fk_FoodBank_FacilityId_Service_FacilityId FOREIGN KEY (FacilityId) REFERENCES Service (FacilityId) ON DELETE CASCADE   
   );

CREATE TABLE Item (
   ItemId int(16) unsigned NOT NULL AUTO_INCREMENT,
   Name varchar(50) NOT NULL,
   StorageType varchar(50) NOT NULL,
   ExpDate varchar(50) NOT NULL,
   Category varchar(50) NOT NULL,
   SubCategory varchar(50) NOT NULL,
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

-- Inserting 12 Clients' phone number --
UPDATE Client SET PhoneNumber='1-300-738-3425' WHERE ClientId='1';
UPDATE Client SET PhoneNumber='189-582-8672' WHERE ClientId='2';
UPDATE Client SET PhoneNumber='1-846-970-2514x674' WHERE ClientId='3';
UPDATE Client SET PhoneNumber='1-956-852-3137' WHERE ClientId='4';
UPDATE Client SET PhoneNumber='344-314-6224x35864' WHERE ClientId='5';
UPDATE Client SET PhoneNumber='(146)580-0101x239' WHERE ClientId='6';
UPDATE Client SET PhoneNumber='(724)357-5470' WHERE ClientId='7';
UPDATE Client SET PhoneNumber='(380)537-9608' WHERE ClientId='8';
UPDATE Client SET PhoneNumber='(715)152-7932x59008' WHERE ClientId='9';
UPDATE Client SET PhoneNumber='1-161-888-4845' WHERE ClientId='10';
UPDATE Client SET PhoneNumber='789.552.3165' WHERE ClientId='11';
UPDATE Client SET PhoneNumber='251.251.9671x156' WHERE ClientId='12';

-- Inserting 9 Services --
INSERT INTO Service (FacilityId) VALUES ('11');
INSERT INTO Service (FacilityId) VALUES ('12');
INSERT INTO Service (FacilityId) VALUES ('13');
INSERT INTO Service (FacilityId) VALUES ('21');
INSERT INTO Service (FacilityId) VALUES ('22');
INSERT INTO Service (FacilityId) VALUES ('31');
INSERT INTO Service (FacilityId) VALUES ('32');
INSERT INTO Service (FacilityId) VALUES ('41');
INSERT INTO Service (FacilityId) VALUES ('42');

-- Inserting 6 ClientServices --
INSERT INTO ClientService (FacilityId,SiteId,FacilityType,FacilityName,HoursOfOperation,EligibilityCondition) VALUES ('31','1','FoodPantry','pantry1','8:00AM-6:00PM Monday-Friday','Drivers License/PhotoID');
INSERT INTO ClientService (FacilityId,SiteId,FacilityType,FacilityName,HoursOfOperation,EligibilityCondition) VALUES ('41','2','SoupKitchen','soup2','8:00AM-6:00PM Monday-Friday','Drivers License/PhotoID');
INSERT INTO ClientService (FacilityId,SiteId,FacilityType,FacilityName,HoursOfOperation,EligibilityCondition) VALUES ('21','2','Shelter','shelter2','9:00PM-9:00AM Sunday-Saturday','PhotoID');
INSERT INTO ClientService (FacilityId,SiteId,FacilityType,FacilityName,HoursOfOperation,EligibilityCondition) VALUES ('32','3','FoodPantry','pantry3','8:00AM-6:00PM Monday-Friday','Drivers License/PhotoID');
INSERT INTO ClientService (FacilityId,SiteId,FacilityType,FacilityName,HoursOfOperation,EligibilityCondition) VALUES ('42','3','SoupKitchen','soup3','8:00AM-6:00PM Monday-Friday','Drivers License/PhotoID');
INSERT INTO ClientService (FacilityId,SiteId,FacilityType,FacilityName,HoursOfOperation,EligibilityCondition) VALUES ('22','3','Shelter','shelter3','9:00PM-9:00AM Sunday-Saturday','PhotoID');

-- Inserting 3 FoodBanks --
INSERT INTO FoodBank (FacilityId,FacilityName) VALUES ('11','foodbank1');
INSERT INTO FoodBank (FacilityId,FacilityName) VALUES ('12','foodbank2');
INSERT INTO FoodBank (FacilityId,FacilityName) VALUES ('13','foodbank3');

-- Inserting 2 Shelters --
INSERT INTO Shelter (FacilityId,BunkType,BunkCountMale,BunkCountFemale,BunkCountMixed,BunkCapacityMale,BunkCapacityFemale,BunkCapacityMixed) VALUES ('21','male/female/mixed','2','2','2','20','20','20');
INSERT INTO Shelter (FacilityId,BunkType,BunkCountMale,BunkCountFemale,BunkCountMixed,BunkCapacityMale,BunkCapacityFemale,BunkCapacityMixed) VALUES ('22','male/female/mixed','2','2','2','20','20','20');

-- Inserting 2 FoodPantries --
INSERT INTO FoodPantry (FacilityId) VALUES ('31');
INSERT INTO FoodPantry (FacilityId) VALUES ('32');

-- Inserting 2 SoupKitchens --
INSERT INTO SoupKitchen (FacilityId,SeatAvail,SeatTotal) VALUES ('31','30','100');
INSERT INTO SoupKitchen (FacilityId,SeatAvail,SeatTotal) VALUES ('32','10','100');

-- Inserting 3 SiteToServices --
INSERT INTO SiteToService (FacilityId,SiteId) VALUES ('11','1');
INSERT INTO SiteToService (FacilityId,SiteId) VALUES ('12','2');
INSERT INTO SiteToService (FacilityId,SiteId) VALUES ('13','3');

-- Inserting Items --
INSERT INTO Item (ItemId,Name,ExpDate,StorageType,Category,SubCategory) VALUES ('1','kale',NOW()+INTERVAL 10 DAY,'Refrigerated','Food','Vegetables');
INSERT INTO Item (ItemId,Name,ExpDate,StorageType,Category,SubCategory) VALUES ('2','spinach',NOW()+INTERVAL 10 DAY,'Refrigerated','Food','Vegetables');
INSERT INTO Item (ItemId,Name,ExpDate,StorageType,Category,SubCategory) VALUES ('3','peanuts',NOW()+INTERVAL 10 DAY,'Drygoods','Food','Nuts/grains/beans');
INSERT INTO Item (ItemId,Name,ExpDate,StorageType,Category,SubCategory) VALUES ('4','almonds',NOW()+INTERVAL 10 DAY,'Drygoods','Food','Nuts/grains/beans');
INSERT INTO Item (ItemId,Name,ExpDate,StorageType,Category,SubCategory) VALUES ('5','pepsi',NOW()+INTERVAL 10 DAY,'Refrigerated','Food','Juice/drink');
INSERT INTO Item (ItemId,Name,ExpDate,StorageType,Category,SubCategory) VALUES ('6','club soda',NOW()+INTERVAL 10 DAY,'Refrigerated','Food','Juice/drink');
INSERT INTO Item (ItemId,Name,ExpDate,StorageType,Category,SubCategory) VALUES ('7','beef',NOW()+INTERVAL 10 DAY,'Frozen','Food','Meat/seafood');
INSERT INTO Item (ItemId,Name,ExpDate,StorageType,Category,SubCategory) VALUES ('8','pork',NOW()+INTERVAL 10 DAY,'Frozen','Food','Meat/seafood');
INSERT INTO Item (ItemId,Name,ExpDate,StorageType,Category,SubCategory) VALUES ('9','chaddar cheese',NOW()+INTERVAL 10 DAY,'Refrigerated','Food','Dairy/eggs');
INSERT INTO Item (ItemId,Name,ExpDate,StorageType,Category,SubCategory) VALUES ('10','swiss cheese',NOW()+INTERVAL 10 DAY,'Refrigerated','Food','Dairy/eggs');
INSERT INTO Item (ItemId,Name,ExpDate,StorageType,Category,SubCategory) VALUES ('11','toothbrush',NOW()+INTERVAL 10 DAY,'Drygoods','Supply','Personal hygiene'),
                                                                               ('12','toothpaste',NOW()+INTERVAL 10 DAY,'Drygoods','Supply','Personal hygiene'),
																			   ('13','shampoo',NOW()+INTERVAL 10 DAY,'Drygoods','Supply','Personal hygiene'),
																			   ('14','babywipes',NOW()+INTERVAL 10 DAY,'Drygoods','Supply','Personal hygiene'),
																			   ('15','tshirt',NOW()+INTERVAL 10 DAY,'Drygoods','Supply','Personal hygiene'),
																			   ('16','pants',NOW()+INTERVAL 10 DAY,'Drygoods','Supply','Clothing'),
                                                                               ('17','shirts',NOW()+INTERVAL 10 DAY,'Drygoods','Supply','Clothing'),
																			   ('18','jeans',NOW()+INTERVAL 10 DAY,'Drygoods','Supply','Clothing'),
																			   ('19','underwear',NOW()+INTERVAL 10 DAY,'Drygoods','Supply','Clothing'),
																			   ('20','coats',NOW()+INTERVAL 10 DAY,'Drygoods','Supply','Clothing'),
																			  ('21','beets',NOW()+INTERVAL 10 DAY,'Refrigerated','Food','Vegetables'),
                                                                               ('22','carrots',NOW()+INTERVAL 10 DAY,'Refrigerated','Food','Vegetables'),
																			   ('23','rice',NOW()+INTERVAL 10 DAY,'Drygoods','Food','Nuts/grains/beans'),
																			   ('24','oats',NOW()+INTERVAL 10 DAY,'Drygoods','Food','Nuts/grains/beans'),
																			   ('25','A1 sauce',NOW()+INTERVAL 10 DAY,'Drygoods','Food','Sauce/condiments'),
																			   ('26','catchups',NOW()+INTERVAL 10 DAY,'Drygoods','Food','Sauce/condiments'),
                                                                               ('27','orange juice',NOW()+INTERVAL 10 DAY,'Refrigerated','Food','Juice/drink'),
																			   ('28','apple juice',NOW()+INTERVAL 10 DAY,'Refrigerated','Food','Juice/drink'),
																			   ('29','shrimp',NOW()+INTERVAL 10 DAY,'Frozen','Food','Meat/seafood'),
																			   ('30','salmon',NOW()+INTERVAL 10 DAY,'Frozen','Food','Meat/seafood'),
																			   ('31','eggs',NOW()+INTERVAL 10 DAY,'Refrigerated','Food','Dairy/eggs'),
                                                                               ('32','egg white',NOW()+INTERVAL 10 DAY,'Refrigerated','Food','Dairy/eggs'),
																			   ('33','tent',NOW()+INTERVAL 10 DAY,'Drygoods','Supply','Shelter'),
																			   ('34','sleeping bags',NOW()+INTERVAL 10 DAY,'Drygoods','Supply','Shelter'),
																			   ('35','blankets',NOW()+INTERVAL 10 DAY,'Drygoods','Supply','Shelter'),
																			   ('36','umbrella',NOW()+INTERVAL 10 DAY,'Drygoods','Supply','Shelter'),
                                                                               ('37','rain coats',NOW()+INTERVAL 10 DAY,'Drygoods','Supply','Shelter'),
																			   ('38','toilet paper',NOW()+INTERVAL 10 DAY,'Drygoods','Supply','Other'),
																			   ('39','pet food',NOW()+INTERVAL 10 DAY,'Drygoods','Supply','Other'),
																			   ('40','batteries',NOW()+INTERVAL 10 DAY,'Drygoods','Supply','Other'),
                                                                               ('41','pencils',NOW()+INTERVAL 10 DAY,'Drygoods','Supply','Other'),
																			   ('42','Note pads',NOW()+INTERVAL 10 DAY,'Drygoods','Supply','Other'),
																			   ('43','chicken breats',NOW()-INTERVAL 10 DAY,'Frozen','Food','Meat/seafood'),
																			   ('44','chicken legs',NOW()-INTERVAL 10 DAY,'Frozen','Food','Meat/seafood'),
																			   ('45','whole milk',NOW()-INTERVAL 10 DAY,'Refrigerated','Food','Dairy/eggs'),
																			   ('46','non-fat milk',NOW()-INTERVAL 10 DAY,'Refrigerated','Food','Dairy/eggs');
																			   
-- Inserting FoodBankToItem --
INSERT INTO FoodBankToItem (FacilityId,ItemId,availableQuantity)
VALUES ('11','1','10'),
       ('11','2','10'),
	   ('11','3','10'),
	   ('11','4','10'),
	   ('11','5','10'),
	   ('11','6','10'),
	   ('11','7','10'),
	   ('11','8','10'),
	   ('11','9','10'),
	   ('11','10','10'),
	   ('11','11','10'),
	   ('11','12','10'),
	   ('11','13','10'),
	   ('11','14','10'),
	   ('11','15','10'),
	   ('11','16','10'),
	   ('11','17','10'),
	   ('11','18','10'),
	   ('11','19','10'),
	   ('11','20','10'),
	   ('12','21','20'),
       ('12','22','20'),
	   ('12','23','20'),
	   ('12','24','20'),
	   ('12','25','20'),
	   ('12','26','20'),
	   ('12','27','20'),
	   ('12','28','20'),
	   ('12','29','20'),
	   ('12','30','20'),
	   ('12','31','20'),
	   ('12','32','20'),
	   ('12','33','20'),
	   ('12','34','20'),
	   ('12','35','20'),
	   ('12','36','20'),
	   ('12','37','20'),
	   ('12','38','20'),
	   ('12','39','20'),
	   ('12','40','20'),
	   ('12','41','10'),
	   ('12','42','10'),
	   ('13','43','10'),
	   ('13','44','10'),
	   ('13','45','10'),
	   ('13','46','10');
	   
-- Inserting Request--	 
insert into Request (RequestID,Username,FacilityId,ItemId,Status,QuantityRequested,QuantityFulfilled)
values
(1,'emp1',12,21,'pending',20,0),
(2,'emp1',12,22,'pending',20,0),
(3,'emp1',12,23,'pending',30,0),
(4,'emp1',13,43,'pending',20,0),
(5,'emp1',13,44,'pending',30,0),
(6,'emp2',11,1,'pending',30,0),
(7,'emp2',11,2,'pending',30,0),																			   
(8,'emp2',11,3,'pending',30,0),
(9,'emp2',11,4,'pending',30,0),
(10,'emp2',13,45,'pending',20,0),
(11,'emp2',13,46,'pending',30,0),
(12,'emp2',11,11,'pending',30,0),
(13,'emp2',11,12,'pending',30,0),																			   
(14,'emp2',11,13,'pending',30,0),
(15,'emp3',11,5,'pending',30,0),
(16,'emp3',11,6,'pending',30,0),																			   
(17,'emp3',11,7,'pending',30,0),
(18,'emp3',11,8,'pending',30,0),
(19,'emp3',12,24,'pending',20,0),
(20,'emp3',12,25,'pending',30,0),
(21,'emp3',11,11,'pending',30,0),
(22,'emp3',11,12,'pending',30,0),																			   
(23,'emp3',11,13,'pending',30,0),
(24,'emp3',12,33,'pending',30,0),
(25,'emp3',12,34,'pending',30,0),																			   
(26,'emp3',12,35,'pending',30,0),
(27,'emp3',11,11,'pending',30,0),
(28,'emp3',11,12,'pending',30,0),																			   
(29,'emp3',11,13,'pending',30,0),
(30,'emp3',11,11,'pending',30,0),
(31,'emp1',12,21,'closed',30,30),																			   
(32,'emp1',12,22,'closed',30,30),
(33,'emp2',11,1,'closed',20,20),																			   
(34,'emp2',11,2,'closed',20,20),
(35,'emp3',11,5,'closed',10,10),
(36,'emp3',11,6,'closed',10,10),
(37,'vol1',12,21,'pending',20,0),
(38,'vol1',12,22,'pending',20,0),
(39,'vol1',12,23,'pending',30,0),
(40,'vol1',13,43,'pending',20,0),
(41,'vol1',13,44,'pending',30,0),
(42,'vol2',11,1,'pending',30,0),
(43,'vol2',11,2,'pending',30,0),																			   
(44,'vol2',11,3,'pending',30,0),
(45,'vol2',11,4,'pending',30,0),
(46,'vol2',13,45,'pending',20,0),
(47,'vol2',13,46,'pending',30,0),
(48,'vol2',11,11,'pending',30,0),
(49,'vol2',11,12,'pending',30,0),																			   
(50,'vol2',11,13,'pending',30,0),
(51,'vol3',11,5,'pending',30,0),
(52,'vol3',11,6,'pending',30,0),																			   
(53,'vol3',11,7,'pending',30,0),
(54,'vol3',11,8,'pending',30,0),
(55,'vol3',12,24,'pending',20,0),
(56,'vol3',12,25,'pending',30,0),
(57,'vol3',11,11,'pending',30,0),
(58,'vol3',11,12,'pending',30,0),																			   
(59,'vol3',11,13,'pending',30,0),
(60,'vol3',12,33,'pending',30,0),
(61,'vol3',12,34,'pending',30,0),																			   
(62,'vol3',12,35,'pending',30,0),
(63,'vol3',11,11,'pending',30,0),
(64,'vol3',11,12,'pending',30,0),																			   
(65,'vol3',11,13,'pending',30,0),
(66,'vol3',11,11,'pending',30,0),
(67,'vol1',12,21,'closed',30,30),																			   
(68,'vol1',12,22,'closed',30,30),
(69,'vol2',11,1,'closed',20,20),																			   
(70,'vol2',11,2,'closed',20,20),
(71,'vol3',11,5,'closed',10,10),
(72,'vol3',11,6,'closed',10,10);
