drop table UserID cascade constraints;
drop table Developer cascade constraints;
drop table Player cascade constraints;
drop table Game_uploads cascade constraints;
drop table Game_rate cascade constraints;
drop table Achievement cascade constraints;
drop table Type cascade constraints;
drop table Forum_category_creates cascade constraints;
drop table ForumArticle_posts cascade constraints;
drop table Follow_up_posts cascade constraints;
drop table isFriend cascade constraints;
drop table rates cascade constraints;
drop table views cascade constraints;
drop table plays cascade constraints;
drop table GameRecord_recordedTo cascade constraints;
--drop table Purchases_profits_records cascade constraints;
drop table Purchases_profits_detail cascade constraints;
drop table achieves cascade constraints;
drop table associates cascade constraints;
drop table Comment_writesTo cascade constraints;
drop table replies cascade constraints;
drop table favorites cascade constraints;
drop table isOf cascade constraints;
drop table mentions cascade constraints;
drop sequence GID_generate;

CREATE TABLE UserID (
	ID		INT		 PRIMARY KEY,
	Nickname	VARCHAR(30) NOT NULL UNIQUE,
	Password	VARCHAR(30),
	Gender	VARCHAR(6),
	Birthday	DATE,
	AccCreation	DATE,
	Role VARCHAR(10)
);

CREATE TABLE Developer (
	ID		INT	PRIMARY KEY,
	YearsOfExp	INT,
	TPurchase	INT,
	TProfit		DECIMAL(10,2),
FOREIGN KEY (ID) REFERENCES UserID (ID)
);

CREATE TABLE Player (
	ID	INT	PRIMARY KEY ,
FOREIGN KEY (ID) REFERENCES UserID (ID)
);

CREATE TABLE Game_uploads (
	GID			INT			PRIMARY KEY,
	DevID		INT			NOT NULL,
	Name 		VARCHAR(40) 	NOT NULL 	UNIQUE,
	Rating		DECIMAL(2,1),
	Price		DECIMAL(10,2),
	--Cover
	UploadDate	DATE,
FOREIGN KEY (DevID) REFERENCES Developer (ID)
);

CREATE TABLE Game_rate (
	Rating		DECIMAL(2,1)	PRIMARY KEY,
	Rank		VARCHAR(10)
);

CREATE TABLE Achievement (
	AID	VARCHAR(10)	PRIMARY KEY,
	Name	VARCHAR(20)	NOT NULL
	--Badge
);

CREATE TABLE Type (
	Name	VARCHAR(20)	PRIMARY KEY
);

CREATE TABLE Forum_category_creates (
	Name		VARCHAR(60)	PRIMARY KEY,
	Category	VARCHAR(20)	NOT NULL,
	CreatorID	INT			NOT NULL,
	CreateDate	DATE,
	FOREIGN KEY (Category) REFERENCES Type (Name),
	FOREIGN KEY (CreatorID) REFERENCES UserID (ID)
);

CREATE TABLE ForumArticle_posts (
	ArtID		VARCHAR(10)	PRIMARY KEY,
	Forum		VARCHAR(60)	NOT NULL,
	AuthorID	INT			NOT NULL,
	Title		VARCHAR(60)	NOT NULL,
	Time		TIMESTAMP(2),
	Views		INT,
	Content	VARCHAR(2000)	NOT NULL,
	FOREIGN KEY (Forum) REFERENCES Forum_category_creates (Name),
	FOREIGN KEY (AuthorID) REFERENCES UserID (ID)
);

CREATE TABLE Follow_up_posts (
	ArtID		VARCHAR(10),
	FID		VARCHAR(10),
	AuthorID	INT			NOT NULL,
	Time		TIMESTAMP(2),
	Content	VARCHAR(2000)	NOT NULL,
	PRIMARY KEY (ArtID, FID),
	FOREIGN KEY (ArtID) REFERENCES ForumArticle_posts(ArtID),
	FOREIGN KEY (AuthorID) REFERENCES UserID (ID)
);

CREATE TABLE isFriend (
	User1ID	INT,
	User2ID	INT,
	PRIMARY KEY (User1ID, User2ID),
	FOREIGN KEY (User1ID) REFERENCES UserID (ID),
	FOREIGN KEY (User2ID) REFERENCES UserID (ID)
);

CREATE TABLE rates (
	PlayerID	INT,
	GID			INT,
	Rating		INT,
	PRIMARY KEY (PlayerID, GID),
	FOREIGN KEY (PlayerID) REFERENCES Player (ID),
	FOREIGN KEY (GID)
		REFERENCES Game_uploads (GID)
		ON DELETE CASCADE
);

CREATE TABLE views (
	PlayerID	INT,
	GID			INT,
	Time		TIMESTAMP(2),
	PRIMARY KEY (PlayerID, GID, Time),
FOREIGN KEY (PlayerID) REFERENCES Player(ID),
	FOREIGN KEY (GID)
		REFERENCES Game_uploads (GID)
		ON DELETE CASCADE
);

