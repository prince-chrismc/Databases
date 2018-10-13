DROP TABLE TRANSACTION_TASK;
DROP TABLE TRANSACTION_SALARY;
DROP TABLE TRANSACTION_MATERIAL;
DROP TABLE MATERIAL_SUPPLIER;
DROP TABLE PHASE_MATERIAL;
DROP TABLE TEAM_EMPLOYEE;
DROP TABLE PROJECT_TEAM;
DROP TABLE SUPPLIER;
DROP TABLE MATERIAL;
DROP TABLE TASK;
DROP TABLE PHASE;
DROP TABLE PROJECT;
DROP TABLE TEAM;
DROP TABLE EMPLOYEE;
DROP TABLE CUSTOMER;


CREATE TABLE CUSTOMER(
	custID int unsigned not null unique auto_increment,
	custName varchar(255) not null,
	custPwd varchar(255) not null, 
	custAddress varchar(255),
	custPhoneNum varchar(255),
	primary key (custID)
);

CREATE TABLE EMPLOYEE(
	empID int unsigned not null unique auto_increment,
	empName varchar(255) not null,
	empPwd varchar(255) not null,
	empAddress varchar(255),
	empPhoneNum varchar(255),
	primary key(empID)
);

CREATE TABLE TEAM(
	teamID int unsigned not null unique auto_increment,
	teamName varchar(255) not null,
	primary key (teamID)
);

CREATE TABLE TEAM_EMPLOYEE(
	empID int unsigned not null,
	teamID int unsigned not null,
	salary int unsigned not null,
	primary key (empID, teamID),
	foreign key (empID) references EMPLOYEE (empID),
	foreign key (teamID) references TEAM (teamID)
);

CREATE TABLE PROJECT(
    projID int unsigned not null unique auto_increment,
    custID int unsigned not null,
    projName varchar(255) not null,
    projDetails varchar(255),
    budgetAmount float(12,2) unsigned,
    estimatedCost float(12,2) unsigned,
    projDateStart date,
    projDateEnd date,
    primary key (projID),
    foreign key (custID) references CUSTOMER (custID)
);

ALTER TABLE PROJECT ALTER budgetAmount SET DEFAULT 0;

CREATE TABLE PROJECT_TEAM(
	projID int unsigned not null,
	teamID int unsigned not null,
	primary key (projID, teamID),
	foreign key (projID) references PROJECT (projID),
	foreign key (teamID) references TEAM (teamID)
);

CREATE TABLE PHASE(
	phaseID int unsigned not null unique auto_increment,
	projID int unsigned not null,
	phaseName varchar(255) not null,
	phaseDetails varchar(255),
	phaseDateStart date,
	phaseDateEnd date,
	primary key (phaseID),
	foreign key (projID) references PROJECT (projID)
);

CREATE TABLE TASK(
	taskID int unsigned not null unique auto_increment,
	phaseID int unsigned not null,
	taskDetails varchar(255) not null,
	taskCost float(12,2) unsigned,
	taskEstimateHours int unsigned not null,
	taskDateStart date,
	taskDateEnd date,
	primary key (taskID),
	foreign key (phaseID) references PHASE(phaseID)
);

CREATE TABLE MATERIAL(
	matID int unsigned not null unique auto_increment,
	matName varchar(255) not null,
	primary key (matID)
);

CREATE TABLE PHASE_MATERIAL(
	phaseID int unsigned not null,
	matID int unsigned not null,
	qty int unsigned not null,
	primary key (phaseID, matID),
	foreign key (phaseID) references PHASE (phaseID),
	foreign key (matID) references MATERIAL (matID)
);

ALTER TABLE PHASE_MATERIAL ALTER qty SET DEFAULT 1;

CREATE TABLE SUPPLIER(
	supID int unsigned not null unique auto_increment,
	supName varchar(255) not null,
	supAddress varchar(255),
	supPhoneNum varchar(255),
	primary key (supID)
);

CREATE TABLE MATERIAL_SUPPLIER(
	matID int unsigned not null,
	supID int unsigned not null,
	matCost float(12,2) unsigned,
	deliveryTime int unsigned,
	primary key (matID, supID),
	foreign key (matID) references MATERIAL (matID),
	foreign key (supID) references SUPPLIER (supID)
);

ALTER TABLE MATERIAL_SUPPLIER ALTER matCost SET DEFAULT 0;

CREATE TABLE TRANSACTION_TASK(
    transID int unsigned not null unique auto_increment,
	projID int unsigned not null,
    taskID int unsigned not null,
    transCost float(12,2) unsigned not null,
    transDate date not null,
    primary key (transID),
	foreign key (projID) references PROJECT (projID),
    foreign key (taskID) references TASK (taskID)
);


CREATE TABLE TRANSACTION_SALARY(
    transID int unsigned not null unique auto_increment,
	projID int unsigned not null,
    empID int unsigned not null,
    teamID int unsigned not null,
    transCost float(12,2) unsigned not null,
    transDate date not null,
    primary key (transID),
	foreign key (projID) references PROJECT (projID),
    foreign key (empID) references EMPLOYEE (empID),
    foreign key (teamID) references TEAM (teamID)
);

CREATE TABLE TRANSACTION_MATERIAL(
    transID int unsigned not null unique auto_increment,
	projID int unsigned not null,
    matID int unsigned not null,
    supID int unsigned not null,
    transCost float(12,2) unsigned not null,
    transDate date not null,
    primary key (transID),
	foreign key (projID) references PROJECT (projID),
    foreign key (matID) references MATERIAL (matID),
    foreign key (supID) references SUPPLIER (supID)
);


delimiter $$
create trigger trans_task_insert_trigger before insert on TRANSACTION_TASK
for each row
begin
declare currBudget float(12,2);
set currBudget = (select budgetAmount from PROJECT where projID = new.projID);
if ( currBudget - new.transCost ) < 0 then
 signal sqlstate '45000'
  set message_text = 'Task Transaction Exception! Not Enough Funds in the Project\'s Remaining Budget';
else
 update PROJECT set budgetAmount = (currBudget - new.transCost) where PROJECT.projID=new.projID;
end if;
end;$$


create trigger trans_material_insert_trigger before insert on TRANSACTION_MATERIAL
for each row
begin
declare currBudget float(12,2);
set currBudget = (select budgetAmount from PROJECT where projID = new.projID);
if ( currBudget - new.transCost ) < 0 then
 signal sqlstate '45000'
  set message_text = 'Material Transaction Exception! Not Enough Funds in the Project\'s Remaining Budget';
else
 update PROJECT set budgetAmount = (currBudget - new.transCost) where PROJECT.projID=new.projID;
end if;
end;$$


create trigger trans_salary_insert_trigger before insert on TRANSACTION_SALARY
for each row
begin
declare currBudget float(12,2);
set currBudget = (select budgetAmount from PROJECT where projID = new.projID);
if ( currBudget - new.transCost ) < 0 then
 signal sqlstate '45000'
  set message_text = 'Salary Transaction Exception! Material Exception Exception! Not Enough Funds in the Project\'s Remaining Budget';
else
 update PROJECT set budgetAmount = (currBudget - new.transCost) where PROJECT.projID=new.projID;
end if;
end;$$


create trigger trans_task_update_trigger before update on TRANSACTION_TASK
for each row
begin
 signal sqlstate '45000'
  set message_text = 'Task Transaction Exception! Transactions cannot be updated...';
end;$$


create trigger trans_material_update_trigger before update on TRANSACTION_MATERIAL
for each row
begin
 signal sqlstate '45000'
  set message_text = 'Material Transaction Exception! Transactions cannot be updated...';
end;$$


create trigger trans_salary_update_trigger before update on TRANSACTION_SALARY
for each row
begin
 signal sqlstate '45000'
  set message_text = 'Salary Transaction Exception! Transactions cannot be updated...';
end;$$


create trigger cust_insert_trigger before insert on CUSTOMER
for each row
begin
 declare pwdLength int;
 set pwdLength = (select length(new.custPwd));
 if (pwdLength < 8) then
  signal sqlstate '45000'
	set message_text= 'Customer Password Exception! Password length must be at least 8 characters long...';
end if;
end;$$


create trigger cust_update_trigger before update on CUSTOMER
for each row
begin
 declare pwdLength int;
 set pwdLength = (select length(new.custPwd));
 if (pwdLength < 8) then
  signal sqlstate '45000'
	set message_text= 'Customer Password Exception! Password length must be at least 8 characters long...';
end if;
end;$$


create trigger emp_insert_trigger before insert on EMPLOYEE
for each row
begin
 declare pwdLength int;
 set pwdLength = (select length(new.empPwd));
 if (pwdLength < 8) then
  signal sqlstate '45000'
	set message_text= 'Employee Password Exception! Password length must be at least 8 characters long...';
end if;
end;$$


create trigger emp_update_trigger before update on EMPLOYEE
for each row
begin
 declare pwdLength int;
 set pwdLength = (select length(new.empPwd));
 if (pwdLength < 8) then
  signal sqlstate '45000'
	set message_text= 'Employee Password Exception! Password length must be at least 8 characters long...';
end if;
end;$$


create trigger phase_mat_insert_trigger before insert on PHASE_MATERIAL
for each row
begin
 if (new.qty < 1) then
  signal sqlstate '45000'
	set message_text= 'Phase Material Exception! Quantity must be at least 1';
end if;
end;$$


create trigger phase_mat_update_trigger before update on PHASE_MATERIAL
for each row
begin
 if (new.qty < 1) then
  signal sqlstate '45000'
	set message_text= 'Phase Material Exception! Quantity must be at least 1';
end if;
end;$$


create trigger project_insert_trigger before insert on PROJECT 
for each row
begin
 if (new.projDateEnd is not null) then
  if(new.projDateStart is null) then
   signal sqlstate '45000'
    set message_text = 'Project Exception! Cannot set an end date without a start date...';
  elseif (new.projDateStart > new.projDateEnd) then
   signal sqlstate '45000'
    set message_text = 'Project Exception! Start date is bigger than end date...';
  end if;
 end if;
end;$$


