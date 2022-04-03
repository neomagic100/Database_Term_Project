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
    SELECT event_id, event_name, descrip, DATE_FORMAT(event_date, '%a, %b %d, %Y') AS event_date,
		TIME_FORMAT(event_start, '%h:%i %p') AS event_start, 
        TIME_FORMAT(event_end, '%h:%i %p') AS event_end
    FROM PrivateEvents
    INNER JOIN Events USING (event_id)
    WHERE is_published = 1
    ORDER BY Events.event_date, Events.event_start;

-- -- View to see RSO events, date and time formatted, ordered chronologically
CREATE VIEW RSOEventView AS
    SELECT event_id, event_name, descrip, DATE_FORMAT(event_date, '%a, %b %d, %Y') AS event_date,
		TIME_FORMAT(event_start, '%h:%i %p') AS event_start, 
        TIME_FORMAT(event_end, '%h:%i %p') AS event_end
    FROM RSOEvents
    INNER JOIN Events USING (event_id)
    WHERE is_published = 1
    ORDER BY Events.event_date, Events.event_start;

