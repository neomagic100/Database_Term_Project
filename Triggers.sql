USE UniversityEvents;

DELIMITER $$

-- Trigger to ensure times of events don't conflict
CREATE TRIGGER Event_Time_Check
	BEFORE INSERT ON Events
FOR EACH ROW
	BEGIN
	IF (EXISTS (
		SELECT *
        FROM Events E
        WHERE (E.lid = NEW.lid) AND (E.event_date = NEW.event_date) AND
			((NEW.event_end - E.event_start) > 0) AND ((E.event_end - NEW.event_start > 0))
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

-- Insert member with min(uid) into admins when member total is 5
CREATE TRIGGER RSOMemberToAdmin
	BEFORE INSERT ON Member_of
FOR EACH ROW 
	BEGIN
    IF ((SELECT COUNT(*) 
		FROM Member_of M
        WHERE M.rso_id = NEW.rso_id) = 4)
	THEN
		INSERT INTO Admins (uid, user_id, user_pass, user_name, email)
        SELECT U.uid, user_id, user_pass, user_name, email
        FROM Users U
        INNER JOIN Member_of M USING (uid)
        WHERE U.uid = (SELECT MIN(uid) FROM Member_of)
        LIMIT 1;
        
        SELECT LAST_INSERT_ID() into @id;
        
        INSERT INTO Owns (uid, rso_id) VALUES (@id, NEW.rso_id);
	END IF;
END $$

DELIMITER ;