create trigger project_update_trigger before update on PROJECT 
for each row
begin
 if (new.projDateEnd is not null) then
  if(new.projDateStart is null) then
   signal sqlstate '45000'
    set message_text = 'Project Exception! Cannot set an end date without a start date...';
  elseif (new.projDateStart > new.projDateEnd) then
   signal sqlstate '45000'
    set message_text = 'Project Exception! Start date is bigger than end date...';
  end if;
 end if;
end;$$


create trigger task_insert_trigger before insert on TASK 
for each row
begin
 if (new.taskDateEnd is not null) then
  if(new.taskDateStart is null) then
   signal sqlstate '45000'
    set message_text = 'Task Exception! Cannot set an end date without a start date...';
  elseif (new.taskDateStart > new.taskDateEnd) then
   signal sqlstate '45000'
    set message_text = 'Task Exception! Start date is bigger than end date...';
  end if;
 end if;
end;$$


create trigger task_update_trigger before update on TASK 
for each row
begin
 if (new.taskDateEnd is not null) then
  if(new.taskDateStart is null) then
   signal sqlstate '45000'
    set message_text = 'Task Exception! Cannot set an end date without a start date...';
  elseif (new.taskDateStart > new.taskDateEnd) then
   signal sqlstate '45000'
    set message_text = 'Task Exception! Start date is bigger than end date...';
  end if;
 end if;
end;$$


create trigger phase_insert_trigger before insert on PHASE 
for each row
begin
declare numPhasesInProgress int;
 if (new.phaseDateEnd is not null) then
  if(new.phaseDateStart is null) then
   signal sqlstate '45000'
    set message_text = 'Phase Exception! Cannot set an end date without a start date...';
  elseif (new.phaseDateStart > new.phaseDateEnd) then
   signal sqlstate '45000'
    set message_text = 'Phase Exception! Start date is bigger than end date...';
  end if;
 else
  if(new.phaseDateStart is not null) then
   set numPhasesInProgress = (select count(*) from PHASE where phaseDateStart is not null and phaseDateEnd is null and projID=new.projID);
   if(numPhasesInProgress > 0) then
	signal sqlstate '45000'
     set message_text = 'Phase Exception! There can be only one phase in progress at a time...';
   end if;
  end if;
 end if;
end;$$


create trigger phase_update_trigger before update on PHASE 
for each row
begin
declare numPhasesInProgress int;
 if (new.phaseDateEnd is not null) then
  if(new.phaseDateStart is null) then
   signal sqlstate '45000'
    set message_text = 'Phase Exception! Cannot set an end date without a start date...';
  elseif (new.phaseDateStart > new.phaseDateEnd) then
   signal sqlstate '45000'
    set message_text = 'Phase Exception! Start date is bigger than end date...';
  end if;
 else
  if(new.phaseDateStart is not null) then
  
   set numPhasesInProgress = (select count(*) from PHASE where phaseDateStart is not null and phaseDateEnd is null and projID=new.projID);
   if(numPhasesInProgress > 0) then
	signal sqlstate '45000'
     set message_text = 'Phase Exception! There can be only one phase in progress at a time...';
   end if;
  end if;
 end if;
end;$$


INSERT INTO wsc353_4.CUSTOMER VALUES
('1', 'Ben Smith', 'password', '7254 Windfall St. Washington, PA 15301', '(157) 380-2510'),
('2', 'Mae Reeves', 'password', '75 West Tanglewood St. Valley Stream, NY 11580', '(545) 758-7399'),
('3', 'Horace Ortega', 'password', '633 Valley Farms Street Logansport, IN 46947', '(165) 894-4585'),
('4', 'Yvette Howell', 'password', '20 N. Glen Ridge Ave. Goose Creek, SC 29445', '(233) 897-2283'),
('5', 'Laverne Gibson', 'password', '5 School Avenue Ashtabula, OH 44004', '(480) 884-3380'),
('6', 'Lucas Boone', 'password', '71 East Clinton Rd. Berwyn, IL 60402', '(225) 590-3084'),
('7', 'Meghan Copeland', 'password', '8338 Hall Ave. Beckley, WV 25801', '(764) 462-6117'),
('8', 'Billie Drake', 'password', '442 Hartford Lane Royersford, PA 19468', '(724) 156-7794'),
('9', 'Marion Paul', 'password', '150 River Ave. Rockville Centre, NY 11570', '(754) 478-1137'),
('10', 'Tracy Hart', 'password', '321 Windsor Avenue Mount Holly, NJ 08060', '(714) 354-6914'),
('11', 'Freda	Frank', 'password', '97 Glen Eagles Court Arlington Heights, IL 60004', '(794) 224-4793'),
('12', 'Mildred Webster', 'password', '317 Sleepy Hollow Lane Sarasota, FL 34231', '(464) 332-4633'),
('13', 'Lyle Jennings', 'password', '7384 Race Court Shelbyville, TN 37160', '(364) 998-7897'),
('14', 'Gwendolyn	Baker', 'password', '7332 Greenrose Street Wakefield, MA 01880', '(164) 462-1467'),
('15', 'Clark	Figueroa', 'password', '545 Bay St. Apt 13 Cherry Hill, NJ 08003', '(642) 348-6997'),
('16', 'Myra Cole', 'password', '9448 Smith Store Lane Sugar Land, TX 77478', '(456) 466-9988'),
('17', 'Donnie Santiago', 'password', '983 N. Vine St. Schenectady, NY 12302', '(321) 514-4557'),
('18', 'Ginger Hines', 'password', '77 Willow Street Cedar Rapids, IA 52402', '(187) 462-4579'),
('19', 'Lonnie Christensen', 'password', '49 Plumb Branch Drive Boston, MA 02127', '(864) 123-6217');$$

INSERT INTO wsc353_4.SUPPLIER(supID, supName, supAddress, supPhoneNum) VALUES
('1', 'Alpha Supplies', '63 Helen Street Miami Gardens, FL 33056', '(105) 431-6373'),
('2', 'Beta Supplies', '115 Woodside Court Bethlehem, PA 18015', '(205) 456-6373'),
('3', 'Dreko Supplies', '6 E. Arlington Dr. Gwynn Oak, MD 21207', '(278) 897-1483'),
('4', 'Supplies Rhino', '25 Marlborough St. Apt 2 Howard Beach, NY 11414', '(496) 932-3180'),
('5', 'Amp Supplies', '74 West Parker Rd. Latrobe, PA 15650', '(223) 590-4678'),
('6', 'Copeland Supplies', '6 Bay Meadows Dr. Irmo, SC 29063', '(454) 490-6427'),
('7', 'Bill\'s Hardware', '339 Bridle St. Zeeland, MI 49464', '(114) 126-9901'),
('8', 'Tom\'s Hardware', '317 Glen Ridge Dr. Barberton, OH 44203', '(344) 568-0937'),
('9', 'Vine Supplies', '268 Brook Court Southgate, MI 48195', '(909) 294-6714'),
('10', 'Freda	Frank', '305 E. Corona Drive Mount Laurel, NJ 08054', '(808) 422-5001'),
('11', 'Webster Construction', '8292 Yukon Drive Rockville Centre, NY 11570', '(514) 728-4633'),
('12', 'Echo Materials', '561 Bow Ridge Dr. Niagara Falls, NY 14304', '(450) 492-7897'),
('13', 'Supreme Materials', '67 Bishop Street Manitowoc, WI 54220', '(879) 322-4456'),
('14', 'Reboot Materials', '51 Snake Hill Ave. Oceanside, NY 11572', '(563) 339-1697');$$


INSERT INTO wsc353_4.MATERIAL(matID, matName) VALUES
('1', 'Interior Door'),
('2', 'Gutter'),
('3', 'Garage Door'),
('4', 'Exterior Door'),
('5', '2\'0\" x 2\'0\" Window'),
('6', '3\'0\" x 3\'0\" Window'),
('7', '4\'0\" x 4\'0\" Window'),
('8', 'Wood Floor'),
('9', 'Ceramic'),
('10', 'Paint'),
('11', 'Wall Paper'),
('12', 'Fireplace'),
('13', 'Sink'),
('14', 'Bath tub'),
('15', 'Closet Door'),
('16', 'Ceiling Fan'),
('17', 'Drywall'),
('18', '2\" x 4\" board'),
('19', 'Light Switches'),
('20', 'Roof Shingles'),
('21', 'Counter Top'),
('22', 'Lumber'),
('23', 'Concrete'),
('24', 'Nails');$$

INSERT INTO wsc353_4.PROJECT(custID, projName, projDetails, budgetAmount, estimatedCost, projDateStart,projDateEnd) VALUES
(1, 'Mr Smith\'s House','Bungalow', 150000,180000,'2016-10-08','2017-03-12'),
(2, 'Mrs Reeves\'s House','Bungalow',150000,160000,'2016-08-08','2016-12-30'),
(3, 'Mr Ortega\'s House','Bungalow',200000,170000,'2016-08-10','2017-01-03'),
(4, 'Mrs Howell\'s House','Bungalow',190000,200000,'2016-07-10','2016-12-03'),
(5, 'Mrs Gibson\'s House','Bungalow',190000,160000,'2016-07-12','2016-12-03'),
(6, 'Mr Boone\'s House','Duplex',100000,180000,'2017-04-10',null),
(7, 'Mrs Copeland\'s House','Duplex',100000,170000,'2017-03-01',null),
(8, 'Mr Drake\'s House','Duplex',120000,175000,'2017-02-01',null),
(9, 'Mrs Paul\'s House','Duplex',200000,180000,'2017-01-01',null),
(10, 'Mr Hart\'s House','Duplex',165000,175000,'2016-12-01','2017-05-04'),
(1, 'Second House','Duplex',150000,190000,'2017-04-10',null),
(1, 'Guest House','Duplex',150000,180000,'2017-03-01',null),
(2, 'Guest House','Duplex',150000,175000,'2017-02-01',null);$$


