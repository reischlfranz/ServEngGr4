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
    $result = $statement->fetch(PDO::FETCH_OBJ);

    $db = null;
    return $result;
  }

  static function getGuest($guestid) {
    $db = Db::getDbObject();
    $statement = $db->prepare("SELECT g.guestid, g.guestname  FROM guest g
            WHERE g.guestid = :guestid");
    $statement->bindParam(':guestid', $guestid, PDO::PARAM_INT);
    $statement->execute();
    $result = $statement->fetch(PDO::FETCH_OBJ);
    $db = null; // close connection
    return $result;
  }

  static function listGuests() {
    $db = Db::getDbObject();
    $query = 'SELECT g.guestid, g.guestname FROM guest g';
    // Save Result as array (because connection will< be closed afterwards  !)
    $resultArray = $db->query($query)->fetchAll(PDO::FETCH_OBJ);
    $db = null; // close connection
    return $resultArray;
  }

  static function listTripGuests($tripId) {
    $db = Db::getDbObject();
    $statement = $db->prepare("SELECT g.guestid, g.guestname FROM guest g
                WHERE g.pickuptrip=:tripid OR g.dropofftrip=:tripid;");
    $statement->bindParam(':tripid', $tripId, PDO::PARAM_INT);
    $statement->execute();
    // Save Result as array (because connection will< be closed afterwards  !)
    $resultArray = $statement->fetchAll(PDO::FETCH_OBJ);
    $db = null; // close connection
    return $resultArray;
  }

  static function addPickup($guestId, $tripId){
    $db = Db::getDbObject();
    $statement = $db->prepare('UPDATE guest SET pickuptrip=:pickuptrip WHERE guestid = :guestid');
    $statement->bindParam(':guestid', $guestId, PDO::PARAM_INT);
    $statement->bindParam(':pickuptrip', $tripId, PDO::PARAM_INT);
    $result = $statement->execute();
    $db = null; // close connection
    return $result;
  }

  static function addDropoff($guestId, $tripId){
    $db = Db::getDbObject();
    $statement = $db->prepare('UPDATE guest SET dropofftrip=:dropofftrip WHERE guestid = :guestid');
    $statement->bindParam(':guestid', $guestId, PDO::PARAM_INT);
    $statement->bindParam(':dropofftrip', $tripId, PDO::PARAM_INT);
    $result = $statement->execute();
    $db = null; // close connection
    return $result;
  }

  static function deleteGuest($guestId) {
    $db = Db::getDbObject();
    $statement = $db->prepare('DELETE FROM guest WHERE guestid = :guestid');
    $statement->bindParam(':guestid', $guestId, PDO::PARAM_INT);
    $result = $statement->execute();
    $db = null;
    return $result;
  }

}
