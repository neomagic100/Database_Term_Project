USE UniversityEvents;

-- ENTITY TABLES

-- University Table
CREATE TABLE IF NOT EXISTS University (
	uni_id INTEGER auto_increment NOT NULL,
    uni_name VARCHAR(255) NOT NULL,
    location VARCHAR(255),
    num_students INTEGER,
    descrip VARCHAR(1023),
    PRIMARY KEY(uni_id)
);

-- Users superclass
-- Store passwords hashed
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
-- Store passwords hashed
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

-- Super Admins subclass of Users
-- Store passwords hashed
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
    is_published BIT DEFAULT 1,
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
    is_published BIT DEFAULT 1,
    descrip VARCHAR(1023),
    PRIMARY KEY(event_id),
    INDEX par_ind (event_id),
    FOREIGN KEY (event_id) REFERENCES Events(event_id)
		ON UPDATE CASCADE
        ON DELETE CASCADE
);


-- RELATIONAL TABLES

-- User or Admin Affiliated with University
CREATE TABLE IF NOT EXISTS Affiliated_with (
    uni_id INTEGER,
    uid INTEGER,
    PRIMARY KEY (uni_id, uid),
    FOREIGN KEY (uni_id) REFERENCES University(uni_id)
		ON DELETE CASCADE
        ON UPDATE CASCADE,
    FOREIGN KEY (uid) REFERENCES Users(uid)
		ON DELETE CASCADE
        ON UPDATE CASCADE
);

-- User member of RSO
CREATE TABLE IF NOT EXISTS Member_of (
	uid INTEGER,
    rso_id INTEGER,
    PRIMARY KEY (uid, rso_id),
    FOREIGN KEY (uid) REFERENCES Users(uid)
		ON DELETE CASCADE
        ON UPDATE CASCADE,
	FOREIGN KEY (rso_id) REFERENCES RSOs(rso_id)
		ON DELETE CASCADE
        ON UPDATE CASCADE
);

-- Admin Owns RSO
CREATE TABLE IF NOT EXISTS Owns (
	uid INTEGER,
    rso_id INTEGER UNIQUE,
    PRIMARY KEY (uid, rso_id),
    FOREIGN KEY (uid) REFERENCES Admins(uid)
		ON DELETE CASCADE
        ON UPDATE CASCADE,
	FOREIGN KEY (rso_id) REFERENCES RSOs(rso_id)
		ON DELETE CASCADE
        ON UPDATE CASCADE
);

-- Admin creates RSO Event
CREATE TABLE IF NOT EXISTS Creates_RSOEvent (
    rso_id INTEGER,
    event_id INTEGER,
    PRIMARY KEY (rso_id, event_id),
	FOREIGN KEY (rso_id) REFERENCES RSOs(rso_id)
		ON DELETE CASCADE
        ON UPDATE CASCADE,
	FOREIGN KEY (event_id) REFERENCES RSOEvents(event_id)
		ON DELETE CASCADE
        ON UPDATE CASCADE
);

-- create private event
CREATE TABLE IF NOT EXISTS Creates_PrivateEvent (
	uid INTEGER,
    event_id INTEGER,
    uni_id INTEGER,
    PRIMARY KEY (uni_id, event_id),
    FOREIGN KEY (uid) REFERENCES Users(uid)
        ON UPDATE CASCADE,
	FOREIGN KEY (event_id) REFERENCES PrivateEvents(event_id)
		ON DELETE CASCADE
        ON UPDATE CASCADE,
	FOREIGN KEY (uni_id) REFERENCES University(uni_id)
		ON DELETE CASCADE
        ON UPDATE CASCADE
);

-- create public event
CREATE TABLE IF NOT EXISTS Creates_PublicEvent (
	uid INTEGER,
    event_id INTEGER,
	PRIMARY KEY (uid, event_id),
    FOREIGN KEY (uid) REFERENCES Superusers(uid)
        ON UPDATE CASCADE,
	FOREIGN KEY (event_id) REFERENCES PublicEvents(event_id)
		ON DELETE CASCADE
        ON UPDATE CASCADE
);

-- User comments on Event
CREATE TABLE IF NOT EXISTS Comments (
	uid INTEGER,
    event_id INTEGER,
    timestmp TIMESTAMP DEFAULT NOW(),
    cmnt VARCHAR(1023),
    rating INTEGER,
    PRIMARY KEY (uid, event_id, comment_id),
    FOREIGN KEY (uid) REFERENCES Users(uid)
		ON DELETE CASCADE
        ON UPDATE CASCADE,
    FOREIGN KEY (event_id) REFERENCES Events(event_id)
		ON DELETE CASCADE
        ON UPDATE CASCADE
);

-- creates uni profile
CREATE TABLE IF NOT EXISTS Creates_Profile (
	uid INTEGER,
    uni_id INTEGER,
    PRIMARY KEY (uid, uni_id),
    FOREIGN KEY (uid) REFERENCES Superusers(uid)
        ON UPDATE CASCADE,
    FOREIGN KEY (uni_id) REFERENCES University(uni_id)
		ON DELETE CASCADE
        ON UPDATE CASCADE
);