INSERT INTO wsc353_4.PHASE(projID, phaseName, phaseDetails, phaseDateStart, phaseDateEnd) VALUES
(1,'Pre-Construction','','2016-10-08','2016-11-8'),
(1,'Excavation and Foundation','','2016-11-09','2016-12-9'),
(1,'Framing','','2016-12-10','2017-01-12'),
(1,'Interior and Exterior Work','','2017-01-12','2017-02-01'),
(1,'Finishing','','2017-02-02','2017-03-12'),
(2,'Pre-Construction','','2016-08-08','2016-09-8'),
(2,'Excavation and Foundation','','2016-09-09','2016-10-9'),
(2,'Framing','','2016-10-10','2016-11-12'),
(2,'Interior and Exterior Work','','2016-11-12','2016-12-01'),
(2,'Finishing','','2016-12-02','2016-12-30'),
(3,'Pre-Construction','','2016-08-10','2016-09-12'),
(3,'Excavation and Foundation','','2016-09-12','2016-10-10'),
(3,'Framing','','2016-10-10','2016-11-13'),
(3,'Interior and Exterior Work','','2016-11-13','2016-12-04'),
(3,'Finishing','','2016-12-04','2017-01-03'),
(4,'Pre-Construction','','2016-07-10','2016-08-12'),
(4,'Excavation and Foundation','','2016-08-12','2016-09-10'),
(4,'Framing','','2016-09-10','2016-10-13'),
(4,'Interior and Exterior Work','','2016-10-13','2016-11-04'),
(4,'Finishing','','2016-11-04','2016-12-03'),
(5,'Pre-Construction','','2016-07-12','2016-08-13'),
(5,'Excavation and Foundation','','2016-08-13','2016-09-16'),
(5,'Framing','','2016-09-16','2016-10-13'),
(5,'Interior and Exterior Work','','2016-10-13','2016-11-04'),
(5,'Finishing','','2016-11-04','2016-12-03'),
(6,'Pre-Construction','','2017-04-10',null),
(7,'Pre-Construction','','2017-03-01','2017-04-01'),
(7,'Excavation and Foundation','','2017-04-02',null),
(8,'Pre-Construction','','2017-02-01','2017-03-01'),
(8,'Excavation and Foundation','','2017-03-02','2017-04-03'),
(8,'Framing','','2017-04-03',null),
(9,'Pre-Construction','','2017-01-01','2017-02-01'),
(9,'Excavation and Foundation','','2017-02-02','2017-03-03'),
(9,'Framing','','2017-03-03','2017-04-04'),
(9,'Interior and Exterior Work','','2017-04-03',null),
(10,'Pre-Construction','','2016-12-01','2017-01-01'),
(10,'Excavation and Foundation','','2017-01-02','2017-02-03'),
(10,'Framing','','2017-02-03','2017-03-04'),
(10,'Interior and Exterior Work','','2017-03-04','2017-04-04'),
(10,'Finishing','','2017-04-05','2017-05-04'),
(11,'Pre-Construction','','2017-04-10',null),
(12,'Pre-Construction','','2017-03-01','2017-04-01'),
(12,'Excavation and Foundation','','2017-04-02',null),
(13,'Pre-Construction','','2017-02-01','2017-03-01'),
(13,'Excavation and Foundation','','2017-03-02','2017-04-03'),
(13,'Framing','','2017-04-03',null);$$


INSERT INTO wsc353_4.TASK(phaseID, taskDetails, taskCost, taskEstimateHours, taskDateStart, taskDateEnd) VALUES
(1,'Construction permit',2500,300,'2016-10-08','2016-11-01'),
(1,'Demolition permit',1500,300,'2016-10-08','2016-11-01'),
(1,'Architecture plans',850,10,'2016-10-08','2016-10-10'),
(1,'Engineering plans',1000,10,'2016-10-08','2016-10-10'),

(2,'Excavation',5000,24,'2016-11-09','2016-11-13'),
(2,'Slab Construction',1000,24,'2016-11-13','2016-11-17'),

(3,'Floor',5000,120,'2016-12-10','2017-01-12'),
(3,'Stairs',3900,40,'2016-12-12','2017-01-11'),
(3,'Walls',5000,120,'2016-12-10','2017-01-12'),
(3,'Roof',5000,120,'2016-12-13','2017-01-12'),

(4,'Insulation',1500,16,'2017-01-13','2017-01-14'),
(4,'Installing Drywall',3000,24,'2017-01-13','2017-01-18'),
(4,'Painting',1500,24,'2017-01-18','2017-01-19'),
(4,'Installing cabinets',1000,16,'2017-01-18','2017-01-21'),
(4,'Brick laying',2000,24,'2017-01-18','2017-01-25'),
(4,'Plumbing',5000,40,'2017-01-18','2017-01-29'),
(4,'Electrical',5000,40,'2017-01-18','2017-01-29'),
(4,'HVAC',5000,40,'2017-01-18','2017-01-30'),
(4,'Roofing',3000,32,'2017-01-18','2017-01-24'),
(4,'Windows',4000,32,'2017-01-24','2017-02-01'),
(4,'Installing garage',3000,10,'2017-01-30','2017-02-01'),

(5,'Tiling',2500,24,'2017-02-03','2017-02-05'),
(5,'Installing Counter tops',3000,24,'2017-02-05','2017-02-08'),
(5,'Installing bathroom apppliances',3000,16,'2017-02-05','2017-02-07'),
(5,'Installing kitchen apppliances',3000,16,'2017-02-09','2017-02-12'),
(5,'Installing interior doors',1500,8,'2017-02-13','2017-02-14'),
(5,'Installing light fixtures',3000,16,'2017-02-15','2017-02-17'),


(6,'Construction permit',3500,300,'2016-08-08','2016-09-08'),
(6,'Demolition permit',1900,300,'2016-08-08','2016-09-08'),
(6,'Architecture plans',950,10,'2016-08-08','2016-09-08'),
(6,'Engineering plans',1500,10,'2016-08-08','2016-09-08'),

(7,'Excavation',5500,24,'2016-09-09','2016-11-13'),
(7,'Slab Construction',1200,24,'2016-09-15','2016-09-19'),

(8,'Floor',4500,120,'2016-10-11','2016-10-19'),
(8,'Stairs',3900,40,'2016-10-12','2016-10-20'),
(8,'Walls',4800,120,'2016-10-19','2016-10-25'),
(8,'Roof',5000,120,'2016-10-25','2016-11-01'),

(9,'Insulation',1800,16,'2016-11-12','2016-11-16'),
(9,'Installing Drywall',3500,24,'2016-11-16','2016-11-19'),
(9,'Painting',1800,24,'2016-11-19','2016-11-24'),
(9,'Installing cabinets',1500,16,'2016-11-19','2016-11-21'),
(9,'Brick laying',2500,24,'2016-11-19','2016-11-25'),
(9,'Plumbing',4800,40,'2016-11-20','2016-11-29'),
(9,'Electrical',5300,40,'2016-11-20','2016-11-29'),
(9,'HVAC',4700,40,'2016-11-20','2016-11-29'),
(9,'Roofing',3500,32,'2016-11-29','2016-12-01'),
(9,'Windows',3800,32,'2016-11-28','2016-12-01'),
(9,'Installing garage',2800,10,'2016-11-30','2016-12-01'),

(10,'Tiling',2800,24,'2016-12-03','2016-12-06'),
(10,'Installing Counter tops',3200,24,'2016-12-03','2016-12-06'),
(10,'Installing bathroom apppliances',2700,16,'2016-12-06','2016-12-09'),
(10,'Installing kitchen apppliances',2700,16,'2016-12-09','2016-12-12'),
(10,'Installing interior doors',1600,8,'2016-12-12','2016-12-13'),
(10,'Installing light fixtures',3200,16,'2016-12-13','2016-12-15'),

(11,'Construction permit',4000,300,'2016-08-10','2016-08-17'),
(11,'Demolition permit',2000,300,'2016-08-10','2016-08-17'),
(11,'Architecture plans',1050,24,'2016-08-14','2016-08-21'),
(11,'Engineering plans',1800,24,'2016-09-01','2016-09-12'),

(12,'Excavation',7000,40,'2016-09-12','2016-09-20'),
(12,'Slab Construction',2000,40,'2016-09-29','2016-10-10'),

(13,'Floor',6800,160,'2016-10-10','2016-10-19'),
(13,'Stairs',5400,60,'2016-10-19','2016-10-29'),
(13,'Walls',6500,120,'2016-10-29','2016-11-09'),
(13,'Roof',6000,120,'2016-10-29','2016-11-13'),

(14,'Insulation',3000,30,'2016-11-13','2016-11-20'),
(14,'Installing Drywall',5000,30,'2016-11-20','2016-11-27'),
(14,'Painting',3000,30,'2016-11-27','2016-12-03'),
(14,'Installing cabinets',2000,24,'2016-11-20','2016-11-26'),
(14,'Brick laying',3500,24,'2016-11-20','2016-11-26'),
(14,'Plumbing',5800,40,'2016-11-20','2016-11-29'),
(14,'Electrical',5300,40,'2016-11-20','2016-11-29'),
(14,'HVAC',5300,40,'2016-11-20','2016-11-29'),
(14,'Roofing',4500,40,'2016-11-29','2016-12-01'),
(14,'Windows',4500,32,'2016-11-28','2016-12-01'),
(14,'Installing garage',4000,10,'2016-11-30','2016-12-04'),

(15,'Tiling',3800,34,'2016-12-06','2016-12-12'),
(15,'Installing Counter tops',4600,38,'2016-12-06','2016-12-11'),
(15,'Installing bathroom apppliances',3700,24,'2016-12-06','2016-12-10'),
(15,'Installing kitchen apppliances',3700,24,'2016-12-06','2016-12-10'),
(15,'Installing interior doors',3000,10,'2016-12-25','2016-12-30'),
(15,'Installing light fixtures',4200,16,'2016-12-30','2017-01-03'),

(16,'Construction permit',3800,300,'2016-07-10','2016-07-20'),
(16,'Demolition permit',2500,300,'2016-07-15','2016-07-25'),
(16,'Architecture plans',2050,24,'2016-07-25','2016-08-03'),
(16,'Engineering plans',2800,24,'2016-08-01','2016-08-12'),

(17,'Excavation',6200,35,'2016-08-12','2016-08-20'),
(17,'Slab Construction',3300,45,'2016-08-30','2016-09-10'),

(18,'Floor',6200,160,'2016-09-10','2016-09-19'),
(18,'Stairs',5100,60,'2016-09-10','2016-09-17'),
(18,'Walls',6300,120,'2016-09-17','2016-09-27'),
(18,'Roof',5700,120,'2016-10-01','2016-10-13'),

