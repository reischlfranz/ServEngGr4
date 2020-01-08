<?php
require_once 'model/Db.php';

class Car {

  static function addCar($name, $seats) {
    $db = Db::getDbObject();
    $statement = $db->prepare("INSERT INTO cars (carname, carpassengers) VALUES (:carname, :carpassengers)");
    $statement->bindParam(':carname', $name);
    $statement->bindParam(':carpassengers', $seats, PDO::PARAM_INT);
    $result = $statement->execute();
    $db = null;
    if(!$result){
      // Insert failed
      return false;
    }
    $db = Db::getDbObject();
    // Get the object with the latest ID back
    $statement = $db->prepare("SELECT *  FROM cars WHERE carid = (SELECT MAX(carid) FROM cars)");
    $statement->execute();
    $result = $statement->fetch(PDO::FETCH_OBJ);

    $db = null;
    return $result;
  }

  static function getCar($carId) {
    $db = Db::getDbObject();
    $statement = $db->prepare("SELECT * FROM cars WHERE carid = :carid");
    $statement->bindParam(':carid', $carId, PDO::PARAM_INT);
    $statement->execute();
    $result = $statement->fetch(PDO::FETCH_OBJ);
    $db = null; // close connection
    return $result;
  }

  static function listCars() {
    $db = Db::getDbObject();
    $query = 'SELECT * FROM cars';
    // $result = $db->query($query);
    // Save Result as array (because connection will be closed afterwards!)
    $resultArray = $db->query($query)->fetchAll(PDO::FETCH_OBJ);
    $db = null; // close connection
    return $resultArray;
  }

  static function deleteCar($carId) {
    $db = Db::getDbObject();
    $statement = $db->prepare('DELETE FROM cars WHERE carid = :carid');
    $statement->bindParam(':carid', $carId, PDO::PARAM_INT);
    $result = $statement->execute();
    $db = null;
    return result;
  }

  static function checkAvailCars($time){
    $db = Db::getDbObject();
    $statement = $db->prepare("SELECT * FROM cars c
            WHERE c.carid NOT IN(
                SELECT trip.carid FROM trip
                    WHERE strftime('%s', timestart) > (strftime('%s', :time) - strftime('%s', '0:30'))
                    AND strftime('%s', timearrival) < (strftime('%s', :time) + strftime('%s', '0:30'))
            )
            ");
    $statement->bindParam(':time', $time);
    $statement->execute();
    $resultArray = $statement->fetchAll(PDO::FETCH_OBJ);
    $db = null;
    return $resultArray;
  }

}
