-- User or Admin Affiliated with University
CREATE TABLE Affilated_with (
    uni_id INTEGER,
    uid INTEGER,
    PRIMARY KEY (uni_id, uid),
    FOREIGN KEY (uni_id) REFERENCES University
		ON DELETE CASCADE
        ON UPDATE CASCADE,
    FOREIGN KEY (uid) REFERENCES Users
		ON DELETE CASCADE
        ON UPDATE CASCADE
);

-- User member of RSO
CREATE TABLE Member_of (
	uid INTEGER,
    rso_id INTEGER,
    PRIMARY KEY (uid, rso_id),
    FOREIGN KEY (uid) REFERENCES Users
		ON DELETE CASCADE
        ON UPDATE CASCADE,
	FOREIGN KEY (rso_id) REFERENCES RSOs
		ON DELETE CASCADE
        ON UPDATE CASCADE
);

-- Admin Owns RSO
CREATE TABLE Owns (
	uid INTEGER,
    rso_id INTEGER,
    PRIMARY KEY (uid, rso_id),
    FOREIGN KEY (uid) REFERENCES Admins
		ON DELETE CASCADE
        ON UPDATE CASCADE,
	FOREIGN KEY (rso_id) REFERENCES RSOs
		ON DELETE CASCADE
        ON UPDATE CASCADE
);

-- Admin creates RSO Event
CREATE TABLE Creates_RSOEvent (
	uid INTEGER,
    rso_id INTEGER,
    event_id INTEGER,
    PRIMARY KEY (uid, rso_id, event_id),
    FOREIGN KEY (uid) REFERENCES Admins
		ON DELETE CASCADE
        ON UPDATE CASCADE,
	FOREIGN KEY (rso_id) REFERENCES RSOs
		ON DELETE CASCADE
        ON UPDATE CASCADE,
	FOREIGN KEY (event_id) REFERENCES RSOEvents
		ON DELETE CASCADE
        ON UPDATE CASCADE
);

-- User comments on Event
CREATE TABLE Comments (
	uid INTEGER,
    event_id INTEGER,
    timestmp TIMESTAMP NOT NULL,
    cmnt VARCHAR(1023),
    rating INTEGER,
    PRIMARY KEY (uid, event_id),
    FOREIGN KEY (uid) REFERENCES Users,
    FOREIGN KEY (event_id) REFERENCES RSOEvents
		ON DELETE CASCADE
        ON UPDATE CASCADE
);

-- create private event
CREATE TABLE Creates_PrivateEvent();

-- create public event
CREATE TABLE Creates_PublicEvent();

-- creates uni profile
CREATE TABLE Creates_Profile();

-- event location
CREATE TABLE Located_at();