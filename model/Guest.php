<?php
require_once 'model/Db.php';

class Guest {

  static function addGuest($guestName) {
    $db = Db::getDbObject();
    $statement = $db->prepare("INSERT INTO guest (guestname) VALUES (:guestName)");
    $statement->bindParam(':guestName', $guestName);
    $result = $statement->execute();

    $db = null;
    return $result;
  }

  static function listGuests() {
    $db = Db::getDbObject();
    $query = 'SELECT * FROM guest';
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
        LEFT OUTER JOIN dropoff d on g.guestid = d.guestid;';
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

    $db = null;
    return $result;
  }

  static function listGuestsForPickUp(){
    $db = Db::getDbObject();
    $query = 'SELECT g.guestid, g.guestname, p.date, p.tripid 
        FROM guest g 
        LEFT OUTER JOIN pickup p on g.guestid = p.guestid;';
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
