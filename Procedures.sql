USE UniversityEvents;

-- Procedure to count the members in a RSO

DELIMITER $$

CREATE PROCEDURE memberCount (IN rso_id INTEGER, OUT num INTEGER)
	BEGIN
		SELECT COUNT(*) INTO num FROM UniversityEvents.Member_of
        WHERE Member_of.rso_id = rso_id;
	END$$
    
DELIMITER ;
