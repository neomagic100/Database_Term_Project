USE UniversityEvents;

CREATE VIEW PublicEventView AS
	SELECT event_id, event_name, descrip, event_date, event_start, event_end
    -- SELECT event_name, descrip, DAYNAME(event_date), MONTHNAME(event_date), DAYOFMONTH(event_date), YEAR(event_date), event_start, event_end
    FROM PublicEvents
    INNER JOIN Events USING (event_id)
    ORDER BY event_date, event_start;
    
CREATE VIEW PrivateEventView AS
    SELECT event_id, event_name, descrip, event_date, event_start
    FROM PrivateEvents
    INNER JOIN Events USING (event_id)
    ORDER BY event_date, event_start;
    
CREATE VIEW RSOEventView AS
    SELECT event_id, event_name, descrip, event_date, event_start
    FROM RSOEvents
    INNER JOIN Events USING (event_id)
    ORDER BY event_date, event_start;

