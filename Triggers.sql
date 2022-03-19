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
		SIGNAL sqlstate '45000';
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