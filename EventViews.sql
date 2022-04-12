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
    FROM PrivateEvents E
    INNER JOIN Events USING (event_id)
    INNER JOIN Creates_PrivateEvent C USING (event_id)
    WHERE is_published = 1
    ORDER BY Events.event_date, Events.event_start;

-- -- View to see RSO events, date and time formatted, ordered chronologically
CREATE VIEW RSOEventView AS
    SELECT DISTINCT(RE.event_id), RE.event_name, C.rso_id, RE.descrip, DATE_FORMAT(E.event_date, '%a, %b %d, %Y') AS event_date,
		TIME_FORMAT(E.event_start, '%h:%i %p') AS event_start, 
        TIME_FORMAT(E.event_end, '%h:%i %p') AS event_end
    FROM Events E
    INNER JOIN RSOEvents RE USING (event_id)
    INNER JOIN Creates_RSOEvent C USING (event_id)
    WHERE RE.is_published = 1 AND (
		SELECT DISTINCT R.rso_id
        FROM RSOs R
        WHERE R.rso_id = C.rso_id AND R.is_active = 1
	);

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

