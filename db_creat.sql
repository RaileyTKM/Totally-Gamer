CREATE TABLE UserID (
	ID		INT		 PRIMARY KEY,
	Nickname	VARCHAR(30) NOT NULL UNIQUE,
	Password	VARCHAR(30),
	Gender	VARCHAR(6),
	Birthday	DATE,
	AccCreation	DATE
);

CREATE TABLE Developer (
	ID		INT	PRIMARY KEY,
	YearsOfExp	INT,
	TPurchase	INT,
	TProfit		REAL,
FOREIGN KEY (ID) REFERENCES User (ID)
);

CREATE TABLE Player (
	ID	INT	PRIMARY KEY ,
FOREIGN KEY (ID) REFERENCES User (ID)
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
	FOREIGN KEY (CreatorID) REFERENCES User (ID)
);

CREATE TABLE ForumArticle_posts (
	ArtID		VARCHAR(10)	PRIMARY KEY,
	Forum		VARCHAR(60)	NOT NULL,
	AuthorID	INT			NOT NULL,
	Title		VARCHAR(60)	NOT NULL,
	Date		DATE,
	Time		TIME,
	Views		INT,
	Content	VARCHAR(2000)	NOT NULL,
	FOREIGN KEY (Forum) REFERENCES Forum_category_creates (Name),
	FOREIGN KEY (AuthorID) REFERENCES User (ID)
);

CREATE TABLE Follow_up_posts (
	ArtID		VARCHAR(10),
	FID		VARCHAR(10),
	AuthorID	INT			NOT NULL,
	Date		DATE,
	Time		TIME,
	Content	VARCHAR(2000)	NOT NULL,
	PRIMARY KEY (ArtID, FID),
	FOREIGN KEY (ArtID) REFERENCES ForumArticle_posts(ArtID),
	FOREIGN KEY (AuthorID) REFERENCES User (ID)
);

CREATE TABLE isFriend (
	User1ID	INT,
	User2ID	INT,
	PRIMARY KEY (User1ID, User2ID),
	FOREIGN KEY (User1ID,User2ID) REFERENCES User (ID)
);

CREATE TABLE rates (
	PlayerID	INT,
	GID		VARCHAR(8),
	Rating		INT,
	PRIMARY KEY (PlayerID, GID),
	FOREIGN KEY (PlayerID) REFERENCES Player (ID),
	FOREIGN KEY (GID) REFERENCES Game_uploads (GID)
);

CREATE TABLE views (
	PlayerID	INT,
	GID		VARCHAR(8),
	Date		DATE,
	Time		TIME,
	PRIMARY KEY (PlayerID, GID, Date, Time),
FOREIGN KEY (PlayerID) REFERENCES Player(ID),
FOREIGN KEY (GID) REFERENCES Game_uploads(GID)
);

CREATE TABLE plays (
	PlayerID		INT,
	GID			VARCHAR(8),
	AccumPlayTime	TIME,
	CurrStage		INT,
	AccumScore		INT,
	PRIMARY KEY (PlayerID, GID),
	FOREIGN KEY (PlayerID) REFERENCES Player(ID),
	FOREIGN KEY (GID) REFERENCES Game_uploads(GID)
);

CREATE TABLE GameRecord_recordedTo (
	PlayerID	INT,
	GID		VARCHAR(8),
	StartDate	DATE,
	StartTime	TIME,
	EndDate	DATE,
	EndTime	TIME,
	Score		INT,
	PRIMARY KEY (PlayerID, GID, StartDate, StartTime),
	FOREIGN KEY (PlayerID) REFERENCES Player(ID),
	FOREIGN KEY (GID) REFERENCES Game_uploads(GID)
);

CREATE TABLE PP1 (
	GID		VARCHAR(8)	PRIMARY KEY,
	DevID		INT,
	AmountProfit	REAL,
	FOREIGN KEY (GID) REFERENCES Game_uploads(GID),
	FOREIGN KEY (DevID) REFERENCES Developer (ID),
	FOREIGN KEY (AmountProfit) REFERENCES Game_uploads (Price)
);

CREATE TABLE PP2 (
	PlayerID	INT,
	GID		VARCHAR(8),
	Date		DATE,
	PayMethod	VARCHAR(10),
	PRIMARY KEY (PlayerID, GID),
	FOREIGN KEY (PlayerID) REFERENCES Player(ID),
	FOREIGN KEY (GID) REFERENCES Game_uploads(GID)
);

