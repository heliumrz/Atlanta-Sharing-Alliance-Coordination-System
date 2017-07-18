-- user

delete from Site;

insert into Site (siteId,shortName,streetAddress,city,state,zipCode,phoneNumber)
values
(1,'OC People Service','123 Street','Irvine','CA','92618','9497894566');

delete from User;

insert into user (username,password,firstname,lastname,email,usertype,siteId)
values
('test1','test1','Test1','User','test1@gmail.com','Employee',1),
('test2','test2','Test2','User','test2@gmail.com','Employee',1);

delete from Client;

insert into Client (clientId,firstName,lastName,description,phoneNumber)
values
(1,'John','Smith','Georgia Drivers License: 9813432','9491112222'),
(2,'Jane','Doe','FL Birth Certificate: 312432','9493334442'),
(3,'Jane','Dof','FL Birth Certificate: 312433','9493334443'),
(4,'Jane','Dog','FL Birth Certificate: 312434','9493334444'),
(5,'Jane','Doh','FL Birth Certificate: 312435','9493334445'),
(6,'Jane','Doi','FL Birth Certificate: 312436','9493334446'),
(7,'John','Doe','CA Birth Certificate: 123456','6511112222'),
(8,'John','Dof','CA Birth Certificate: 234567','6122223333'),
(9,'John','Dog','CA Birth Certificate: 345678','6123334444'),
(10,'John','Doh','CA Birth Certificate: 456789','7634445555');

delete from Service;

insert into Service (facilityId)
values
(1);

delete from ClientService;

insert into ClientService (facilityId,facilityName,eligibilityCondition,hoursOfOperation)
values
(1,'OC Soup Kitchen','Clothing required.','Everyday 6AM to 10PM');

delete from SoupKitchen;

insert into SoupKitchen (facilityId,seatCount)
values
(1,50);

delete from SiteToService;

insert into SiteToService (siteId,facilityId)
values
(1,1);

delete from ClientLog;

insert into ClientLog (clientId,username,modifiedDateTime,fieldModified,previousValue)
values
(1,'test1',NOW(),'FIRSTNAME','Johnathan'),
(2,'test1',NOW(),'FIRSTNAME','Jana');

delete from ClientServiceUsage;

insert into ClientServiceUsage (clientId,siteId,facilityId,username,serviceDateTime,description,note)
values
(1,1,1,'test1',NOW(),'Soup Kitchen',NULL),
(2,1,1,'test1',NOW(),'Soup Kitchen',NULL);

delete from FoodBank;
insert into FoodBank (facilityId)
values
(1);

delete from Item;
insert into Item (itemId,name,storageType,expirationDate,itemType)
values
(1,'potato','Dry Good','2018-01-01','Food'),
(2,'cheese','Dry Good','2017-10-01','Food');

delete from FoodBankToItem;
insert into FoodBankToItem (facilityId,itemId,availableQuantity)
values
(1,1,100),
(1,2,50);

delete from Request;
insert into Request (requestID,username,facilityId,itemId,status,quantityRequested,quantityFulfilled)
values
(1,'test1',1,1,'pending',10,0),
(2,'test1',1,1,'pending',10,0),
(3,'test2',1,2,'pending',1000,0);

