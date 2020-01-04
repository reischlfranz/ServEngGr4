
SELECT timestart, timearrival,
       (strftime('%s', timearrival) - strftime('%s', timestart) ) AS triptime
        FROM trip;


SELECT t.direction, d.drivername, c.carname, t.timestart, t.timearrival,
            (strftime('%s', timearrival) - strftime('%s', timestart) ) AS triptime
            FROM trip t
            JOIN cars c on t.carid = c.carid
            JOIN drivers d on t.driverid = d.driverid;


-- Get current position of each car (direction of last trip)
SELECT t.carid, tripid, direction FROM trip t
    WHERE tripid = (
        SELECT max(tripid) FROM trip
        WHERE carid = t.carid
        )
    GROUP BY t.carid
    ;