USE UniversityEvents;

-- University Table
CREATE TABLE IF NOT EXISTS University (
	uni_id INTEGER auto_increment NOT NULL,
    uni_name VARCHAR(255),
    location VARCHAR(255),
    num_students INTEGER,
    descrip VARCHAR(1023),
    PRIMARY KEY(uni_id)
);

-- Users superclass
-- Store passwords as MD5('[password]')
--      i.e. INSERT INTO Users (user_id, user_pass) values ('michael', MD5('qwerty'));
CREATE TABLE IF NOT EXISTS Users (
	uid INTEGER auto_increment NOT NULL,
    user_id VARCHAR(24) NOT NULL,
    user_pass VARCHAR(260) NOT NULL,
    user_name VARCHAR(255),
    email VARCHAR(255),
    PRIMARY KEY(uid),
    UNIQUE KEY (user_id)
);

-- Admins subclass of Users
CREATE TABLE IF NOT EXISTS Admins (
	uid INTEGER NOT NULL,
    user_id VARCHAR(24) NOT NULL,
    user_pass VARCHAR(260) NOT NULL,
    user_name VARCHAR(255),
    email VARCHAR(255),
    PRIMARY KEY(uid),
    FOREIGN KEY(uid) REFERENCES Users(uid)
		ON DELETE CASCADE
        ON UPDATE CASCADE
);

-- Admins subclass of Users
CREATE TABLE IF NOT EXISTS Superusers (
	uid INTEGER NOT NULL,
    user_id VARCHAR(24) NOT NULL,
    user_pass VARCHAR(260) NOT NULL,
    user_name VARCHAR(255),
    email VARCHAR(255),
    PRIMARY KEY(uid),
    FOREIGN KEY(uid) REFERENCES Users(uid)
		ON DELETE CASCADE
        ON UPDATE CASCADE
);

-- RSO table, have primary key as autoincrementing integer
CREATE TABLE IF NOT EXISTS RSOs (
	rso_id INTEGER auto_increment NOT NULL,
    rname VARCHAR(255),
    rtype VARCHAR(255),
    is_active BIT DEFAULT 0,
    established DATETIME DEFAULT NOW(),
    PRIMARY KEY(rso_id)
);

-- Location table
CREATE TABLE IF NOT EXISTS Location (
	lid INTEGER auto_increment NOT NULL,
	lname VARCHAR(255),
	latitude REAL,
	longitude REAL,
    address VARCHAR(255),
	PRIMARY KEY(lid),
    UNIQUE KEY (latitude, longitude)
);

-- Event parent table
CREATE TABLE IF NOT EXISTS Events (
	event_id INTEGER auto_increment NOT NULL,
    event_date DATE,
    event_start TIME,
    event_end TIME,
    lid INTEGER NOT NULL,
    established TIMESTAMP DEFAULT NOW(),
    PRIMARY KEY (event_id),
    FOREIGN KEY (lid) REFERENCES Location(lid)
	 	ON DELETE CASCADE
        ON UPDATE CASCADE
);

-- Public Events table
CREATE TABLE IF NOT EXISTS PublicEvents (
	event_id INTEGER NOT NULL,
    event_name VARCHAR(255),
    event_category VARCHAR(255),
    is_published BIT DEFAULT 0,
    descrip VARCHAR(1023),
    PRIMARY KEY(event_id),
    INDEX par_ind (event_id),
    FOREIGN KEY (event_id) REFERENCES Events(event_id)
		ON UPDATE CASCADE
        ON DELETE CASCADE
);

-- Private Events table
CREATE TABLE IF NOT EXISTS PrivateEvents (
	event_id INTEGER NOT NULL,
    event_name VARCHAR(255),
    event_category VARCHAR(255),
    is_published BIT DEFAULT 0,
    descrip VARCHAR(1023),
    PRIMARY KEY(event_id),
    INDEX par_ind (event_id),
    FOREIGN KEY (event_id) REFERENCES Events(event_id)
		ON UPDATE CASCADE
        ON DELETE CASCADE
);

-- RSO Events table
CREATE TABLE IF NOT EXISTS RSOEvents (
	event_id INTEGER NOT NULL,
    event_name VARCHAR(255),
    event_category VARCHAR(255),
    is_published BIT DEFAULT 0,
    descrip VARCHAR(1023),
    PRIMARY KEY(event_id),
    INDEX par_ind (event_id),
    FOREIGN KEY (event_id) REFERENCES Events(event_id)
		ON UPDATE CASCADE
        ON DELETE CASCADE
);