CREATE TABLE plays (
	PlayerID		INT,
	GID				INT,
	AccumPlayTime	INT,
	CurrStage		INT,
	AccumScore		INT,
	PRIMARY KEY (PlayerID, GID),
	FOREIGN KEY (PlayerID) REFERENCES Player(ID),
	FOREIGN KEY (GID)
		REFERENCES Game_uploads (GID)
		ON DELETE CASCADE
);

CREATE TABLE GameRecord_recordedTo (
	PlayerID	INT,
	GID			INT,
	StartTime		TIMESTAMP(2),
	EndTime		TIMESTAMP(2),
	Score		INT,
	PRIMARY KEY (PlayerID, GID, StartTime),
	FOREIGN KEY (PlayerID) REFERENCES Player(ID),
	FOREIGN KEY (GID)
		REFERENCES Game_uploads (GID)
		ON DELETE CASCADE
);

-- CREATE TABLE Purchases_profits_records (
-- 	GID		INT	PRIMARY KEY,
-- 	DevID		INT,
-- 	AmountProfit	REAL,
-- 	FOREIGN KEY (GID) REFERENCES Game_uploads(GID),
-- 	FOREIGN KEY (DevID) REFERENCES Developer (ID),
-- 	FOREIGN KEY (AmountProfit) REFERENCES Game_uploads (Price)
-- );

CREATE TABLE Purchases_profits_detail (
	PlayerID	INT,
	GID			INT,
	Purchase_Date		DATE,
	PayMethod	VARCHAR(10),
	PRIMARY KEY (PlayerID, GID),
	FOREIGN KEY (PlayerID) REFERENCES Player(ID),
	FOREIGN KEY (GID)
		REFERENCES Game_uploads (GID)
		ON DELETE CASCADE
);

CREATE TABLE achieves (
	UserID	INT,
	AID		VARCHAR(10),
	Achieve_Date		DATE,
	PRIMARY KEY (UserID, AID),
	FOREIGN KEY (UserID) REFERENCES UserID(ID),
	FOREIGN KEY (AID) REFERENCES Achievement(AID)
);

CREATE TABLE associates (
	GID	INT,
	AID	VARCHAR(10),
	PRIMARY KEY (GID, AID),
	FOREIGN KEY (GID)
		REFERENCES Game_uploads (GID)
		ON DELETE CASCADE,
	FOREIGN KEY (AID) REFERENCES Achievement(AID)
);

CREATE TABLE Comment_writesTo (
	CID		VARCHAR(20) PRIMARY KEY,
	PlayerID	INT		DEFAULT 0,
	GID		INT	 NOT NULL,
	Content	VARCHAR(2000)	NOT NULL,
	Time		TIMESTAMP(2),
	FOREIGN KEY (GID)
		REFERENCES Game_uploads (GID)
		ON DELETE CASCADE,
	FOREIGN KEY (PlayerID)
		REFERENCES Player(ID)
		ON DELETE SET NULL
);

CREATE TABLE replies (
	UserID		INT,
	GID			INT,
	Time		TIMESTAMP(2),
	Content	VARCHAR(2000),
	PRIMARY KEY (UserID, GID),
	FOREIGN KEY (GID)
		REFERENCES Game_uploads (GID)
		ON DELETE CASCADE,
	FOREIGN KEY (UserID) REFERENCES UserID (ID)
);

CREATE TABLE favorites (
	UserID		INT,
	Type		VARCHAR(20),
	PRIMARY KEY (UserID, Type),
	FOREIGN KEY (Type) REFERENCES Type (Name),
	FOREIGN KEY (UserID) REFERENCES UserID (ID)
);

CREATE TABLE isOf (
	GID		INT,
	Type		VARCHAR(20),
	PRIMARY KEY (GID, Type),
	FOREIGN KEY (GID)
		REFERENCES Game_uploads (GID)
		ON DELETE CASCADE,
	FOREIGN KEY (Type) REFERENCES Type (Name)
);

CREATE TABLE mentions (
	ArtID		VARCHAR(10),
	GID		INT,
	FOREIGN KEY (ArtID) REFERENCES ForumArticle_posts (ArtID),
	FOREIGN KEY (GID)
		REFERENCES Game_uploads (GID)
		ON DELETE CASCADE
);

