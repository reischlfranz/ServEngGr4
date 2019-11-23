-- Drop tables
DROP TABLE IF EXISTS dropoff;
DROP TABLE IF EXISTS pickup;
DROP TABLE IF EXISTS guest;
DROP TABLE IF EXISTS trip;
DROP TABLE IF EXISTS cars;
DROP TABLE IF EXISTS drivers;

-- Mark version of Schema
DROP TABLE IF EXISTS version;
CREATE TABLE version(version INTEGER PRIMARY KEY, dt TIME NOT NULL);
INSERT INTO version (version, dt) VALUES (2, date('2019-11-23'));

-- Create tables
CREATE TABLE cars
(
    carid         INTEGER      NOT NULL PRIMARY KEY AUTOINCREMENT,
    carname       VARCHAR(100) NOT NULL,
    carpassengers INTEGER      NOT NULL
);

CREATE TABLE drivers
(
    driverid   INTEGER      NOT NULL PRIMARY KEY AUTOINCREMENT,
    drivername VARCHAR(100) NOT NULL
);

CREATE TABLE guest
(
    guestid   INTEGER      NOT NULL PRIMARY KEY AUTOINCREMENT,
    guestname VARCHAR(100) NOT NULL
);

CREATE TABLE trip
(
    tripid      INTEGER      NOT NULL PRIMARY KEY AUTOINCREMENT,
    direction   VARCHAR(100) NOT NULL,
    driverid                 NOT NULL REFERENCES drivers,
    carid                    NOT NULL REFERENCES cars,
    timestart   TIME         NOT NULL,
    timearrival TIME         NOT NULL
);

CREATE TABLE dropoff
(
    dropoffid INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT,
    date      TIME    NOT NULL,
    guestid           NOT NULL REFERENCES guest,
    tripid            NOT NULL REFERENCES trip
);

CREATE TABLE pickup
(
    pickupid INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT,
    date     DATE    NOT NULL,
    guestid          NOT NULL REFERENCES guest,
    tripid           NOT NULL REFERENCES trip
);
