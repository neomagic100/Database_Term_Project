USE UniversityEvents;

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