INSERT INTO UserID (ID, Nickname, Password, Gender, Birthday, AccCreation, Role) VALUES (000001, 'Harrison', 'Harry123', 'Male', '17-DEC-88', '03-JUN-18', 'Player');
INSERT INTO UserID (ID, Nickname, Password, Gender, Birthday, AccCreation, Role) VALUES (000002, 'Omaha', 'Password', 'Male', '22-AUG-98', '01-SEP-19', 'Developer');
INSERT INTO UserID (ID, Nickname, Password, Gender, Birthday, AccCreation, Role) VALUES (000003, 'Mark', 'TouhouIsBest', 'Male', '23-APR-96', '01-JAN-19', 'Developer');
INSERT INTO UserID (ID, Nickname, Password, Gender, Birthday, AccCreation, Role) VALUES (000004, 'HollySteve', 'WHATTHEHECK', 'Other', '30-OCT-93', '22-JUN-19', 'Player');
INSERT INTO UserID (ID, Nickname, Password, Gender, Birthday, AccCreation, Role) VALUES (000005, 'ASAP', 'bettergirl', 'Female', '01-JAN-01', '12-MAY-19', 'Player');
INSERT INTO UserID (ID, Nickname, Password, Gender, Birthday, AccCreation, Role) VALUES (000006, 'Marry', 'abcde', 'Female', '12-FEB-99', '09-MAR-20', 'Player');
INSERT INTO UserID (ID, Nickname, Password, Gender, Birthday, AccCreation, Role) VALUES (000007, 'Gorini', 'Ra345', 'Male', '17-MAR-00', '01-AUG-19', 'Player');
INSERT INTO UserID (ID, Nickname, Password, Gender, Birthday, AccCreation, Role) VALUES (000008, 'Rosetti', '1234567Abc', 'Female', '06-JUN-96', '22-JUL-18', 'Developer');
INSERT INTO UserID (ID, Nickname, Password, Gender, Birthday, AccCreation, Role) VALUES (000009, 'Creeber', 'Co43245', 'Other', '17-FEB-98', '11-APR-19', 'Player');
INSERT INTO UserID (ID, Nickname, Password, Gender, Birthday, AccCreation, Role) VALUES (000010, 'Balnaves', 'Le5625', 'Female', '11-NOV-91', '21-OCT-19', 'Player');
INSERT INTO UserID (ID, Nickname, Password, Gender, Birthday, AccCreation, Role) VALUES (000011, 'Maestrini', 'Le2345', 'Female', '21-JUL-94', '27-FEB-20', 'Developer');
INSERT INTO UserID (ID, Nickname, Password, Gender, Birthday, AccCreation, Role) VALUES (000012, 'Mario', 'SmashBro', 'Male', '03-MAR-93', '11-MAY-19', 'Player');

INSERT INTO Developer (ID, YearsOfExp, TPurchase, TProfit) VALUES (000002, 2, 5, 47.00);
INSERT INTO Developer (ID, YearsOfExp, TPurchase, TProfit) VALUES (000003, 1, 3, 2.00);
INSERT INTO Developer (ID, YearsOfExp, TPurchase, TProfit) VALUES (000008, 2, 7, 149.00);
INSERT INTO Developer (ID, YearsOfExp, TPurchase, TProfit) VALUES (000011, 1, 4, 0.00);

INSERT INTO Player (ID) VALUES (000001);
INSERT INTO Player (ID) VALUES (000004);
INSERT INTO Player (ID) VALUES (000005);
INSERT INTO Player (ID) VALUES (000006);
INSERT INTO Player (ID) VALUES (000007);
INSERT INTO Player (ID) VALUES (000009);
INSERT INTO Player (ID) VALUES (000010);
INSERT INTO Player (ID) VALUES (000012);

-- Game tuples that belongs to the 4 developers
INSERT INTO Game_uploads (GID, DevID, Name, Rating, Price, UploadDate) VALUES (0200, 000002, 'Run Run Run', 4.2, 0.00, '19-SEP-19');
INSERT INTO Game_uploads (GID, DevID, Name, Rating, Price, UploadDate) VALUES (0201, 000002, 'Ventosanzap', 3.7, 3.00, '22-JAN-20');
INSERT INTO Game_uploads (GID, DevID, Name, Rating, Price, UploadDate) VALUES (0202, 000002, 'Vagram', 1.2, 20.00, '04-FEB-20');
INSERT INTO Game_uploads (GID, DevID, Name, Rating, Price, UploadDate) VALUES (0203, 000002, 'Cooky', 3.8, 2.00, '07-APR-20');
INSERT INTO Game_uploads (GID, DevID, Name, Rating, Price, UploadDate) VALUES (0204, 000002, 'Holdlamis', 4.1, 9.90, '01-JUN-20');

INSERT INTO Game_uploads (GID, DevID, Name, Rating, Price, UploadDate) VALUES (0300, 000003, 'Rim', 0, 0.00, '12-FEB-19');
INSERT INTO Game_uploads (GID, DevID, Name, Rating, Price, UploadDate) VALUES (0301, 000003, 'Road Map', 3.5, 0.00, '21-SEP-19');
INSERT INTO Game_uploads (GID, DevID, Name, Rating, Price, UploadDate) VALUES (0302, 000003, 'Valkyrie', 4.0, 2.00, '23-APR-20');

