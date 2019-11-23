<?php
require_once 'model/Db.php';

class Driver {

  static function addDriver($name) {
    $db = Db::getDbObject();
    $statement = $db->prepare("INSERT INTO drivers (drivername) VALUES (:name)");
    $statement->bindParam(':name', $name);
    $statement->execute();

    $db = null;
    return self::listDrivers();
  }

  static function listDrivers() {
    $db = Db::getDbObject();
    $query = 'SELECT * FROM drivers';
    // Save Result as array (because connection will be closed afterwards!)
    $resultArray = $db->query($query)->fetchAll();
    $db = null; // close connection
    return $resultArray;
  }

  static function deleteDriver($driverId) {
    $db = Db::getDbObject();
    $statement = $db->prepare('DELETE FROM drivers WHERE driverid = :driverid');
    $statement->bindParam(':driverid', $driverId, PDO::PARAM_INT);
    $statement->execute();
    $db = null;
    return self::listDrivers();
  }
}
