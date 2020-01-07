<?php
require_once 'model/Db.php';
require_once 'model/Trip.php';

class Guest {

  static function addGuest($guestName) {
    $db = Db::getDbObject();
    $statement = $db->prepare("INSERT INTO guest (guestname) VALUES (:guestName)");
    $statement->bindParam(':guestName', $guestName);
    $result = $statement->execute();
    $db = null;
    if(!$result){
      // Insert failed
      return false;
    }
    $db = Db::getDbObject();
    // Get the object with the latest ID back
    $statement = $db->prepare("SELECT *  FROM guest WHERE guestid = (SELECT MAX(guestid) FROM guest)");
    $statement->execute();
    $result = $statement->fetch();

    $db = null;
    return $result;
  }

  static function getGuest($guestid) {
    $db = Db::getDbObject();
    $statement = $db->prepare("SELECT g.guestid, g.guestname, d.date as dropoffdate, p.date as pickupdate  FROM guest g
            LEFT OUTER JOIN dropoff d on g.guestid = d.guestid
            LEFT OUTER JOIN pickup p on g.guestid = p.guestid
            WHERE g.guestid = :guestid");
    $statement->bindParam(':guestid', $guestid, PDO::PARAM_INT);
    $statement->execute();
    $result = $statement->fetch();
    $db = null; // close connection
    return $result;
  }

  static function listGuests() {
    $db = Db::getDbObject();
    $query = 'SELECT g.guestid, g.guestname, d.date as dropoffdate, p.date as pickupdate  FROM guest g
            LEFT OUTER JOIN dropoff d on g.guestid = d.guestid
            LEFT OUTER JOIN pickup p on g.guestid = p.guestid
            ';
    // $result = $db->query($query);
    // Save Result as array (because connection will< be closed afterwards  !)
    $resultArray = $db->query($query)->fetchAll();
    $db = null; // close connection
    return $resultArray;
  }

  static function deleteGuest($guestId) {
    $db = Db::getDbObject();
    $statement = $db->prepare('DELETE FROM guest WHERE guestid = :guestid');
    $statement->bindParam(':guestid', $guestId, PDO::PARAM_INT);
    $result = $statement->execute();
    $db = null;
    return $result;
  }

  static function listGuestsForDropOff(){
    $db = Db::getDbObject();
    $query = 'SELECT g.guestid, g.guestname, d.date, d.tripid
        FROM guest g
        LEFT OUTER JOIN dropoff d on g.guestid = d.guestid
        WHERE tripid IS null
        ;';
    // Save Result as array (because connection will be closed afterwards!)
    $resultArray = $db->query($query)->fetchAll();
    $db = null; // close connection
    return $resultArray;
  }

  static function addDropOff($guestId, $dropoffDate) {
    $db = Db::getDbObject();
    $statement = $db->prepare("INSERT INTO dropoff (date, guestid) values (:date, :guestid);");
    $statement->bindParam(':date', $dropoffDate);
    $statement->bindParam(':guestid', $guestId);
    $result = $statement->execute();
    Trip::testTrips();

    $db = null;
    return $result;
  }

  static function listGuestsForPickUp(){
    $db = Db::getDbObject();
    $query = 'SELECT g.guestid, g.guestname, p.date, p.tripid
        FROM guest g
        LEFT OUTER JOIN pickup p on g.guestid = p.guestid
        WHERE tripid IS null
        ;';
    // Save Result as array (because connection will be closed afterwards!)
    $resultArray = $db->query($query)->fetchAll();
    $db = null; // close connection
    return $resultArray;
  }

  static function addPickUp($guestId, $pickupDate) {
    $db = Db::getDbObject();
    $statement = $db->prepare("INSERT INTO pickup (date, guestid) values (:date, :guestid);");
    $statement->bindParam(':date', $pickupDate);
    $statement->bindParam(':guestid', $guestId);
    $result = $statement->execute();

    $db = null;
    Trip::testTrips();

    return $result;
  }

  static function listGuestsForPickUp1(){
    $db = Db::getDbObject();
    $query = 'SELECT * FROM guest g WHERE g.guestid NOT IN (SELECT guestid FROM pickup p);';
    // Save Result as array (because connection will be closed afterwards!)
    $resultArray = $db->query($query)->fetchAll();
    $db = null; // close connection
    return $resultArray;
  }

}