INSERT INTO Game_uploads (GID, DevID, Name, Rating, Price, UploadDate) VALUES (0801, 000008, 'So Far So Good', 0, 1.00, '02-SEP-18');
INSERT INTO Game_uploads (GID, DevID, Name, Rating, Price, UploadDate) VALUES (0802, 000008, 'Hold My Beer', 3.0, 0.00, '28-SEP-18');
INSERT INTO Game_uploads (GID, DevID, Name, Rating, Price, UploadDate) VALUES (0803, 000008, 'Dwalf and Dungen', 4.0, 1.00, '14-JAN-19');
INSERT INTO Game_uploads (GID, DevID, Name, Rating, Price, UploadDate) VALUES (0804, 000008, 'Snap Da Bug!', 2.7, 0.00, '29-MAR-19');
INSERT INTO Game_uploads (GID, DevID, Name, Rating, Price, UploadDate) VALUES (0805, 000008, 'Vegetation', 1.2, 0.00, '04-APR-19');
INSERT INTO Game_uploads (GID, DevID, Name, Rating, Price, UploadDate) VALUES (0806, 000008, 'Who That DUDE', 3.8, 2.00, '07-DEC-19');
INSERT INTO Game_uploads (GID, DevID, Name, Rating, Price, UploadDate) VALUES (0807, 000008, 'Underworld', 4.5, 12.5, '01-JAN-20');

INSERT INTO Game_uploads (GID, DevID, Name, Rating, Price, UploadDate) VALUES (1100, 000011, 'HELP ME', 0, 0.00, '29-FEB-20');
INSERT INTO Game_uploads (GID, DevID, Name, Rating, Price, UploadDate) VALUES (1101, 000011, 'SUB', 0, 3.00, '12-MAR-20');
INSERT INTO Game_uploads (GID, DevID, Name, Rating, Price, UploadDate) VALUES (1102, 000011, 'IM HUNGRY', 2.0, 0.00, '05-APR-20');
INSERT INTO Game_uploads (GID, DevID, Name, Rating, Price, UploadDate) VALUES (1103, 000011, 'BYE', 0, 2.00, '05-JUN-20');

--Matching all ratings to respective ranks
INSERT INTO Game_rate (Rating, Rank) VALUES (5.0,'Excellent');
INSERT INTO Game_rate (Rating, Rank) VALUES (4.9,'Excellent');
INSERT INTO Game_rate (Rating, Rank) VALUES (4.8,'Excellent');
INSERT INTO Game_rate (Rating, Rank) VALUES (4.7,'Excellent');
INSERT INTO Game_rate (Rating, Rank) VALUES (4.6,'Excellent');
INSERT INTO Game_rate (Rating, Rank) VALUES (4.5,'Excellent');
INSERT INTO Game_rate (Rating, Rank) VALUES (4.4,'Excellent');
INSERT INTO Game_rate (Rating, Rank) VALUES (4.3,'Excellent');
INSERT INTO Game_rate (Rating, Rank) VALUES (4.2,'Excellent');
INSERT INTO Game_rate (Rating, Rank) VALUES (4.1,'Excellent');
INSERT INTO Game_rate (Rating, Rank) VALUES (4.0,'Excellent');
INSERT INTO Game_rate (Rating, Rank) VALUES (3.9,'Great');
INSERT INTO Game_rate (Rating, Rank) VALUES (3.8,'Great');
INSERT INTO Game_rate (Rating, Rank) VALUES (3.7,'Great');
INSERT INTO Game_rate (Rating, Rank) VALUES (3.6,'Great');
INSERT INTO Game_rate (Rating, Rank) VALUES (3.5,'Great');
INSERT INTO Game_rate (Rating, Rank) VALUES (3.4,'Great');
INSERT INTO Game_rate (Rating, Rank) VALUES (3.3,'Great');
INSERT INTO Game_rate (Rating, Rank) VALUES (3.2,'Great');
INSERT INTO Game_rate (Rating, Rank) VALUES (3.1,'Great');
INSERT INTO Game_rate (Rating, Rank) VALUES (3.0,'Great');
INSERT INTO Game_rate (Rating, Rank) VALUES (2.9,'Mediocre');
INSERT INTO Game_rate (Rating, Rank) VALUES (2.8,'Mediocre');
INSERT INTO Game_rate (Rating, Rank) VALUES (2.7,'Mediocre');
INSERT INTO Game_rate (Rating, Rank) VALUES (2.6,'Mediocre');
INSERT INTO Game_rate (Rating, Rank) VALUES (2.5,'Mediocre');
INSERT INTO Game_rate (Rating, Rank) VALUES (2.4,'Mediocre');
INSERT INTO Game_rate (Rating, Rank) VALUES (2.3,'Mediocre');
INSERT INTO Game_rate (Rating, Rank) VALUES (2.2,'Mediocre');
INSERT INTO Game_rate (Rating, Rank) VALUES (2.1,'Mediocre');
INSERT INTO Game_rate (Rating, Rank) VALUES (2.0,'Mediocre');
INSERT INTO Game_rate (Rating, Rank) VALUES (1.9,'Poor');
INSERT INTO Game_rate (Rating, Rank) VALUES (1.8,'Poor');
INSERT INTO Game_rate (Rating, Rank) VALUES (1.7,'Poor');
INSERT INTO Game_rate (Rating, Rank) VALUES (1.6,'Poor');
INSERT INTO Game_rate (Rating, Rank) VALUES (1.5,'Poor');
INSERT INTO Game_rate (Rating, Rank) VALUES (1.4,'Poor');
INSERT INTO Game_rate (Rating, Rank) VALUES (1.3,'Poor');
INSERT INTO Game_rate (Rating, Rank) VALUES (1.2,'Poor');
INSERT INTO Game_rate (Rating, Rank) VALUES (1.1,'Poor');
INSERT INTO Game_rate (Rating, Rank) VALUES (1.0,'Poor');
INSERT INTO Game_rate (Rating, Rank) VALUES (0,'No Rate');