(19,'Insulation',3100,35,'2016-10-13','2016-10-20'),
(19,'Installing Drywall',4800,32,'2016-10-20','2016-11-27'),
(19,'Painting',3300,33,'2016-10-27','2016-11-05'),
(19,'Installing cabinets',2500,30,'2016-10-27','2016-11-01'),
(19,'Brick laying',4100,30,'2016-10-28','2016-11-01'),
(19,'Plumbing',5600,40,'2016-10-20','2016-10-29'),
(19,'Electrical',5400,40,'2016-10-20','2016-10-29'),
(19,'HVAC',5600,40,'2016-10-13','2016-10-29'),
(19,'Roofing',4400,40,'2016-10-20','2016-10-29'),
(19,'Windows',4500,32,'2016-10-20','2016-10-29'),
(19,'Installing garage',4000,12,'2016-11-01','2016-11-04'),

(20,'Tiling',3800,34,'2016-11-04','2016-11-10'),
(20,'Installing Counter tops',4600,38,'2016-11-04','2016-11-10'),
(20,'Installing bathroom apppliances',3700,24,'2016-11-10','2016-11-16'),
(20,'Installing kitchen apppliances',3700,24,'2016-11-12','2016-11-18'),
(20,'Installing interior doors',3000,10,'2016-11-20','2016-11-25'),
(20,'Installing light fixtures',4200,16,'2016-11-29','2016-12-03'),

(21,'Construction permit',3800,300,'2016-07-15','2016-08-13'),
(21,'Demolition permit',2600,300,'2016-07-15','2016-08-13'),
(21,'Architecture plans',2250,24,'2016-07-12','2016-07-20'),
(21,'Engineering plans',3000,24,'2016-07-12','2016-07-19'),

(22,'Excavation',6200,35,'2016-09-10','2016-09-16'),
(22,'Slab Construction',3360,45,'2016-08-13','2016-08-20'),

(23,'Floor',5400,160,'2016-09-16','2016-09-26'),
(23,'Stairs',5100,50,'2016-09-20','2016-09-27'),
(23,'Walls',6300,110,'2016-09-27','2016-10-07'),
(23,'Roof',5700,110,'2016-10-01','2016-10-13'),

(24,'Insulation',3000,31,'2016-10-13','2016-10-20'),
(24,'Installing Drywall',4100,29,'2016-10-20','2016-11-27'),
(24,'Painting',3150,33,'2016-10-27','2016-11-05'),
(24,'Installing cabinets',2700,33,'2016-10-27','2016-11-01'),
(24,'Brick laying',3800,28,'2016-10-28','2016-11-01'),
(24,'Plumbing',5400,40,'2016-10-20','2016-10-29'),
(24,'Electrical',5100,40,'2016-10-20','2016-10-29'),
(24,'HVAC',5200,45,'2016-10-13','2016-10-23'),
(24,'Roofing',4350,40,'2016-10-20','2016-10-28'),
(24,'Windows',4400,32,'2016-10-20','2016-10-29'),
(24,'Installing garage',4000,12,'2016-11-01','2016-11-04'),

(25,'Tiling',3760,34,'2016-11-10','2016-11-15'),
(25,'Installing Counter tops',4306,38,'2016-11-04','2016-11-10'),
(25,'Installing bathroom apppliances',2900,24,'2016-11-09','2016-11-12'),
(25,'Installing kitchen apppliances',2811,24,'2016-11-16','2016-11-20'),
(25,'Installing interior doors',3000,10,'2016-11-26','2016-11-30'),
(25,'Installing light fixtures',3915,16,'2016-11-30','2016-12-03'),

(26,'Construction permit',3800,300,'2017-04-16','2017-04-26'),
(26,'Demolition permit',2600,300,'2017-04-16','2017-04-26'),
(26,'Architecture plans',2250,24,'2017-04-10','2017-04-15'),
(26,'Engineering plans',3000,24,'2017-05-01','2017-05-08'),

(27,'Construction permit',3800,300,'2017-03-01','2017-03-13'),
(27,'Demolition permit',2600,300,'2017-03-01','2017-03-15'),
(27,'Architecture plans',2250,24,'2017-03-20','2017-03-29'),
(27,'Engineering plans',3000,24,'2017-03-26','2017-04-01'),

(28,'Excavation',6200,35,'2017-04-02','2017-04-10'),
(28,'Slab Construction',3360,45,'2017-04-27','2017-05-03'),

(29,'Construction permit',3800,300,'2017-02-20','2017-03-01'),
(29,'Demolition permit',2600,300,'2017-02-20','2017-03-01'),
(29,'Architecture plans',2250,24,'2017-02-20','2017-02-28'),
(29,'Engineering plans',3000,24,'2017-02-01','2017-02-10'),

(30,'Excavation',6200,35,'2017-03-02','2017-03-14'),
(30,'Slab Construction',3360,45,'2017-03-24','2017-04-03'),

(31,'Floor',5400,160,'2017-04-03','2017-04-10'),
(31,'Stairs',5100,50,'2017-04-10','2017-04-16'),
(31,'Walls',6300,110,'2017-04-11','2017-04-20'),
(31,'Roof',5700,110,'2017-04-25','2017-05-04'),

(32,'Construction permit',3800,300,'2017-01-20','2017-02-01'),
(32,'Demolition permit',2600,300,'2017-01-20','2017-02-01'),
(32,'Architecture plans',2250,24,'2017-01-01','2017-01-10'),
(32,'Engineering plans',3000,24,'2017-01-01','2017-01-09'),

(33,'Excavation',6200,35,'2017-02-02','2017-02-10'),
(32,'Slab Construction',3360,45,'2017-02-25','2017-03-03'),

(34,'Floor',5400,160,'2017-03-03','2017-03-12'),
(34,'Stairs',5100,50,'2017-03-12','2017-03-17'),
(34,'Walls',6300,110,'2017-03-17','2017-03-23'),
(34,'Roof',5700,110,'2017-03-26','2017-04-04'),

(35,'Insulation',3000,31,'2017-04-03','2017-04-10'),
(35,'Installing Drywall',4100,29,'2017-04-10','2017-04-16'),
(35,'Painting',3150,33,'2017-04-16','2017-04-23'),
(35,'Installing cabinets',2700,33,'2017-04-10','2017-04-16'),
(35,'Brick laying',3800,28,'2017-04-17','2017-04-20'),
(35,'Plumbing',5400,40,'2017-04-10','2017-04-20'),
(35,'Electrical',5100,40,'2017-04-10','2017-04-20'),
(35,'HVAC',5200,45,'2017-04-10','2017-04-20'),
(35,'Roofing',4350,40,'2017-04-10','2017-04-20'),
(35,'Windows',4400,32,'2017-04-10','2017-04-16'),
(35,'Installing garage',4000,12,'2017-04-15','2017-04-19'),

(36,'Construction permit',3800,300,'2016-12-20','2017-01-01'),
(36,'Demolition permit',2600,300,'2016-12-20','2017-01-01'),
(36,'Architecture plans',2250,24,'2016-12-01','2016-12-10'),
(36,'Engineering plans',3000,24,'2016-12-10','2016-12-20'),

(37,'Excavation',6200,35,'2017-01-02','2017-01-10'),
(37,'Slab Construction',3360,45,'2017-01-24','2017-02-03'),

(38,'Floor',5450,160,'2017-02-03','2017-02-12'),
(38,'Stairs',5600,50,'2017-02-06','2017-02-12'),
(38,'Walls',6500,110,'2017-02-10','2017-02-19'),
(38,'Roof',5900,110,'2017-02-25','2017-03-04'),

(39,'Insulation',3100,31,'2017-03-04','2017-03-12'),
(39,'Installing Drywall',4100,29,'2017-03-12','2017-03-19'),
(39,'Painting',3450,33,'2017-03-19','2017-03-26'),
(39,'Installing cabinets',2760,33,'2017-03-12','2017-03-19'),
(39,'Brick laying',3850,28,'2017-03-12','2017-03-19'),
(39,'Plumbing',5200,40,'2017-03-19','2017-03-29'),
(39,'Electrical',5000,40,'2017-03-19','2017-03-29'),
(39,'HVAC',5100,45,'2017-03-19','2017-03-29'),
(39,'Roofing',4150,40,'2017-03-26','2017-04-04'),
(39,'Windows',4400,32,'2017-03-29','2017-04-04'),
(39,'Installing garage',4000,12,'2017-03-30','2017-04-02'),

(40,'Tiling',3660,34,'2017-04-05','2017-04-10'),
(40,'Installing Counter tops',4328,38,'2017-04-10','2017-04-14'),
(40,'Installing bathroom apppliances',2870,24,'2017-04-14','2017-04-16'),
(40,'Installing kitchen apppliances',2911,24,'2017-04-16','2017-04-20'),
(40,'Installing interior doors',3100,10,'2017-04-23','2017-04-27'),
(40,'Installing light fixtures',3905,16,'2017-04-30','2017-05-04'),

(41,'Construction permit',3800,300,'2017-04-16','2017-04-26'),
(41,'Demolition permit',2600,300,'2017-04-16','2017-04-26'),
(41,'Architecture plans',2250,24,'2017-04-10','2017-04-15'),
(41,'Engineering plans',3000,24,'2017-05-01','2017-05-08'),

(42,'Construction permit',3800,300,'2017-03-01','2017-03-13'),
(42,'Demolition permit',2600,300,'2017-03-01','2017-03-15'),
(42,'Architecture plans',2250,24,'2017-03-20','2017-03-29'),
(42,'Engineering plans',3000,24,'2017-03-26','2017-04-01'),

(43,'Excavation',6200,35,'2017-04-02','2017-04-10'),
(43,'Slab Construction',3360,45,'2017-04-27','2017-05-03'),

(44,'Construction permit',3800,300,'2017-02-20','2017-03-01'),
(44,'Demolition permit',2600,300,'2017-02-20','2017-03-01'),
(44,'Architecture plans',2250,24,'2017-02-20','2017-02-28'),
(44,'Engineering plans',3000,24,'2017-02-01','2017-02-10'),

(45,'Excavation',6200,35,'2017-03-02','2017-03-14'),
(45,'Slab Construction',3360,45,'2017-03-24','2017-04-03'),

