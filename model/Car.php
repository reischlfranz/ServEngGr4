<?php
require_once 'model/Db.php';

class Car {

  static function addCar($name, $seats) {
    $db = Db::getDbObject();
    $statement = $db->prepare("INSERT INTO cars (carname, carpassengers) VALUES (:carname, :carpassengers)");
    $statement->bindParam(':carname', $name);
    $statement->bindParam(':carpassengers', $seats, PDO::PARAM_INT);
    $statement->execute();

    $db = null;
    return self::listCars();
  }

  static function listCars() {
    $db = Db::getDbObject();
    $query = 'SELECT * FROM cars';
    // $result = $db->query($query);
    // Save Result as array (because connection will be closed afterwards!)
    $resultArray = $db->query($query)->fetchAll();
    $db = null; // close connection
    return $resultArray;
  }

  static function deleteCar($carId) {
    $db = Db::getDbObject();
    $statement = $db->prepare('DELETE FROM cars WHERE carid = :carid');
    $statement->bindParam(':carid', $carId, PDO::PARAM_INT);
    $statement->execute();
    $db = null;
    return self::listCars();
  }

}