INSERT INTO Achievement (AID, Name) VALUES ('1a1','First Game!');
INSERT INTO Achievement (AID, Name) VALUES ('3a2','Speed Runner');
INSERT INTO Achievement (AID, Name) VALUES ('2s1','ALL KILL');
INSERT INTO Achievement (AID, Name) VALUES ('7f4','King of the Land');
INSERT INTO Achievement (AID, Name) VALUES ('84r','FOB');

INSERT INTO Type (Name) VALUES ('Action');
INSERT INTO Type (Name) VALUES ('RPG');
INSERT INTO Type (Name) VALUES ('Simulation');
INSERT INTO Type (Name) VALUES ('Sports');
INSERT INTO Type (Name) VALUES ('Strategy');
INSERT INTO Type (Name) VALUES ('Puzzle');
INSERT INTO Type (Name) VALUES ('FPS');
INSERT INTO Type (Name) VALUES ('Visual Novel');
INSERT INTO Type (Name) VALUES ('Crafting');
INSERT INTO Type (Name) VALUES ('Parkour');

INSERT INTO Forum_category_creates  (Name, Category, CreatorID, CreateDate) VALUES ('FIFA is Da BEST', 'Sports', 000010, '27-NOV-2019');
INSERT INTO Forum_category_creates  (Name, Category, CreatorID, CreateDate) VALUES ('Animal Crossing Marketplace', 'Simulation', 000002, '02-OCT-2019');
INSERT INTO Forum_category_creates  (Name, Category, CreatorID, CreateDate) VALUES ('Is Ubisoft bankrupt yet', 'RPG', 000004, '25-OCT-19');
INSERT INTO Forum_category_creates  (Name, Category, CreatorID, CreateDate) VALUES ('Jessica fanclub', 'Visual Novel', 000009, '20-FEB-20');
INSERT INTO Forum_category_creates  (Name, Category ,CreatorID, CreateDate) VALUES ('Majo No Ie', 'RPG', 000008, '18-DEC-19');

INSERT INTO ForumArticle_posts (ArtID, Forum, AuthorID, Title, Time, Views, Content) VALUES ('a100z567', 'FIFA is Da BEST', 000004, 'Anyone having the same bug?', to_date('2020-01-04:23:02:34','YYYY-MM-DD:HH24:MI:SS'), 21, 
	'This notification keeps popping up after the welcome screen and I can''t login the game: "ERROR 0034 Configuration Missmatch 27688doDFy879"');
INSERT INTO ForumArticle_posts (ArtID, Forum, AuthorID, Title, Time, Views, Content) VALUES ('diqw78f', 'Jessica fanclub', 000007, 'Amazing fanart found on Twitter', to_date('2020-03-03:16:33:04','YYYY-MM-DD:HH24:MI:SS'), 68, 
	'Chekcout this artist @kiminodaikon on Twitter. His work is so sickkkkk');
INSERT INTO ForumArticle_posts (ArtID, Forum, AuthorID, Title, Time, Views, Content) VALUES ('f8hf92wd', 'Is Ubisoft bankrupt yet', 000008, 'My thoughts on the new AC update', to_date('2020-01-23:00:18:20','YYYY-MM-DD:HH24:MI:SS'), 5, 'Blablabla…');
INSERT INTO ForumArticle_posts (ArtID, Forum, AuthorID, Title, Time, Views, Content) VALUES ('xcvhhy93', 'FIFA is Da BEST', 000002, 'The FULLEST character analysis ever', to_date('2019-12-05:09:20:00','YYYY-MM-DD:HH24:MI:SS'), 2334, 'Blablabla…');
INSERT INTO ForumArticle_posts (ArtID, Forum, AuthorID, Title, Time, Views, Content) VALUES ('987dshkjg', 'Majo No Ie', 000010, 'UWU Happy new year peasants', to_date('2020-01-01:00:11:39','YYYY-MM-DD:HH24:MI:SS'), 12, 
	'Whassup just checking on yall guys. Happy new year folks');