(46,'Floor',5400,160,'2017-04-03','2017-04-10'),
(46,'Stairs',5100,50,'2017-04-10','2017-04-16'),
(46,'Walls',6300,110,'2017-04-11','2017-04-20'),
(46,'Roof',5700,110,'2017-04-25','2017-05-04');$$


INSERT INTO `wsc353_4`.`TEAM`(`teamName`)VALUES
('Tight Penguins'),
('Statuesque Anteaters'),
('Illustrious Boars'),
('Better Tigers'),
('Towering Hyenas'),
('Hallowed Cheetahs'),
('Symptomatic Alligators'),
('Wistful Cougars'),
('Brainy Foxes'),
('Silky Deers');$$

INSERT INTO `wsc353_4`.`EMPLOYEE`(`empName`,`empPwd`,`empAddress`,`empPhoneNum`)VALUES
('Ralph Wood', 'password', '255 Wishing Dale Cape, Albanel, Quebec, J9Z-8R2, CA', ' (450) 009-5002'),
('Christopher Morgan', 'catsdogs', '3484 Quiet Nook, Newport, Quebec, H5R-0C7, CA', ' (581) 066-7055'),
('Gerald Gonzalez', 'ilovedbs', '741 Fallen Farm, Pierreville, Quebec, J9O-2Z7, CA', ' (579) 586-4128'),
('Donald Rogers', 'password', '7928 Amber Cloud Terrace, Sainte-genevieve-de-batiscan, Quebec, G8Z-5L1, CA', ' (438) 710-3127'),
('Jesse Butler', 'password', '886 Silver Field, Sainte-martine, Quebec, G9K-2Q9, CA', ' (418) 231-7394'),
('Matthew Jenkins', 'password', '5336 Quaking Barn Rise, Cote-saint-luc, Quebec, G7D-2K4, CA', ' (438) 318-8539'),
('Wayne Sanchez', 'password', '4896 Pleasant Apple Meadow, Chute-shipshaw, Quebec, G2I-4Z0, CA', ' (438) 549-0750'),
('Billy Rivera', 'password', '112 Sleepy Deer Jetty, Sainte-flore, Quebec, J3R-9G0, CA', ' (450) 031-5325'),
('Fred Alexander', 'password', '3716 Cinder View Heights, Saint-adalbert, Quebec, G1G-5T4, CA', ' (581) 989-9921'),
('Roger Kelly', 'password', '8105 Dusty Downs, Palmarol, Quebec, H5D-3K9, CA', ' (450) 039-9841'),
('Douglas Mitchell', 'password', '2484 Gentle Avenue, Lac-saint-paul, Quebec, J1Z-0C5, CA', ' (438) 702-0387'),
('Bobby Peterson', 'password', '3149 Cozy Lane, Ayersville, Quebec, G6R-0P6, CA', ' (581) 172-6037'),
('Harry Patterson', 'password', '3116 Rustic Corners, Saint-francois-du-lac, Quebec, H4I-7B6, CA', ' (438) 797-7364'),
('Eric Stewart', 'password', '2289 Emerald Subdivision, Saint-ours, Quebec, J5D-7H2, CA', ' (418) 776-6066'),
('Alan Jackson', 'password', '9508 Tawny Circuit, Van Bruyssel, Quebec, J2D-1H9, CA', ' (418) 086-5031'),
('Carl Rodriguez', 'password', '3652 Honey Forest, Hervey Junction, Quebec, H8S-2V4, CA', ' (819) 290-1462'),
('Philip Foster', 'password', '6222 Jagged Canyon, Saint Francois Xavier, Quebec, J7A-3R2, CA', ' (581) 060-7124'),
('Brian Bell', 'password', '4730 Merry Heath, Val-d\'or, Quebec, H0K-8F3, CA', ' (514) 272-3689'),
('Steve Lopez', 'password', '9684 Stony Mountain Pike, Coaticook, Quebec, H0W-8U5, CA', ' (581) 210-4755'),
('John Brooks', 'password', '3027 Easy Boulevard, Stanstead, Quebec, H2T-8S2, CA', ' (581) 294-2315'),
('Paul Diaz', 'password', '7784 Middle Via, Saint-paul-du-nord, Quebec, G5X-1N3, CA', ' (579) 806-0522'),
('Michael Parker', 'password', '6433 Burning Pony Green, Lizotte, Quebec, G3X-6E3, CA', ' (438) 411-2426'),
('Chris Collins', 'password', '8976 Hazy Wynd, Sainte-adelaide-de-pabos, Quebec, H6D-7O6, CA', ' (581) 870-7675'),
('Craig Ward', 'password', '2950 Lost Bluff Edge, Forrestville, Quebec, H6A-6N1, CA', ' (819) 081-3719'),
('Joshua Gonzales', 'password', '9198 Noble Hickory Moor, Saint-joachim-de-montmorency, Quebec, H0A-0K9, CA', ' (579) 787-4513'),
('Jack Harris', 'password', '8800 Foggy Beacon Square, East Angus, Quebec, J1A-3X1, CA', ' (514) 650-5927'),
('Ernest Taylor', 'password', '9955 Harvest Carrefour, Moisie, Quebec, H9J-8Z6, CA', ' (581) 591-9179'),
('Raymond Allen', 'password', '5 Sunny Prairie Route, Pointe-au-pic, Quebec, J6Y-3P3, CA', ' (438) 643-1658'),
('Henry Baker', 'password', '3098 Old Berry Bend, Price, Quebec, G8I-8T4, CA', ' (581) 547-7949'),
('Scott Price', 'password', '202 Rocky Quail Range, Ta-tuque, Quebec, G4K-7N0, CA', ' (579) 662-4596'),
('Carlos Anderson', 'password', '3433 Lazy Robin Private, Fort-chimo, Quebec, G2L-7U4, CA', ' (418) 944-9811'),
('Jeffrey Bailey', 'password', '2370 Colonial Log Highlands, Netashquan, Quebec, H6F-1O2, CA', ' (581) 926-4730'),
('Victor Flores', 'password', '989 Dewy River Common, Ham Nord, Quebec, H7Y-1P9, CA', ' (450) 584-4998'),
('Steven Martin', 'password', '6617 Heather Blossom Village, Riviere-saint-jean, Quebec, H6P-3Y3, CA', ' (579) 076-8852'),
('Russell Walker', 'password', '9757 Little Embers Knoll, Thurso, Quebec, J0H-3J3, CA', ' (450) 281-1104'),
('Harold Turner', 'password', '1244 Clear Towers, Saint-fidele-de-mont-murray, Quebec, G5N-9L5, CA', ' (418) 373-1671'),
('Howard Miller', 'password', '8007 Blue Anchor Park, Saint-leon-de-standon, Quebec, J8N-9U1, CA', ' (438) 346-4829'),
('Benjamin Wilson', 'password', '223 Broad Point, Riviere-des-prairies, Quebec, J7N-6M5, CA', ' (579) 129-1950'),
('Frank Nelson', 'password', '4223 Iron Limits, Quebec-ouest, Quebec, J5B-2M1, CA', ' (581) 880-4421'),
('Ronald Long', 'password', '9675 Golden Willow Beach, Fort-rupert, Quebec, H0O-7G7, CA', ' (418) 745-8381'),
('David Ramirez', 'password', '4226 Indian Grove Stead, Saint-jean-de-boischatel, Quebec, J4N-5W4, CA', ' (579) 273-6855'),
('Edward Clark', 'password', '5104 Umber Acres, Marieville, Quebec, G9U-5Y7, CA', ' (438) 217-6430'),
('Dennis Williams', 'password', '426 Hidden Link, Fancamp Township, Quebec, H2M-3R7, CA', ' (514) 123-8969'),
('Roy Barnes', 'password', '7254 Silent Wharf, Ascot Corner, Quebec, G3M-0D9, CA', ' (514) 180-2122'),
('Justin Jones', 'password', '7400 Round Gate Orchard, Montebello, Quebec, J8I-1G0, CA', ' (438) 000-3616'),
('Martin Coleman', 'password', '6129 Cotton Nectar Pathway, Sainte-rose, Quebec, J7R-4V6, CA', ' (819) 470-5038'),
('Terry Scott', 'password', '1309 Grand Leaf Alley, Saint-antoine-des-laurentides, Quebec, G0G-6O6, CA', ' (418) 362-1919'),
('Clarence Howard', 'password', '489 Velvet Pioneer Campus, Saint-elie-d\'orford, Quebec, J2X-9M8, CA', ' (514) 507-6254'),
('Adam Evans', 'password', '5599 Crystal Bank, Port-nouveau-quebec, Quebec, G5K-1V6, CA', ' (438) 709-8464'),
('Aaron Hughes', 'password', '2971 Bright Pointe, Saint-zotique, Quebec, J3L-5X0, CA', ' (438) 113-0903');$$


INSERT INTO wsc353_4.PHASE_MATERIAL(phaseID, matID, qty) VALUES
(4,1,10),
(9,1,10),
(14,1,10),
(19,1,10),
(24,1,10),
(35,1,10),
(39,1,10),
(4,5,3),
(4,6,5),
(4,7,5),
(9,5,3),
(9,6,5),
(9,7,5),
(14,5,3),
(14,6,5),
(14,7,5),
(19,5,3),
(19,6,5),
(19,7,5),
(24,5,3),
(24,6,5),
(24,7,5),
(35,5,3),
(35,6,5),
(35,7,5),
(39,5,3),
(39,6,5),
(39,7,5),
(3,18,100),
(8,18,100),
(13,18,100),
(18,18,100),
(23,18,100),
(34,18,100),
(38,18,100),
(5,19,25),
(10,19,25),
(15,19,25),
(20,19,25),
(25,19,25),
(40,19,25),
(2,23,50),
(7,23,50),
(12,23,50),
(17,23,50),
(22,23,50),
(28,23,50),
(30,23,50),
(33,23,50),
(37,23,50),
(43,23,50),
(45,23,50),
(3,24,1000),
(8,24,1000),
(13,24,1000),
(18,24,1000),
(23,24,1000),
(31,24,1000),
(34,24,1000),
(38,24,1000),
(46,24,1000),
(5,13,3),
(10,13,3),
(15,13,3),
(20,13,3),
(25,13,3),
(40,13,3),
(4,17,500),
(9,17,500),
(14,17,500),
(19,17,500),
(24,17,500),
(35,17,500),
(39,17,500),
(4,10,20),
(9,10,20),
(14,10,20),
(19,10,20),
(24,10,20),
(35,10,20),
(39,10,20),
(4,8,300),
(9,8,300),
(14,8,300),
(19,8,300),
(24,8,300),
(35,8,300),
(39,8,300);$$


