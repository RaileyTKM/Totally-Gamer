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
drop table Purchases_profits_records cascade constraints;
drop table Purchases_profits_detail cascade constraints;
drop table achieves cascade constraints;
drop table associates cascade constraints;
drop table Comment_writesTo cascade constraints;
drop table replies cascade constraints;
drop table favorites cascade constraints;
drop table isOf cascade constraints;
drop table mentions cascade constraints;

CREATE TABLE UserID (
	ID		INT		 PRIMARY KEY,
	Nickname	VARCHAR(30) NOT NULL UNIQUE,
	Password	VARCHAR(30),
	Gender	VARCHAR(6),
	Birthday	DATE,
	AccCreation	DATE,
	role VARCHAR(10)
);

CREATE TABLE Developer (
	ID		INT	PRIMARY KEY,
	YearsOfExp	INT,
	TPurchase	INT,
	TProfit		REAL,
FOREIGN KEY (ID) REFERENCES UserID (ID)
);

CREATE TABLE Player (
	ID	INT	PRIMARY KEY ,
FOREIGN KEY (ID) REFERENCES UserID (ID)
);

CREATE TABLE Game_uploads (
	GID		INT			PRIMARY KEY,
	DevID		INT			NOT NULL,
	Name 		VARCHAR(40) 	NOT NULL 	UNIQUE,
	Rating		REAL,
	Price		REAL,
	Cover		BLOB,
	UploadDate	DATE,
FOREIGN KEY (DevID) REFERENCES Developer (ID)
);

CREATE TABLE Game_rate (
	Rating		REAL,
	Rank		VARCHAR(10)
);

CREATE TABLE Achievement (
	AID	VARCHAR(10)	PRIMARY KEY,
	Name	VARCHAR(20)	NOT NULL,
	Badge	BLOB
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
	GID		INT,
	Rating		INT,
	PRIMARY KEY (PlayerID, GID),
	FOREIGN KEY (PlayerID) REFERENCES Player (ID),
	FOREIGN KEY (GID) REFERENCES Game_uploads (GID)
);

CREATE TABLE views (
	PlayerID	INT,
	GID		INT,
	Time		TIMESTAMP(2),
	PRIMARY KEY (PlayerID, GID, Time),
FOREIGN KEY (PlayerID) REFERENCES Player(ID),
FOREIGN KEY (GID) REFERENCES Game_uploads(GID)
);

CREATE TABLE plays (
	PlayerID		INT,
	GID			INT,
	AccumPlayTime	INT,
	CurrStage		INT,
	AccumScore		INT,
	PRIMARY KEY (PlayerID, GID),
	FOREIGN KEY (PlayerID) REFERENCES Player(ID),
	FOREIGN KEY (GID) REFERENCES Game_uploads(GID)
);

CREATE TABLE GameRecord_recordedTo (
	PlayerID	INT,
	GID		INT,
	Start_Time		TIMESTAMP(2),
	End_Time		TIMESTAMP(2),
	Score		INT,
	PRIMARY KEY (PlayerID, GID, Start_Time),
	FOREIGN KEY (PlayerID) REFERENCES Player(ID),
	FOREIGN KEY (GID) REFERENCES Game_uploads(GID)
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
	GID		INT,
	Purchase_Date		DATE,
	PayMethod	VARCHAR(10),
	PRIMARY KEY (PlayerID, GID),
	FOREIGN KEY (PlayerID) REFERENCES Player(ID),
	FOREIGN KEY (GID) REFERENCES Game_uploads(GID)
);

CREATE TABLE achieves (
	PlayerID	INT,
	AID		VARCHAR(10),
	Achieve_Date		DATE,
	PRIMARY KEY (PlayerID, AID),
	FOREIGN KEY (PlayerID) REFERENCES Player(ID),
	FOREIGN KEY (AID) REFERENCES Achievement(AID)
);

CREATE TABLE associates (
	GID	INT,
	AID	VARCHAR(10),
	PRIMARY KEY (GID, AID),
	FOREIGN KEY (GID) REFERENCES  Game_uploads (GID),
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
	GID		INT,
	Content	VARCHAR(2000),
	PRIMARY KEY (UserID, GID),
	FOREIGN KEY (GID) REFERENCES Game_uploads(GID),
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
	FOREIGN KEY (Type) REFERENCES Type (Name)
);

CREATE TABLE mentions (
	ArtID		VARCHAR(10),
	GID		INT,
	FOREIGN KEY (ArtID) REFERENCES ForumArticle_posts (Name),
	FOREIGN KEY (GID) REFERENCES Game_uploads(Name)
)

INSERT INTO UserID (ID, Nickname, Password, Gender, Birthday, AccCreation,role) VALUES (12, 'Gorini', 'Ra345', 'Male', '16-Mar-2000', '01-Aug-2000','DEVELOPER');
INSERT INTO UserID (ID, Nickname, Password, Gender, Birthday, AccCreation,role) VALUES (17, 'Rosetti', '1234567Abc', 'Female', '06-Jun-1996', '22-Jul-2019','DEVELOPER');
INSERT INTO UserID (ID, Nickname, Password, Gender, Birthday, AccCreation,role) VALUES (18, 'Creeber', 'Co43245', 'Other', '17-Nov-1998', '11-Apr-2019','DEVELOPER');
INSERT INTO UserID (ID, Nickname, Password, Gender, Birthday, AccCreation,role) VALUES (22, 'Balnaves', 'Le5625', 'Female', '11-Nov-1991', '21-Nov-2019','PLAYER');
INSERT INTO UserID (ID, Nickname, Password, Gender, Birthday, AccCreation,role) VALUES (35, 'Maestrini', 'Le2345', 'Female', '21-Jul-1992', '27-Nov-2019','PLAYER');
INSERT INTO UserID (ID, Nickname, Password, Gender, Birthday, AccCreation,role) VALUES (36, 'Marry', 'abcde', 'Male', '03-Mar-1999', '28-Nov-2019','PLAYER');

INSERT INTO Developer (ID, YearsOfExp, TPurchase, TProfit) VALUES (12, 2, 12, 33.00);
INSERT INTO Developer (ID, YearsOfExp, TPurchase, TProfit) VALUES (17, 1, 3, 2.00);
INSERT INTO Developer (ID, YearsOfExp, TPurchase, TProfit) VALUES (18, 2, 27, 47.30);

INSERT INTO Player (ID) VALUES (22);
INSERT INTO Player (ID) VALUES (35);
INSERT INTO Player (ID) VALUES (36);