INSERT INTO Follow_up_posts (ArtID, FID, AuthorID, Time, Content) VALUES ('a100z567','f45j', 000002, to_date('2020-01-05:00:06:29','YYYY-MM-DD:HH24:MI:SS'),
	'Many people have been having the same bug recently, and EA said they are working on this. It is likely that they will release a patch by today. ');
INSERT INTO Follow_up_posts (ArtID, FID, AuthorID, Time, Content) VALUES ('a100z567', 'd78', 000004, to_date('2020-01-05:00:08:02','YYYY-MM-DD:HH24:MI:SS'), 
	'WTH alright. Not surprised cuz it''s EA man. ');
INSERT INTO Follow_up_posts (ArtID, FID, AuthorID, Time, Content) VALUES ('987dshkjg', 's45b', 000008, to_date('2020-01-01:01:11:39','YYYY-MM-DD:HH24:MI:SS'),'Y u so fast HUH?');
INSERT INTO Follow_up_posts (ArtID, FID, AuthorID, Time, Content) VALUES ('xcvhhy93', '6823', 000003, to_date('2020-01-07:20:09:40','YYYY-MM-DD:HH24:MI:SS'),
	'Not quite sure about point 2 anymore after this update tho. I think they changed it all up. ');
INSERT INTO Follow_up_posts (ArtID, FID, AuthorID, Time, Content) VALUES ('xcvhhy93', '241231' , 000002, to_date('2020-01-07:22:20:17','YYYY-MM-DD:HH24:MI:SS'),
	'Yep that''s right. Trying to figure out exactly what they have done so that I can update this post real quick');

INSERT INTO isFriend (User1ID, User2ID) VALUES (000001, 000002);
INSERT INTO isFriend (User1ID, User2ID) VALUES (000001, 000009);
INSERT INTO isFriend (User1ID, User2ID) VALUES (000004, 000005);
INSERT INTO isFriend (User1ID, User2ID) VALUES (000004, 000006);
INSERT INTO isFriend (User1ID, User2ID) VALUES (000002, 000007);

INSERT INTO rates (PlayerID, GID, Rating) VALUES (000004, 0805, 1);
INSERT INTO rates (PlayerID, GID, Rating) VALUES (000005, 0200, 4);
INSERT INTO rates (PlayerID, GID, Rating) VALUES (000005, 0802, 3);
INSERT INTO rates (PlayerID, GID, Rating) VALUES (000012, 0302, 4);
INSERT INTO rates (PlayerID, GID, Rating) VALUES (000001, 1102, 2);

INSERT INTO views (PlayerID, GID, Time) VALUES (000004, 0805, to_date('2019-12-31:19:06:40','YYYY-MM-DD:HH24:MI:SS'));
INSERT INTO views (PlayerID, GID, Time) VALUES (000005, 0200, to_date('2019-12-13:20:24:36','YYYY-MM-DD:HH24:MI:SS'));
INSERT INTO views (PlayerID, GID, Time) VALUES (000005, 0802, to_date('2020-01-14:06:35:07','YYYY-MM-DD:HH24:MI:SS'));
INSERT INTO views (PlayerID, GID, Time) VALUES (000012, 0302, to_date('2020-05-04:01:35:25','YYYY-MM-DD:HH24:MI:SS'));
INSERT INTO views (PlayerID, GID, Time) VALUES (000001, 1102, to_date('2020-05-11:13:21:24','YYYY-MM-DD:HH24:MI:SS'));

INSERT INTO plays (PlayerID, GID, AccumPlayTime, CurrStage, AccumScore) VALUES (000004, 0805, 17, 1, 26);
INSERT INTO plays (PlayerID, GID, AccumPlayTime, CurrStage, AccumScore) VALUES (000005, 0200, 13, 4, 0);
INSERT INTO plays (PlayerID, GID, AccumPlayTime, CurrStage, AccumScore) VALUES (000005, 0802, 4, 5, 430);
INSERT INTO plays (PlayerID, GID, AccumPlayTime, CurrStage, AccumScore) VALUES (000012, 0302, 6, 6, 356);
INSERT INTO plays (PlayerID, GID, AccumPlayTime, CurrStage, AccumScore) VALUES (000001, 1102, 1, 1, 26);