INSERT INTO wsc353_4.MATERIAL_SUPPLIER(matID, supID, matCost, deliveryTime) VALUES
(1,1,50,8),
(2,1,5,3),
(3,1,150,8),
(4,1,50,8),
(5,1,100,11),
(6,1,150,11),
(7,1,200,11),
(8,1,7,5),
(9,1,8,7),
(10,1,20,3),
(11,1,15.00,8),
(12,1,200.00,5),
(13,1,80,6),
(14,1,350,4),
(15,1,30,5),
(16,1,150,6),
(17,1,15,6),
(18,1,6,6),
(19,1,5,3),
(20,1,18,6),
(21,1,90,6),
(22,1,10,8),
(23,1,12,7),
(1,2,55,7),
(2,2,8,4),
(3,2,145,6),
(4,2,60,7),
(5,2,110,9),
(6,2,185,9),
(7,2,210,9),
(8,2,6,5),
(9,2,9,7),
(10,2,25,4),
(11,2,15.00,5),
(12,2,230.00,4),
(13,2,75,5),
(14,2,330,3),
(15,2,40,6),
(16,2,155,7),
(17,2,19,6),
(18,2,7,6),
(19,2,5,3),
(20,2,18,6),
(21,2,90,6),
(22,2,10,8),
(23,2,12,7),
(1,3,55,7),
(2,3,8,4),
(3,3,133,6),
(4,3,55,7),
(5,3,105,9),
(6,3,175,9),
(7,3,205,9),
(8,3,7,5),
(9,3,8,7),
(10,3,22,4),
(11,3,16.00,5),
(12,3,225.00,4),
(13,3,69,5),
(14,3,325,3),
(15,3,35,6),
(16,3,145,7),
(17,3,17,6),
(18,3,6,6),
(19,3,6,3),
(20,3,15,6),
(21,3,85,6),
(22,3,11,8),
(23,3,10,7),
(1,4,52,6),
(2,4,7,5),
(3,4,129,5),
(4,4,52,6),
(5,4,119,10),
(6,4,169,10),
(7,4,200,10),
(8,4,9,4),
(9,4,7,6),
(10,4,21,5),
(11,4,17.50,6),
(12,4,230.00,6),
(13,4,73,7),
(14,4,335,5),
(15,4,39,5),
(16,4,153,5),
(17,4,16,4),
(18,4,5,5),
(19,4,7,4),
(20,4,14,6),
(21,4,82,6),
(22,4,10,9),
(23,4,11,7),
(24,1,56,3),
(24,2,6,3),
(24,3,5,2),
(24,4,6,2);$$


INSERT INTO wsc353_4.TEAM_EMPLOYEE VALUES
(1,1,3300),
(2,1,3500),
(3,1,3800),
(4,1,3700),
(5,1,3500),
(6,2,3500),
(7,2,3200),
(8,2,3100),
(9,2,3500),
(10,2,3300),
(11,3,3500),
(12,3,3800),
(13,3,3600),
(14,3,3500),
(15,3,3500),
(16,4,3500),
(17,4,3400),
(18,4,3500),
(19,4,3400),
(20,4,4000),
(21,5,4500),
(22,5,4200),
(23,5,4100),
(24,5,4000),
(25,5,4000),
(26,6,4000),
(27,6,6000),
(28,6,6200),
(29,6,4000),
(30,6,5200),
(31,7,3900),
(32,7,4300),
(33,7,3600),
(34,7,4600),
(35,7,4800),
(36,8,5300),
(37,8,4600),
(38,8,4800),
(39,8,4900),
(40,8,3500),
(41,9,5600),
(42,9,3900),
(43,9,4900),
(44,9,5300),
(45,9,5300),
(46,10,5300),
(47,10,3600),
(48,10,3700),
(49,10,3800),
(50,10,5300);$$


INSERT INTO wsc353_4.PROJECT_TEAM(projid,teamid) VALUES
(1,1),
(1,2),
(2,3),
(2,4),
(3,5),
(3,6),
(4,7),
(4,8),
(5,9),
(5,10),
(6,1),
(6,2),
(7,3),
(7,4),
(8,5),
(8,6),
(9,7),
(9,8),
(10,9),
(10,10),
(11,1),
(11,2),
(12,3),
(12,4),
(13,5),
(13,6);$$


