-- User or Admin Affiliated with University
CREATE TABLE Affilated_with (
    uni_id INTEGER,
    uid INTEGER,
    PRIMARY KEY (uni_id, uid),
    FOREIGN KEY (uni_id) REFERENCES University(uni_id)
		ON DELETE CASCADE
        ON UPDATE CASCADE,
    FOREIGN KEY (uid) REFERENCES Users(uid)
		ON DELETE CASCADE
        ON UPDATE CASCADE
);

-- User member of RSO
CREATE TABLE Member_of (
	uid INTEGER,
    rso_id INTEGER,
    PRIMARY KEY (uid, rso_id),
    FOREIGN KEY (uid) REFERENCES Users(uid)
		ON DELETE CASCADE
        ON UPDATE CASCADE,
	FOREIGN KEY (rso_id) REFERENCES RSOs(rso_id)
		ON DELETE CASCADE
        ON UPDATE CASCADE
);

-- Admin Owns RSO
CREATE TABLE Owns (
	uid INTEGER,
    rso_id INTEGER,
    PRIMARY KEY (uid, rso_id),
    FOREIGN KEY (uid) REFERENCES Admins(uid)
		ON DELETE CASCADE
        ON UPDATE CASCADE,
	FOREIGN KEY (rso_id) REFERENCES RSOs(rso_id)
		ON DELETE CASCADE
        ON UPDATE CASCADE
);

-- Admin creates RSO Event
CREATE TABLE Creates_RSOEvent (
    rso_id INTEGER,
    event_id INTEGER,
    PRIMARY KEY (rso_id, event_id),
	FOREIGN KEY (rso_id) REFERENCES RSOs(rso_id)
		ON DELETE CASCADE
        ON UPDATE CASCADE,
	FOREIGN KEY (event_id) REFERENCES RSOEvents(event_id)
		ON DELETE CASCADE
        ON UPDATE CASCADE
);

-- create private event
CREATE TABLE Creates_PrivateEvent(
	uid INTEGER,
    event_id INTEGER,
    PRIMARY KEY (uid, event_id),
    FOREIGN KEY (uid) REFERENCES Admins(uid)
		ON DELETE SET NULL
        ON UPDATE CASCADE,
	FOREIGN KEY (event_id) REFERENCES PrivateEvents(event_id)
		ON DELETE CASCADE
        ON UPDATE CASCADE
);

-- create public event
CREATE TABLE Creates_PublicEvent(
	uid INTEGER,
    event_id INTEGER,
	PRIMARY KEY (uid, event_id),
    FOREIGN KEY (uid) REFERENCES Superusers(uid)
		ON DELETE SET NULL
        ON UPDATE CASCADE,
	FOREIGN KEY (event_id) REFERENCES PublicEvents(event_id)
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
    FOREIGN KEY (uid) REFERENCES Users(uid)
		ON DELETE SET NULL
        ON UPDATE CASCADE,
    FOREIGN KEY (event_id) REFERENCES RSOEvents(event_id)
		ON DELETE CASCADE
        ON UPDATE CASCADE
);

-- creates uni profile
CREATE TABLE Creates_Profile(
	uid INTEGER,
    uni_id INTEGER,
    PRIMARY KEY (uid, uni_id),
    FOREIGN KEY (uid) REFERENCES Superusers(uid)
		ON DELETE SET NULL
        ON UPDATE CASCADE,
    FOREIGN KEY (uni_id) REFERENCES University(uni_id)
		ON DELETE CASCADE
        ON UPDATE CASCADE
);