INSERT INTO GameRecord_recordedTo (PlayerID, GID, StartTime, EndTime, Score) VALUES (000004, 0805, to_date('2019-12-31:21:08:42','YYYY-MM-DD:HH24:MI:SS'),  to_date('2019-12-31:22:22:00','YYYY-MM-DD:HH24:MI:SS'), 1);--1
INSERT INTO GameRecord_recordedTo (PlayerID, GID, StartTime, EndTime, Score) VALUES (000004, 0805, to_date('2020-01-02:13:25:33','YYYY-MM-DD:HH24:MI:SS'),  to_date('2020-01-02:23:18:42','YYYY-MM-DD:HH24:MI:SS'), 20);--10
INSERT INTO GameRecord_recordedTo (PlayerID, GID, StartTime, EndTime, Score) VALUES (000004, 0805, to_date('2020-01-02:23:56:24','YYYY-MM-DD:HH24:MI:SS'),  to_date('2020-01-02:06:18:42','YYYY-MM-DD:HH24:MI:SS'), 5);--6

INSERT INTO GameRecord_recordedTo (PlayerID, GID, StartTime, EndTime, Score) VALUES (000005, 0200, to_date('2019-12-13:21:05:47','YYYY-MM-DD:HH24:MI:SS'), to_date('2019-12-13:02:03:44','YYYY-MM-DD:HH24:MI:SS'), 0);--5
INSERT INTO GameRecord_recordedTo (PlayerID, GID, StartTime, EndTime, Score) VALUES (000005, 0200, to_date('2019-12-14:14:33:28','YYYY-MM-DD:HH24:MI:SS'), to_date('2019-12-14:22:45:01','YYYY-MM-DD:HH24:MI:SS'), 0);--8

INSERT INTO GameRecord_recordedTo (PlayerID, GID, StartTime, EndTime, Score) VALUES (000005, 0802, to_date('2020-01-14:18:35:07','YYYY-MM-DD:HH24:MI:SS'), to_date('2020-01-14:22:49:14','YYYY-MM-DD:HH24:MI:SS'), 430);--4

INSERT INTO GameRecord_recordedTo (PlayerID, GID, StartTime, EndTime, Score) VALUES (000012, 0302, to_date('2020-05-04:01:41:20','YYYY-MM-DD:HH24:MI:SS'), to_date('2020-05-04:07:58:59','YYYY-MM-DD:HH24:MI:SS'), 356);--6

INSERT INTO GameRecord_recordedTo (PlayerID, GID, StartTime, EndTime, Score) VALUES (000001, 1102, to_date('2020-05-11:13:24:11','YYYY-MM-DD:HH24:MI:SS'), to_date('2020-05-11:14:30:18','YYYY-MM-DD:HH24:MI:SS'), 26);--1

INSERT INTO Purchases_profits_detail (PlayerID, GID, Purchase_Date, PayMethod) VALUES (000004, 0805, '31-DEC-19', 'CreditCard');
INSERT INTO Purchases_profits_detail (PlayerID, GID, Purchase_Date, PayMethod) VALUES (000005, 0200, '13-DEC-19', 'PayPal');
INSERT INTO Purchases_profits_detail (PlayerID, GID, Purchase_Date, PayMethod) VALUES (000005, 0802, '14-JAN-20', 'PayPal');
INSERT INTO Purchases_profits_detail (PlayerID, GID, Purchase_Date, PayMethod) VALUES (000012, 0302, '04-MAY-20', 'CreditCard');
INSERT INTO Purchases_profits_detail (PlayerID, GID, Purchase_Date, PayMethod) VALUES (000001, 1102, '11-MAY-20', 'DebitCard');

INSERT INTO achieves (UserID, AID, Achieve_Date) VALUES (000002, '1a1', '19-SEP-19');
INSERT INTO achieves (UserID, AID, Achieve_Date) VALUES (000003, '1a1', '12-FEB-19');
INSERT INTO achieves (UserID, AID, Achieve_Date) VALUES (000008, '1a1', '02-SEP-18');
INSERT INTO achieves (UserID, AID, Achieve_Date) VALUES (000011, '1a1', '29-FEB-20');
INSERT INTO achieves (UserID, AID, Achieve_Date) VALUES (000012, '2s1', '04-MAY-20');

INSERT INTO associates (GID, AID) VALUES (0200, '3a2');--Speed Runner
INSERT INTO associates (GID, AID) VALUES (0302, '2s1');--all kill
INSERT INTO associates (GID, AID) VALUES (0301, '7f4');--king of the land
INSERT INTO associates (GID, AID) VALUES (1101, '84r');--FOB

INSERT INTO Comment_writesTo (CID, PlayerID, GID, Content, Time) VALUES ('efe1b189-ad63-4e3e', 000004, 0805,
	'It''s so bad that it''s good', to_date('2020-01-03:06:20:23','YYYY-MM-DD:HH24:MI:SS'));
INSERT INTO Comment_writesTo (CID, PlayerID, GID, Content, Time) VALUES ('990a0bcf-149b-43c4', 000005, 0200,
	'Nice as someone''s first game. I wish that the challenges are easier tho. ', to_date('2019-12-14:22:56:31','YYYY-MM-DD:HH24:MI:SS'));