INSERT INTO wsc353_4.TRANSACTION_TASK(transID, projID, taskID ,transCost, transDate) VALUES
(1, 1, 1 ,2500, '2016-10-08'),
(2, 1, 2 ,1500, '2016-10-08'),
(3, 1, 3 ,850, '2016-10-08'),
(4, 1, 4,1000, '2016-10-08'),
(5, 1, 5,5000, '2016-11-09'),
(6, 1, 6,1000, '2016-11-13'),
(7, 1, 7,5000, '2016-12-10'),
(8, 1, 8,3900, '2016-12-12'),
(9, 1, 9,5000, '2016-12-10'),
(10, 1,  10,5000, '2016-12-13'),
(11, 1,  11,1500, '2017-01-13'),
(12, 1,  12,3000, '2017-01-13'),
(13, 1,  13,1500, '2017-01-12'),
(14, 1,  14,1000, '2017-01-18'),
(15, 1,  15,2000, '2017-01-18'),
(16, 1,  16,5000, '2017-01-18'),
(17, 1,  17,5000, '2017-01-18'),
(18, 1,  18,5000, '2017-01-18'),
(19, 1,  19,3000, '2017-01-18'),
(20, 1,  20,4000, '2017-01-24'),
(21, 1,  21,3000, '2017-01-30'),
(22, 1,  22,2500, '2017-02-03'),
(23, 1,  23,3000, '2017-02-05'),
(24, 1,  24,3000, '2017-02-05'),
(25, 1,  25,3000, '2017-02-09'),
(26, 1,  26,1500, '2017-02-13'),
(27, 1,  27,3000, '2017-02-15'),
(28, 2,  28,3500, '2016-08-08'),
(29, 2,  29,1900, '2016-08-08'),
(30, 2,  30,950,  '2016-08-08'),
(31, 2, 31,1500,  '2016-08-08'),
(32, 2, 32,5500, '2016-09-09'),
(33, 2, 33,1200, '2016-09-15'),
(34, 2, 34,4500, '2016-10-11'),
(35, 2, 35,3900, '2016-10-12'),
(36, 2, 36,4800, '2016-10-19'),
(37, 2, 37,5000, '2016-10-25'),
(38, 2, 38,1800, '2016-11-12'),
(39, 2, 39,3500, '2016-11-16'),
(40, 2, 40, 1800, '2016-11-19'),
(41, 2, 41,1500, '2016-11-19'),
(42, 2, 42,2500, '2016-11-19'),
(43, 2, 43,4800, '2016-11-20'),
(44, 2, 44,5300, '2016-11-20'),
(45, 2, 45,4700, '2016-11-20'),
(46, 2, 46,3500, '2016-11-29'),
(47, 2, 47, 3800, '2016-11-28'),
(48, 2, 48, 2800, '2016-11-30'),
(49, 2, 49, 2800, '2016-12-03'),
(50, 2, 50, 3200, '2016-12-03'),
(51, 2, 51, 2700, '2016-12-06'),
(52, 2, 52, 2700, '2016-12-09'),
(53, 2, 53, 1600, '2016-12-12'),
(54, 2, 54, 3200, '2016-12-13'),
(55, 3, 55, 4000, '2016-08-10'),
(56, 3, 56, 2000, '2016-08-17'),
(57, 3, 57, 1050, '2016-08-14'),
(58, 3, 58, 1800, '2016-09-01'),
(59, 3, 59, 7000, '2016-09-12'),
(60, 3, 60, 2000, '2016-09-29'),
(61, 3, 61, 6800, '2016-10-10'),
(62, 3, 62, 5400, '2016-10-19'),
(63, 3, 63, 6500, '2016-10-29'),
(64, 3, 64, 6000, '2016-10-29'),
(65, 3, 65, 3000, '2016-11-13'),
(66, 3, 66, 5000, '2016-11-20'),
(67, 3, 67, 3000, '2016-11-27'),
(68, 3, 68, 2000, '2016-11-20'),
(69, 3, 69, 3500, '2016-11-20'),
(70, 3, 70, 5800, '2016-11-20'),
(71, 3, 71, 5300, '2016-11-20'),
(72, 3, 72, 5300, '2016-11-20'),
(73, 3, 73, 4500, '2016-11-29'),
(74, 3, 74, 4500, '2016-11-28'),
(75, 3, 75, 4000, '2016-11-30'),
(76, 3, 76, 3800, '2016-12-06'),
(77, 3, 77, 4600, '2016-12-06'),
(78, 3, 78, 3700, '2016-12-06'),
(79, 3, 79, 3700, '2016-12-06'),
(80, 3, 80, 3000, '2016-12-25'),
(81, 3, 81, 4200, '2016-12-30'),
(82, 4, 82, 3800,  '2016-07-10'),
(83, 4, 83, 2500,  '2016-07-15'),
(84, 4, 84, 2050,   '2016-07-25'),
(85, 4, 85, 2800,   '2016-08-01'),
(86, 4, 86, 6200,  '2016-08-12'),
(87, 4, 87, 3300,  '2016-08-30'),
(88, 4, 88, 6200,  '2016-09-10'),
(89, 4, 89, 5100,  '2016-09-10'),
(90, 4, 90, 6300,  '2016-09-17'),
(91, 4, 91, 5700,  '2016-10-01'),
(92, 4, 92, 3100,  '2016-10-13'),
(93, 4, 93, 4800,  '2016-10-20'),
(94, 4, 94, 3300,   '2016-10-27'),
(95, 4, 95, 2500,   '2016-10-27'),
(96, 4, 96, 4100,  '2016-10-28'),
(97, 4, 97, 5600,  '2016-10-20'),
(98, 4, 98, 5400,  '2016-10-20'),
(99, 4, 99, 5600,  '2016-10-13'),
(100, 4, 100, 4400, '2016-10-20'),
(101, 4, 101, 4500, '2016-10-20'),
(102, 4, 102, 4000, '2016-11-01'),
(103, 4, 103, 3800, '2016-11-04'),
(104, 4, 104, 4600, '2016-11-04'),
(105, 4, 105, 3700, '2016-11-10'),
(106, 4, 106, 3700, '2016-11-12'),
(107, 4, 107, 3000, '2016-11-20'),
(108, 4, 108, 4200, '2016-11-29'),
(109, 5, 109, 3800, '2016-07-15'),
(110, 5, 110, 2600, '2016-07-15'),
(111, 5, 111, 2250, '2016-07-12'),
(112, 5, 112, 3000, '2016-07-12'),
(113, 5, 113, 6200, '2016-09-10'),
(114, 5, 114, 3360, '2016-08-13'),
(115, 5, 115, 5400, '2016-09-16'),
(116, 5, 116, 5100, '2016-09-20'),
(117, 5, 117, 6300, '2016-09-27'),
(118, 5, 118, 5700, '2016-10-01'),
(119, 5, 119, 3000, '2016-10-13'),
(120, 5, 120, 4100, '2016-10-20'),
(121, 5, 121, 3150, '2016-10-27'),
(122, 5, 122, 2700, '2016-10-27'),
(123, 5, 123, 3800, '2016-10-28'),
(124, 5, 124, 5400, '2016-10-20'),
(125, 5, 125, 5100, '2016-10-20'),
(126, 5, 126, 5200, '2016-10-13'),
(127, 5, 127, 4350, '2016-10-20'),
(128, 5, 128, 4400, '2016-10-20'),
(129, 5, 129, 4000, '2016-11-01'),
(130, 5, 130, 3760, '2016-11-10'),
(131, 5, 131, 4306, '2016-11-04'),
(132, 5, 132, 2900, '2016-11-09'),
(133, 5, 133, 2811, '2016-11-16'),
(134, 5, 134, 3000, '2016-11-26'),
(135, 5, 135, 3915, '2016-11-30'),
(136, 6, 136, 3800, '2017-04-16'),
(137, 6, 137, 2600, '2017-04-16'),
(138, 6, 138, 2250, '2017-04-10'),
(139, 6, 139, 3000, '2017-05-01'),
(140, 7, 140, 3800, '2017-03-01'),
(141, 7, 141, 2600, '2017-03-01'),
(142, 7, 142, 2250, '2017-03-20'),
(143, 7, 143, 3000, '2017-03-26'),
(144, 7, 144, 6200, '2017-04-02'),
(145, 7, 145, 3360, '2017-04-02'),
(146, 8, 146, 3800, '2017-02-20'),
(147, 8, 147, 2600, '2017-02-20'),
(148, 8, 148, 2250, '2017-02-20'),
(149, 8, 149, 3000, '2017-02-01'),
(150, 8, 150, 6200, '2017-03-02'),
(151, 8, 151, 3360, '2017-03-24'),
(152, 8, 152, 5400, '2017-04-03'),
(153, 8, 153, 5100, '2017-04-10'),
(154, 8, 154, 6300, '2017-04-11'),
(155, 8, 155, 5700, '2017-04-25'),
(156, 9, 156, 3800, '2017-01-20'),
(157, 9, 157, 2600, '2017-01-20'),
(158, 9, 158, 2250, '2017-01-01'),
(159, 9, 159, 3000, '2017-01-01'),
(160, 9, 160, 6200, '2017-02-02'),
(161, 9, 161, 3360, '2017-02-25'),
(162, 9, 162, 5400, '2017-03-03'),
(163, 9, 163, 5100, '2017-03-12'),
(164, 9, 164, 6300, '2017-03-17'),
(165, 9, 165, 5700, '2017-03-26'),
(166, 9, 166, 3000, '2017-04-03'),
(167, 9, 167, 4100, '2017-04-10'),
(168, 9, 168, 3150, '2017-04-16'),
(169, 9, 169, 2700, '2017-04-10'),
(170, 9, 170, 3800, '2017-04-23'),
(171, 9, 171, 5400, '2017-04-10'),
(172, 9, 172, 5100, '2017-04-10'),
(173, 9, 173, 5200, '2017-04-10'),
(174, 9, 174, 4350, '2017-04-10'),
(175, 9, 175, 4400, '2017-04-10'),
(176, 9, 176, 4000, '2017-04-15'),
(177, 10, 177, 3800, '2017-12-20'),
(178, 10, 178, 2600, '2017-12-20'),
(179, 10, 179, 2250, '2017-12-01'),
(180, 10, 180, 3000, '2017-12-10'),
(181, 10, 181, 6200, '2017-01-02'),
(182, 10, 182, 3360, '2017-01-24'),
(183, 10, 183, 5450, '2017-02-03'),
(184, 10, 184, 5600, '2017-02-06'),
(185, 10, 185, 6500, '2017-02-10'),
(186, 10, 186, 5900, '2017-02-25'),
(187, 10, 187, 3100, '2017-03-04'),
(188, 10, 188, 4100, '2017-03-12'),
(189, 10, 189, 3450, '2017-03-19'),
(190, 10, 190, 2760, '2017-03-12'),
(191, 10, 191, 3850, '2017-03-12'),
(192, 10, 192, 5200, '2017-03-19'),
(193, 10, 193, 5000, '2017-03-19'),
(194, 10, 194, 5100, '2017-03-19'),
(195, 10, 195, 4150, '2017-03-26'),
(196, 10, 196, 4400, '2017-03-29'),
(197, 10, 197, 4000, '2017-03-30'),
(198, 11, 198, 3800, '2017-04-16'),
(199, 11, 199, 2600, '2017-04-16'),
(200, 11, 200, 2250, '2017-04-10'),
(201, 11, 201, 3000, '2017-05-01'),
(202, 12, 202, 3800, '2017-03-01'),
(203, 12, 203, 2600, '2017-03-01'),
(204, 12, 204, 2250, '2017-03-20'),
(205, 12, 205, 3000, '2017-03-26'),
(206, 12, 206, 6200, '2017-04-02'),
(207, 12, 207, 3360, '2017-04-27'),
(208, 13, 208, 3800, '2017-02-20'),
(209, 13, 209, 2600, '2017-02-20'),
(210, 13, 210, 2250, '2017-02-20'),
(211, 13, 211, 3000, '2017-02-01'),
(212, 13, 212, 6200, '2017-03-02'),
(213, 13, 213, 3360, '2017-03-24'),
(214, 13, 214, 5400, '2017-04-03'),
(215, 13, 215, 5100, '2017-04-10'),
(216, 13, 216, 6300, '2017-04-11'),
(217, 13, 217, 5700, '2017-04-25');$$