-- Located at table for events (public, private, rso)
-- May not be used
CREATE TABLE IF NOT EXISTS Located_at (
    event_id INTEGER,
    lid INTEGER,
    PRIMARY KEY (event_id , lid),
    FOREIGN KEY (event_id)
        REFERENCES Events (event_id)
        ON DELETE CASCADE ON UPDATE CASCADE,
    FOREIGN KEY (lid)
        REFERENCES Location (lid)
        ON DELETE CASCADE ON UPDATE CASCADE
);


-- TRIGGERS


DELIMITER $$

-- Trigger to prevent admin from leaving RSO
CREATE TRIGGER Prevent_Admin_Leaving
	BEFORE DELETE ON Member_of
FOR EACH ROW
	IF (EXISTS (
		SELECT *
        FROM Owns O
        WHERE (OLD.uid = O.uid) AND (OLD.rso_id = O.rso_id)
        )
	)
    THEN
		SIGNAL sqlstate '45000'
        SET MESSAGE_TEXT = 'Admin cannot leave an RSO they own';
	END IF;
END$$

-- Trigger to ensure times of events don't conflict
CREATE TRIGGER Event_Time_Check
	BEFORE INSERT ON Events
FOR EACH ROW
	BEGIN
	IF (EXISTS (
		SELECT *
        FROM Events E
        WHERE (E.lid = NEW.lid) AND (E.event_date = NEW.event_date) AND
			((NEW.event_end - E.event_start) >= 0) AND ((E.event_end - NEW.event_start >= 0))
		)
	)
    THEN
		SIGNAL sqlstate '45000'
        SET MESSAGE_TEXT = 'Time Conflict';
    END IF;
END$$
    
-- Trigger to delete a user in Member_of relation if the user is deleted
CREATE TRIGGER UserDelinRSO
	BEFORE DELETE ON Users
FOR EACH ROW
	BEGIN
    IF (SELECT uid
		FROM Member_of
        WHERE Member_of.uid = OLD.uid)
	THEN
		DELETE FROM Member_of
		WHERE Member_of.uid = OLD.uid;
	END IF;
END$$

-- Trigger to update an RSO when a user is added to Member_of
CREATE TRIGGER RSOStatusUpdateAdd
	AFTER INSERT ON Member_of
FOR EACH ROW 
	BEGIN
    IF ((SELECT COUNT(*) 
		FROM Member_of M
        WHERE M.rso_id = NEW.rso_id) > 4)
	THEN
		UPDATE RSOs
        SET RSOs.is_active = 1
        WHERE RSOs.rso_id = NEW.rso_id;
        
	ELSEIF ((SELECT COUNT(*) 
		FROM Member_of M
        WHERE M.rso_id = NEW.rso_id) < 5)
	THEN
		UPDATE RSOs
        SET RSOs.is_active = 0
        WHERE RSOs.rso_id = NEW.rso_id;
	END IF;
END$$ 

-- Trigger to update an RSO when a user is deleted from Member_of
CREATE TRIGGER RSOStatusUpdateDel
	AFTER DELETE ON Member_of
FOR EACH ROW 
	BEGIN
    IF ((SELECT COUNT(*) 
		FROM Member_of M
        WHERE M.rso_id = OLD.rso_id) < 5)
	THEN
		UPDATE RSOs
        SET RSOs.is_active = 0
        WHERE RSOs.rso_id = OLD.rso_id;
        
	ELSEIF ((SELECT COUNT(*) 
		FROM Member_of M
        WHERE M.rso_id = OLD.rso_id) > 4)
	THEN
		UPDATE RSOs
        SET RSOs.is_active = 1
        WHERE RSOs.rso_id = OLD.rso_id;
	END IF;
END$$ 

DELIMITER ;


-- PROCEDURES


DELIMITER $$

-- Add a User to Users table
-- IN: user_id, user_pass, user_name, email
-- OUT: uid (auto)
CREATE PROCEDURE addUser (IN user_id VARCHAR(255), IN user_pass VARCHAR(255), 
	IN user_name VARCHAR(255), IN email VARCHAR(255), OUT id INTEGER)
	BEGIN
		INSERT INTO Users (user_id, user_pass, user_name, email) values 
			(user_id, user_pass, user_name, email);
        SELECT LAST_INSERT_ID() INTO id;
	END$$

-- Add an Admin to Admins table
-- IN: user_id, user_pass, user_name, email
CREATE PROCEDURE addAdmin (IN user_id VARCHAR(255), IN user_pass VARCHAR(255), 
		IN user_name VARCHAR(255), IN email VARCHAR(255))
	BEGIN
		CALL addUser(user_id, user_pass, user_name, email, @temp_id);
        INSERT INTO Admins
        SELECT * FROM Users U
		WHERE U.uid = @temp_id;
    END$$

-- Add a Superuser to Superusers table
-- IN: user_id, user_pass, user_name, email
CREATE PROCEDURE addSuperuser (IN user_id VARCHAR(255), IN user_pass VARCHAR(255), 
		IN user_name VARCHAR(255), IN email VARCHAR(255))
	BEGIN
		CALL addUser(user_id, user_pass, user_name, email, @temp_id);
        INSERT INTO Superusers
        SELECT * FROM Users U
		WHERE U.uid = @temp_id;
    END$$