INSERT INTO Comment_writesTo (CID, PlayerID, GID, Content, Time) VALUES ('4a9a6fc0-71bb-4621', 000005, 0802,
	'Nice chilling game. There is a bug at the beginning of stage 2 that you can''t drink after refilling. ', to_date('2020-01-14:22:49:14','YYYY-MM-DD:HH24:MI:SS'));
INSERT INTO Comment_writesTo (CID, PlayerID, GID, Content, Time) VALUES ('d1d93e17-fc53-49ad', 000012, 0302,
	'I was really surprised by how much I enjoyed it. This game has its own flavor while following the standard action game model, thanks to the amazing dialog design. I hope it has a sequal. ', to_date('2020-05-04:08:00:52','YYYY-MM-DD:HH24:MI:SS'));
INSERT INTO Comment_writesTo (CID, PlayerID, GID, Content, Time) VALUES ('c8a91850-dcc5-4751', 000001, 1102,
	'SUB DUDE 2 POINTS 4 U', to_date('2020-05-11:14:31:44','YYYY-MM-DD:HH24:MI:SS'));

INSERT INTO replies (UserID, GID, Time, Content) VALUES (000008, 0805, to_date('2020-01-04:12:38:56','YYYY-MM-DD:HH24:MI:SS'), 'I think so too. Thx for playing');
INSERT INTO replies (UserID, GID, Time, Content) VALUES (000002, 0200, to_date('2019-12-15:10:34:51','YYYY-MM-DD:HH24:MI:SS'), 
	'Thank you! I have made another game of the same type but less demanding. You can check out my homepage for that!');
INSERT INTO replies (UserID, GID, Time, Content) VALUES (000008, 0802, to_date('2020-01-17:02:28:04','YYYY-MM-DD:HH24:MI:SS'), 
	'Thx for telling me that. It''s fixed now. Enjoy. ');
INSERT INTO replies (UserID, GID, Time, Content) VALUES (000003, 0302, to_date('2020-05-04:16:30:07','YYYY-MM-DD:HH24:MI:SS'), 
	'Well thank you very much! I put a lot more effort into designing this one than I ever did. I am currently working on the sequal and it is going to be a 3D game. I will definitely notify you when it comes out!');
INSERT INTO replies (UserID, GID, Time, Content) VALUES (000012, 0302, to_date('2020-05-04:17:02:59','YYYY-MM-DD:HH24:MI:SS'), 
	'WOW bring the work on man. Looking forward to that. ');

INSERT INTO favorites (UserID, Type) VALUES (000001, 'Action');
INSERT INTO favorites (UserID, Type) VALUES (000001, 'RPG');
INSERT INTO favorites (UserID, Type) VALUES (000002, 'Simulation');
INSERT INTO favorites (UserID, Type) VALUES (000002, 'Sports');
INSERT INTO favorites (UserID, Type) VALUES (000002, 'Strategy');

INSERT INTO isOf (GID, Type) VALUES (0200, 'Parkour');
INSERT INTO isOf (GID, Type) VALUES (0201, 'RPG');
INSERT INTO isOf (GID, Type) VALUES (0202, 'RPG');
INSERT INTO isOf (GID, Type) VALUES (0203, 'Sports');
INSERT INTO isOf (GID, Type) VALUES (0204, 'RPG');

INSERT INTO isOf (GID, Type) VALUES (0300, 'RPG');
INSERT INTO isOf (GID, Type) VALUES (0301, 'RPG');
INSERT INTO isOf (GID, Type) VALUES (0302, 'Action');

INSERT INTO isOf (GID, Type) VALUES (0801, 'Simulation');
INSERT INTO isOf (GID, Type) VALUES (0802, 'Simulation');
INSERT INTO isOf (GID, Type) VALUES (0803, 'RPG');
INSERT INTO isOf (GID, Type) VALUES (0804, 'Simulation');
INSERT INTO isOf (GID, Type) VALUES (0805, 'Simulation');
INSERT INTO isOf (GID, Type) VALUES (0806, 'Action');
INSERT INTO isOf (GID, Type) VALUES (0807, 'RPG');

INSERT INTO isOf (GID, Type) VALUES (1100, 'Parkour');
INSERT INTO isOf (GID, Type) VALUES (1101, 'Simulation');
INSERT INTO isOf (GID, Type) VALUES (1102, 'Crafting');
INSERT INTO isOf (GID, Type) VALUES (1103, 'Simulation');

INSERT INTO mentions (ArtID, GID) VALUES ('diqw78f', 0807);
INSERT INTO mentions (ArtID, GID) VALUES ('987dshkjg', 0302);

CREATE SEQUENCE GID_generate
	START WITH 20000
  	INCREMENT BY 1
  	CACHE 5;


