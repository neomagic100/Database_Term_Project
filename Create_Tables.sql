USE UniversityEvents;

-- University Table
CREATE TABLE University (
	uni_id INTEGER auto_increment NOT NULL,
    uni_name VARCHAR(255),
    location VARCHAR(255),
    num_students INTEGER,
    descrip VARCHAR(1023),
    PRIMARY KEY(uni_id)
);

-- Users superclass
CREATE TABLE Users (
	uid INTEGER auto_increment NOT NULL,
    user_id VARCHAR(255),
    user_pass VARCHAR(255),
    PRIMARY KEY(uid)
);

-- Admins subclass of Users
CREATE TABLE Admins (
	uid INTEGER NOT NULL,
    user_id VARCHAR(255),
    user_pass VARCHAR(255),
    PRIMARY KEY(uid),
    FOREIGN KEY(uid) REFERENCES Users(uid)
		ON DELETE CASCADE
        ON UPDATE CASCADE
);

-- Admins subclass of Users
CREATE TABLE Superusers (
	uid INTEGER NOT NULL,
    user_id VARCHAR(255),
    user_pass VARCHAR(255),
    PRIMARY KEY(uid),
    FOREIGN KEY(uid) REFERENCES Users(uid)
		ON DELETE CASCADE
        ON UPDATE CASCADE
);

-- RSO table, have primary key as autoincrementing integer
CREATE TABLE RSOs (
	rso_id INTEGER auto_increment NOT NULL,
    rname VARCHAR(255),
    rtype VARCHAR(255),
    is_active VARBINARY(3),
    PRIMARY KEY(rso_id)
);

-- Events table
CREATE TABLE Events (
	event_id INTEGER auto_increment NOT NULL,
    event_name VARCHAR(255),
    event_category VARCHAR(255),
    view_type VARCHAR(255),
    is_published VARBINARY(3),
    descrip VARCHAR(1023),
    PRIMARY KEY(event_id)
);
-- Location table
CREATE TABLE location (
	lid INTEGER audo_increment NOT NULL,
	lname VARCHAR(255),
	latitude REAL,
	longitude REAL,
	PRIMARY KEY(lid)
);