-- Add an Event to Event Super Table
-- In: date, start_time, end_time, lid
-- Out: event_id
CREATE PROCEDURE addEvent (IN edate DATE, IN estart TIME, IN eend TIME, IN lid INTEGER, OUT id INTEGER)
	BEGIN
		INSERT INTO Events (event_date, event_start, event_end, lid) values (edate, estart, eend, lid);
        SELECT LAST_INSERT_ID() INTO id;
	END$$

-- Add an Event to PublicEvents
-- In: event_name, category, description, date, start_time, end_time, lid
CREATE PROCEDURE addPublicEvent (IN event_name VARCHAR(255), IN categ VARCHAR(255), IN descrip VARCHAR(1023),
				IN edate DATE, IN estart TIME, IN eend TIME, IN lid INTEGER)
	BEGIN
		CALL addEvent(edate, estart, eend, lid, @id);
        INSERT INTO PublicEvents (event_id, event_name, event_category, descrip) values (@id, event_name, categ, descrip);
	END$$

-- Add an Event to PrivateEvents
-- In: event_name, category, description, date, start_time, end_time, lid
CREATE PROCEDURE addPrivateEvent (IN event_name VARCHAR(255), IN categ VARCHAR(255), IN descrip VARCHAR(1023),
				IN edate DATE, IN estart TIME, IN eend TIME, IN lid INTEGER)
	BEGIN
		CALL addEvent(edate, estart, eend, lid, @id);
        INSERT INTO PrivateEvents (event_id, event_name, event_category, descrip) values (@id, event_name, categ, descrip);
	END$$
    
-- Add an Event to RsoEvents
-- In: event_name, category, description, date, start_time, end_time, lid
CREATE PROCEDURE addRSOEvent (IN event_name VARCHAR(255), IN categ VARCHAR(255), IN descrip VARCHAR(1023),
				IN edate DATE, IN estart TIME, IN eend TIME, IN lid INTEGER)
	BEGIN
		CALL addEvent(edate, estart, eend, lid, @id);
        INSERT INTO RSOEvents (event_id, event_name, event_category, descrip)  values (@id, event_name, categ, descrip);
	END$$
    
DELIMITER ;


-- VIEWS


-- Dates formatted as Wed, Mar 31, 2022
-- Times formatted as 11:46 AM

-- View to see public events, date and time formatted, ordered chronologically
 CREATE VIEW PublicEventView AS
	SELECT event_id, event_name, descrip, DATE_FORMAT(event_date, '%a, %b %d, %Y') AS event_date,
		TIME_FORMAT(event_start, '%h:%i %p') AS event_start, 
        TIME_FORMAT(event_end, '%h:%i %p') AS event_end
    FROM PublicEvents
    INNER JOIN Events USING (event_id)
    WHERE is_published = 1
    ORDER BY Events.event_date, Events.event_start;

-- View to see private events, date and time formatted, ordered chronologically
CREATE VIEW PrivateEventView AS
    SELECT E.event_id, event_name, C.uni_id, descrip, DATE_FORMAT(event_date, '%a, %b %d, %Y') AS event_date,
		TIME_FORMAT(event_start, '%h:%i %p') AS event_start, 
        TIME_FORMAT(event_end, '%h:%i %p') AS event_end
    FROM PrivateEvents E
    INNER JOIN Events USING (event_id)
    INNER JOIN Creates_PrivateEvent C USING (event_id)
    WHERE is_published = 1
    ORDER BY Events.event_date, Events.event_start;

-- -- View to see RSO events, date and time formatted, ordered chronologically
CREATE VIEW RSOEventView AS
    SELECT DISTINCT
        (RE.event_id),
        RE.event_name,
        C.rso_id,
        RE.descrip,
        DATE_FORMAT(E.event_date, '%a, %b %d, %Y') AS event_date,
        TIME_FORMAT(E.event_start, '%h:%i %p') AS event_start,
        TIME_FORMAT(E.event_end, '%h:%i %p') AS event_end
    FROM
        Events E
            INNER JOIN
        RSOEvents RE USING (event_id)
            INNER JOIN
        Creates_RSOEvent C USING (event_id)
    WHERE
        RE.is_published = 1
            AND (SELECT DISTINCT
                R.rso_id
            FROM
                RSOs R
            WHERE
                R.rso_id = C.rso_id AND R.is_active = 1);

CREATE VIEW ActiveRSOs AS
	SELECT U.uid, R.rso_id, R.rname, R.rtype
    FROM RSOs R, Member_of M, Users U
    WHERE R.rso_id = M.rso_id AND U.uid = M.uid AND R.is_active = 1
    ORDER BY R.rname;
    
CREATE VIEW InactiveRSOs AS
	SELECT U.uid, R.rso_id, R.rname, R.rtype
    FROM RSOs R, Member_of M, Users U
    WHERE R.rso_id = M.rso_id AND U.uid = M.uid AND R.is_active = 0
    ORDER BY R.rname;