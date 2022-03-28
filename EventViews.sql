-- Templates of Views

USE UniversityEvents;

CREATE VIEW PublicEventView AS
	SELECT event_name, descrip, event_date, event_start, event_end
    FROM PublicEvents
    INNER JOIN Events USING (event_id)
    ORDER BY event_date, event_start;
    
CREATE VIEW PrivateEventView AS
	SELECT event_name, descrip, event_date, event_start
    FROM PublicEvents
    INNER JOIN Events USING (event_id)
    UNION
    SELECT event_name, descrip, event_date, event_start
    FROM PrivateEvents
    INNER JOIN Events USING (event_id)
    ORDER BY event_date, event_start;
    
CREATE VIEW RSOEventView AS
	SELECT event_name, descrip, event_date, event_start
    FROM PublicEvents
    INNER JOIN Events USING (event_id)
    UNION
    SELECT event_name, descrip, event_date, event_start
    FROM PrivateEvents
    INNER JOIN Events USING (event_id)
    UNION
    SELECT event_name, descrip, event_date, event_start
    FROM RSOEvents
    INNER JOIN Events USING (event_id)
    ORDER BY event_date, event_start;
