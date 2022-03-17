USE UniversityEvents;
DELIMITER $$

-- Count the number of members in an RSO
CREATE PROCEDURE memberCount (IN rso_id INTEGER, OUT num INTEGER)
	BEGIN
		SELECT COUNT(*) INTO num FROM UniversityEvents.Member_of
        WHERE Member_of.rso_id = rso_id;
	END$$
    
-- Add a User to Users table
-- IN: user_name, user_pass
-- OUT: uid (auto)
CREATE PROCEDURE addUser (IN user_id VARCHAR(255), IN user_pass VARCHAR(255), OUT id INTEGER)
	BEGIN
		INSERT INTO Users values (0, user_id, user_pass);
        SELECT LAST_INSERT_ID() INTO id;
	END$$

-- Add an Admin to Admins table
-- IN: user_name, user_pass
CREATE PROCEDURE addAdmin (IN user_id VARCHAR(255), IN user_pass VARCHAR(255))
	BEGIN
		CALL addUser(user_id, user_pass, @temp_id);
        INSERT INTO Admins values (@temp_id, user_id, user_pass);
    END$$

-- Add a Superuser to Superusers table
-- IN: user_name, user_pass
CREATE PROCEDURE addSuperuser (IN user_id VARCHAR(255), IN user_pass VARCHAR(255))
	BEGIN
		CALL addUser(user_id, user_pass, @temp_id);
        INSERT INTO Superusers values (@temp_id, user_id, user_pass);
    END$$
    
DELIMITER ;
