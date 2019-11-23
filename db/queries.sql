-- Queries necessary for service

-- ## Cars
-- Getting a list of all cars
SELECT * FROM cars;
-- Adding car
INSERT INTO cars (carname, carpassengers) VALUES (:carname, :carpassengers);
-- Deleting car
DELETE FROM cars WHERE carid = :carid;


-- ## Drivers
-- Getting a list of all drivers
SELECT * FROM drivers;
-- Adding driver
INSERT INTO drivers (drivername) VALUES (:name);
-- Deleting driver
DELETE FROM drivers WHERE driverid = :driverid;


-- ## Guests
-- Getting a list of all guests
SELECT * FROM guest;
-- Adding guest
INSERT INTO guest (guestname) VALUES (:guestName);
-- Deleting guest
DELETE FROM guest WHERE guestid = :guestid;

-- ## Drop-Offs and Pick-Ups
-- List all Drop-Offs
-- TODO

-- List all Pick-Ups
-- TODO

-- Add Drop-Off of Guest at time XX
-- TODO

-- Add Pick-Up of Guest at time XX
-- TODO

-- ## Trips
-- Get a list of all trips
SELECT t.tripid, t.direction, d.drivername, c.carname, t.timestart, t.timearrival,
    (strftime('%s', timearrival) - strftime('%s', timestart) ) AS triptime
    FROM trip t
    JOIN cars c on t.carid = c.carid
    JOIN drivers d on t.driverid = d.driverid
    ;

-- Get list of cars with correct destination within timerange around XX
-- TODO

-- Get amount of empty seats of trip YY
-- TODO
--

-- Get current position of each car (direction of last trip)
-- TODO Not counting future/planned trips
SELECT t.carid, tripid, direction FROM trip t
    WHERE tripid = (
        SELECT max(tripid) FROM trip
        WHERE carid = t.carid
        )
    GROUP BY t.carid
    ;