INSERT INTO wsc353_4.TRANSACTION_SALARY(transID, projID, empID, teamID, transCost, transDate) VALUES
(1, 1, 1 ,1, 3300,  '2017-03-12'),
(2, 1, 2 ,1, 3500,  '2017-03-12'),
(3, 1, 3 ,1, 3800,  '2017-03-12'),
(4, 1, 4 ,1, 3700,  '2017-03-12'),
(5, 1, 5 ,1, 3500,  '2017-03-12'),
(6, 1, 6 ,2, 3500,  '2017-03-12'),
(7, 1, 7 ,2, 3200,  '2017-03-12'),
(8, 1, 8 ,2, 3100,  '2017-03-12'),
(9, 1, 9 ,2, 3500,  '2017-03-12'),
(10,1, 10 ,2, 3300,  '2017-03-12'),
(11,2, 11, 3,3500,  '2016-12-30'),
(12,2, 12, 3,3800,  '2016-12-30'),
(13,2, 13, 3,3600,  '2016-12-30'),
(14,2, 14, 3,3500,  '2016-12-30'),
(15,2, 15, 3,3500,  '2016-12-30'),
(16,2, 16, 4,3500,  '2016-12-30'),
(17,2, 17, 4,3400,  '2016-12-30'),
(18,2, 18, 4,3500,  '2016-12-30'),
(19,2, 19, 4,3400,  '2016-12-30'),
(20,2, 20, 4,4000,  '2016-12-30'),
(21, 3, 21,5,4500,  '2017-01-03'),
(22, 3, 22,5,4200,  '2017-01-03'),
(23, 3, 23,5,4100,'2017-01-03'),
(24, 3, 24,5,4000, '2017-01-03'),
(25, 3, 25,5,4000, '2017-01-03'),
(26, 3, 26,6,4000, '2017-01-03'),
(27, 3, 27,6,6000, '2017-01-03'),
(28, 3, 28,6,6200, '2017-01-03'),
(29, 3, 29,6,4000, '2017-01-03'),
(30, 3, 30,6,5200, '2017-01-03'),
(31, 4, 31, 7,3900, '2016-12-03'),
(32, 4, 32, 7,4300, '2016-12-03'),
(33, 4, 33, 7,3600, '2016-12-03'),
(34, 4, 34, 7,4600, '2016-12-03'),
(35, 4, 35, 7,4800, '2016-12-03'),
(36, 4, 36, 8,5300, '2016-12-03'),
(37, 4, 37, 8,4600, '2016-12-03'),
(38, 4, 38, 8,4800, '2016-12-03'),
(39, 4, 39, 8,4900, '2016-12-03'),
(40, 4, 40, 8,3500, '2016-12-03'),
(41, 5, 41, 9,5600, '2016-12-03'),
(42, 5, 42, 9,3900, '2016-12-03'),
(43, 5, 43, 9,4900, '2016-12-03'),
(44, 5, 44, 9,5300, '2016-12-03'),
(45, 5, 45, 9,5300, '2016-12-03'),
(46, 5, 46, 10,5300, '2016-12-03'),
(47, 5, 47, 10,3600, '2016-12-03'),
(48, 5, 48, 10,3700, '2016-12-03'),
(49, 5, 49, 10,3800, '2016-12-03'),
(50, 5, 50, 10,5300, '2016-12-03'),
(51, 6, 1,  1,3300, '2017-05-08'),
(52, 6, 2,  1,3500, '2017-05-08'),
(53, 6, 3,  1,3800, '2017-05-08'),
(54, 6, 4,  1,3700, '2017-05-08'),
(55, 6, 5,  1,3500, '2017-05-08'),
(56, 6, 6,  2,3500, '2017-05-08'),
(57, 6, 7,  2,3200, '2017-05-08'),
(58, 6, 8,  2,3100, '2017-05-08'),
(59, 6, 9,  2,3500, '2017-05-08'),
(60, 6, 10, 2, 3300, '2017-05-08'),
(61, 7, 11, 3,3500, '2017-04-02'),
(62, 7, 12, 3,3800, '2017-04-02'),
(63, 7, 13, 3,3600, '2017-04-02'),
(64, 7, 14, 3,3500, '2017-04-02'),
(65, 7, 15, 3,3500, '2017-04-02'),
(66, 7, 16, 4,3500, '2017-04-02'),
(67, 7, 17, 4,3400, '2017-04-02'),
(68, 7, 18, 4,3500, '2017-04-02'),
(69, 7, 19, 4,3400, '2017-04-02'),
(70, 7, 20, 4,5000, '2017-04-02'),
(71, 8, 21, 5,4500, '2017-04-25'),
(72, 8, 22, 5,4200, '2017-04-25'),
(73, 8, 23, 5,4100, '2017-04-25'),
(74, 8, 24, 5,4000, '2017-04-25'),
(75, 8, 25, 5,4000, '2017-04-25'),
(76, 8, 26, 6,4000, '2017-04-25'),
(77, 8, 27, 6,6000, '2017-04-25'),
(78, 8, 28, 6,6200, '2017-04-25'),
(79, 8, 29, 6,4000, '2017-04-25'),
(80, 8, 30, 6,5200, '2017-04-25'),
(81, 9, 31, 7,3900, '2017-04-15'),
(82, 9, 32, 7,4300, '2017-04-15'),
(83, 9, 33, 7,3600, '2017-04-15'),
(84, 9, 34, 7,4600, '2017-04-15'),
(85, 9, 35, 7,4800, '2017-04-15'),
(86, 9, 36, 8,5300, '2017-04-15'),
(87, 9, 37, 8,4600, '2017-04-15'),
(88, 9, 38, 8,4800, '2017-04-15'),
(89, 9, 39, 8,4900, '2017-04-15'),
(90, 9, 40, 8,3500, '2017-04-15'),
(91, 10, 41, 9,5600, '2016-03-20'),
(92, 10, 42, 9,3900, '2017-03-20'),
(93, 10, 43, 9,4900, '2017-03-20'),
(94, 10, 44, 9,5300, '2017-03-20'),
(95, 10, 45, 9,5300, '2017-03-20'),
(96, 10, 46, 10,5300, '2017-03-20'),
(97, 10, 47, 10,3600, '2017-03-20'),
(98, 10, 48, 10,3700, '2017-03-20'),
(99, 10, 49, 10,3800, '2017-03-20'),
(100, 10, 50, 10,5300, '2017-03-20'),
(101, 11, 1,  1 ,3300, '2017-05-01'),
(102, 11, 2,  1 ,3500, '2017-05-01'),
(103, 11, 3,  1 ,3800, '2017-05-01'),
(104, 11, 4,  1 ,3700, '2017-05-01'),
(105, 11, 5,  1 ,3500, '2017-05-01'),
(106, 11, 6,  2 ,3500, '2017-05-01'),
(107, 11, 7,  2 ,3200, '2017-05-01'),
(108, 11, 8,  2 ,3100, '2017-05-01'),
(109, 11, 9,  2 ,3500, '2017-05-01'),
(110, 11, 10, 2, 3300, '2017-05-01'),
(111, 12, 11, 3,3500,  '2017-05-03'),
(112, 12, 12, 3,3800,  '2017-05-03'),
(113, 12, 13, 3,3600,  '2017-05-03'),
(114, 12, 14, 3,3500,  '2017-05-03'),
(115, 12, 15, 3,3500,  '2017-05-03'),
(116, 12, 16, 4,3500,  '2017-05-03'),
(117, 12, 17, 4,3400,  '2017-05-03'),
(118, 12, 18, 4,3500,  '2017-05-03'),
(119, 12, 19, 4,3400,  '2017-05-03'),
(120, 12, 20, 4,4000,  '2017-05-03'),
(121, 13, 21, 5,4500, '2017-05-03'),
(122, 13, 22, 5,4200, '2017-05-03'),
(123, 13, 23, 5,4100, '2017-05-03'),
(124, 13, 24, 5,4000, '2017-05-03'),
(125, 13, 25, 5,4000, '2017-05-03'),
(126, 13, 26, 6,4000, '2017-05-03'),
(127, 13, 27, 6,6000, '2017-05-03'),
(128, 13, 28, 6,6200, '2017-05-03'),
(129, 13, 29, 6,4000, '2017-05-03'),
(130, 13, 30, 6,5200, '2017-05-03');$$


INSERT INTO wsc353_4.TRANSACTION_MATERIAL(transID, projID, matID, supID, transCost, transDate) VALUES
(1, 1, 1, 1, 500, '2017-01-03'),
(2, 1, 5, 1,300, '2017-01-03'),
(3, 1, 6, 1,900, '2017-01-03'),
(4, 1, 7, 1,1000, '2017-01-04'),
(5, 1, 8, 1,2700,  '2017-01-05'),
(6, 1, 10, 1,420, '2017-01-06'),
(7, 1, 13, 1,240,  '2017-01-06') ,
(8, 1, 17, 1,9500,  '2017-01-06'),
(9, 1, 18, 1,700,  '2017-01-06') ,
(10, 1, 19, 1,125,  '2017-01-06'),
(11, 1, 23, 1,550,  '2017-01-06'),
(12, 1, 24, 1,6000,  '2017-01-06'),
(13, 2, 1,  1 ,500, '2016-09-08'),
(14, 2, 5,  1 ,300, '2016-09-08'),
(15, 2, 6,  1 ,900, '2016-09-08'),
(16, 2, 7,  1 ,1000,  '2016-09-08'),
(17, 2, 8,  1 ,2700, '2016-09-08'),
(18, 2, 10, 1 , 420, '2016-09-08'),
(19, 2, 13, 1 , 240, '2016-09-08'),
(20, 2, 17, 1 , 9500, '2016-09-08'),
(21, 2, 18, 1 , 700, '2016-09-08'),
(22, 2, 19, 1 , 125, '2016-09-08'),
(23, 2, 23, 1 , 550, '2016-09-08'),
(24, 2, 24, 1 , 6000, '2016-09-08'),
(25, 3, 1,  1,500,  '2016-09-12'),
(26, 3, 5,  1,300,  '2016-09-12'),
(27, 3, 6,  1,900,  '2016-09-12'),
(28, 3, 7,  1,1000, '2016-09-12'),
(29, 3, 8,  1,2700, '2016-09-12'),
(30, 3, 10, 1, 420,  '2016-09-12'),
(31, 3, 13, 1, 240,  '2016-09-12'),
(32, 3, 17, 1, 9500,  '2016-09-12'),
(33, 3, 18, 1, 700, '2016-09-12'),
(34, 3, 19, 1, 125, '2016-09-12'),
(35, 3, 23, 1, 550, '2016-09-12'),
(36, 3, 24, 1, 6000, '2016-09-12'),
(37, 4, 1,  1,500,  '2016-08-12'),
(38, 4, 5,  1,300,  '2016-08-12'),
(39, 4, 6,  1,900,  '2016-08-12'),
(40, 4, 7,  1,1000, '2016-08-12'),
(41, 4, 8,  1,2700, '2016-08-12'),
(42, 4, 10, 1, 420,  '2016-08-12'),
(43, 4, 13, 1, 240,  '2016-08-12'),
(44, 4, 17, 1, 9500,  '2016-08-12'),
(45, 4, 18, 1, 700, '2016-08-12'),
(46, 4, 19, 1, 125, '2016-08-12'),
(47, 4, 23, 1, 550, '2016-08-12'),
(48, 4, 24, 1, 6000, '2016-08-12'),
(49, 5, 1,  1,500,  '2016-07-12'),
(50, 5, 5,  1,300,  '2016-07-12'),
(51, 5, 6,  1,900,  '2016-07-12'),
(52, 5, 7,  1,1000,  '2016-07-12'),
(53, 5, 8,  1,2700, '2016-07-12'),
(54, 5, 10, 1, 420,  '2016-07-12'),
(55, 5, 13, 1, 240,  '2016-07-12'),
(56, 5, 17, 1, 9500,  '2016-07-12'),
(57, 5, 18, 1, 700, '2016-07-12'),
(58, 5, 19, 1, 125, '2016-07-12'),
(59, 5, 23, 1, 550, '2016-07-12'),
(60, 5, 24, 1, 6000, '2016-07-12'),
(61, 7, 23, 1,550, '2017-04-02'),
(62, 8, 23, 1,550, '2017-02-20'),
(63, 8, 24, 1,6000,'2016-02-20'),
(64, 9, 8, 1,2700, '2017-01-01'),
(65, 9, 10, 1,420, '2017-01-01'),
(66, 9, 17, 1,9500, '2017-01-01'),
(67, 9, 18, 1,700, '2017-01-01'), 
(68, 9, 23, 1,550, '2017-01-01'), 
(69, 9, 24, 1,6000, '2017-01-01'),
(70, 10, 1,  1,500,  '2016-12-10'),
(71, 10, 5,  1,300,  '2016-12-10'),
(72, 10, 6,  1,900,  '2016-12-10'),
(73, 10, 7,  1,1000, '2016-12-10'),
(74, 10, 8,  1,2700, '2016-12-10'),
(75, 10, 10, 1, 420,  '2016-12-10'),
(76, 10, 13, 1, 240,  '2016-12-10'),
(77, 10, 17, 1, 9500,  '2016-12-10'),
(78, 10, 18, 1, 700, '2016-12-10'),
(79, 10, 19, 1, 125, '2016-12-10'),
(80, 10, 23, 1, 550, '2016-12-10'),
(81, 10, 24, 1, 6000, '2016-12-10'),
(82, 12, 23, 1,550, '2017-05-03'),
(83, 13, 23, 1,550, '2017-02-20'),
(84, 13, 24, 1,6000, '2016-02-20');$$
