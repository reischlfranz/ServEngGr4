<?php
require_once 'model/Db.php';

class Guest {

  static function addGuest($guestName) {
    $db = Db::getDbObject();
    $statement = $db->prepare("INSERT INTO guest (guestname) VALUES (:guestName)");
    $statement->bindParam(':guestName', $guestName);
    $statement->execute();

    $db = null;
    return self::listGuests();
  }

  static function listGuests() {
    $db = Db::getDbObject();
    $query = 'SELECT * FROM guest';
    // $result = $db->query($query);
    // Save Result as array (because connection will be closed afterwards!)
    $resultArray = $db->query($query)->fetchAll();
    $db = null; // close connection
    return $resultArray;
  }

  static function deleteGuest($guestId) {
    $db = Db::getDbObject();
    $statement = $db->prepare('DELETE FROM guest WHERE guestid = :guestid');
    $statement->bindParam(':guestid', $guestId, PDO::PARAM_INT);
    $statement->execute();
    $db = null;
    return self::listGuests();
  }
}
