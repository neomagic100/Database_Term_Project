USE UniversityEvents;

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
    FROM PrivateEvents E, Creates_PrivateEvent C
    INNER JOIN Events USING (event_id)
    WHERE is_published = 1
    ORDER BY Events.event_date, Events.event_start;

-- -- View to see RSO events, date and time formatted, ordered chronologically
CREATE VIEW RSOEventView AS
    SELECT E.event_id, event_name, C.rso_id, descrip, DATE_FORMAT(event_date, '%a, %b %d, %Y') AS event_date,
		TIME_FORMAT(event_start, '%h:%i %p') AS event_start, 
        TIME_FORMAT(event_end, '%h:%i %p') AS event_end
    FROM RSOEvents E, Creates_RSOEvent C
    INNER JOIN Events USING (event_id)
    WHERE E.is_published = 1 AND (
		SELECT R.rso_id
        FROM RSOs R
        WHERE R.rso_id = C.rso_id AND R.is_active = 1)
    ORDER BY Events.event_date, Events.event_start;

CREATE VIEW ActiveRSOs AS
	SELECT U.uid, R.rname, R.rtype
    FROM RSOs R, Member_of M, Users U
    WHERE R.rso_id = M.rso_id AND U.uid = M.uid AND R.is_active = 1
    ORDER BY R.rname;
    
CREATE VIEW InactiveRSOs AS
	SELECT U.uid, R.rname, R.rtype
    FROM RSOs R, Member_of M, Users U
    WHERE R.rso_id = M.rso_id AND U.uid = M.uid AND R.is_active = 0
    ORDER BY R.rname;

