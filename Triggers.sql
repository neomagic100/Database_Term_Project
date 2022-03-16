USE UniversityEvents;

-- Works when directly Adding or deleting from member_of table
-- But not when deleting from users
DELIMITER $$

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