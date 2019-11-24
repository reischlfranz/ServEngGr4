<?php
require_once 'model/Db.php';
require_once 'model/Guest.php';

class Trip {

  static function listTrips() {
    $db = Db::getDbObject();
    $query = "SELECT t.tripid, t.direction, d.drivername, c.carname, t.timestart, t.timearrival, 
            (strftime('%s', timearrival) - strftime('%s', timestart) ) AS triptime
            FROM trip t 
            JOIN cars c on t.carid = c.carid
            JOIN drivers d on t.driverid = d.driverid
            ;
            ";
    // Save Result as array (because connection will be closed afterwards!)
    $resultArray = $db->query($query)->fetchAll();
    $db = null; // close connection
    return $resultArray;
  }

}