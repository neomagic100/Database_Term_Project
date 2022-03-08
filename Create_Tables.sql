USE UniversityEvents;

-- RSO table, have primary key as autoincrementing integer
CREATE TABLE RSOs (
	rso_id INTEGER AUTO_INCREMENT NOT NULL,
    rname VARCHAR(255),
    rtype VARCHAR(255),
    is_active VARBINARY(3),
    PRIMARY KEY(rso_id)
);