CREATE TABLE achieves (
	PlayerID	INT,
	AID		VARCHAR(10),
	Date		DATE,
	PRIMARY KEY (PlayerID, AID),
	FOREIGN KEY (PlayerID) REFERENCES Player(ID),
	FOREIGN KEY (AID) REFERENCES Achievement(AID)
);

CREATE TABLE associates (
	GID	VARCHAR(8),
	AID	VARCHAR(10),
	PRIMARY KEY (GID, AID),
	FOREIGN KEY (GID) REFERENCES  Game_uploads (GID),
	FOREIGN KEY (AID) REFERENCES Achievement(AID)
);

CREATE TABLE Comment_writesTo (
	CID		VARCHAR(20) PRIMARY KEY,
	PlayerID	INT		 NOT NULL DEFAULT 0,
	GID		VARCHAR(8)	 NOT NULL,
	Content	VARCHAR(2000)	NOT NULL,
	Date		DATE,
	Time		TIME,
	FOREIGN KEY (GID) 
		REFERENCES Game_uploads (GID)
		ON DELETE CASCADE,
	FOREIGN KEY (PlayerID) 
		REFERENCES Player(ID)
		ON DELETE SET DEFAULT
);

CREATE TABLE replies (
	UserID		INT,
	GID		VARCHAR(8),
	Content	VARCHAR(2000),
	PRIMARY KEY (UserID, GID),
	FOREIGN KEY (GID) REFERENCES Game_uploads(GID),
	FOREIGN KEY (UserID) REFERENCES User (ID)
);

CREATE TABLE favorites (
	UserID		INT,
	Type		VARCHAR(20),
	PRIMARY KEY (UserID, Type),
	FOREIGN KEY (Type) REFERENCES Type (Name),
	FOREIGN KEY (UserID) REFERENCES User (ID)
);

CREATE TABLE isOf (
	GID		VARCHAR(8),
	Type		VARCHAR(20),
	PRIMARY KEY (GID, Type),
	FOREIGN KEY (Type) REFERENCES Type (Name),
	FOREIGN KEY (UserID) REFERENCES User (ID)
);

CREATE TABLE mentions (
	ArtID		VARCHAR(10),
	GID		VARCHAR(8),
	FOREIGN KEY (ArtID) REFERENCES ForumArticle_posts (Name),
	FOREIGN KEY (GID) REFERENCES Game_uploads(Name)
)

INSERT INTO UserID (ID, Nickname, Password, Gender, Birthday, AccCreation) VALUES (12, 'Gorini', 'Ra345', 'Male', '2000-03-17', '2020-08-01');
INSERT INTO UserID (ID, Nickname, Password, Gender, Birthday, AccCreation) VALUES (17, 'Rosetti', '1234567Abc', 'Female', '1996-06-06', '2019-07-22');
INSERT INTO UserID (ID, Nickname, Password, Gender, Birthday, AccCreation) VALUES (18, 'Creeber', 'Co43245', 'Other', '1998-11-17', '2019-04-11');
INSERT INTO UserID (ID, Nickname, Password, Gender, Birthday, AccCreation) VALUES (22, 'Balnaves', 'Le5625', 'Female', '1991-11-11', '2019-11-21');
INSERT INTO UserID (ID, Nickname, Password, Gender, Birthday, AccCreation) VALUES (35, 'Maestrini', 'Le2345', 'Female', '1992-07-21', '2019-11-27');
INSERT INTO UserID (ID, Nickname, Password, Gender, Birthday, AccCreation) VALUES (36, 'Marry', 'abcde', 'Male', '1999-03-03', '2019-11-27');

INSERT INTO Developer (ID, YearsOfExp, TPurchase, TProfit) VALUES (12, 2, 12, 33.00);
INSERT INTO Developer (ID, YearsOfExp, TPurchase, TProfit) VALUES (17, 1, 3, 2.00);
INSERT INTO Developer (ID, YearsOfExp, TPurchase, TProfit) VALUES (18, 2, 27, 47.30);

INSERT INTO Player (ID) VALUES (22);
INSERT INTO Player (ID) VALUES (35);
INSERT INTO Player (ID) VALUES (36);










