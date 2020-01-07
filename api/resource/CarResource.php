<?php


class CarResource {
  static function callCars($pathArray, $body, $queryParam){


    // Ghetto sub routing
    switch ($_SERVER['REQUEST_METHOD']){
      case 'GET':
        if (isset($pathArray[1])){
          // get Single
          self::getSingleCar($pathArray[1]);
        }else{
          // get All
          self::getAllCars();
        }
        break;
      case 'POST':
        self::addCar($body);
        break;
      case 'DELETE':
        self::deleteCar($pathArray[1]);
        break;
      default:
        // Method not allowed
        http_response_code(405);
        break;
    }



  }

  //GET
  private static function getSingleCar($carId){
    $response = Car::getCar($carId);
    if(!$response){
      http_response_code(404);
      header('Content-Type: application/json');
      header('Reason: carId not found');
    }else {
      http_response_code(200);
      header('Content-Type: application/json');
      echo(json_encode($response));
    }
  }

  //GET
  private static function getAllCars(){
    $response = Car::listCars();
    http_response_code(200);
    header('Content-Type: application/json');
    echo(json_encode($response));
  }

  //POST
  private static function addCar($body){
    $response = "";
    if(!isset($body->carname) || !isset($body->carpassengers) || !is_numeric($body->carpassengers)){
      // missing parameters
      http_response_code(400);
      header('Content-Type: application/json');
      header('Reason: Needs "carname" and "carpassengers" as parameters, "carpassengers" needs to be numeric.');
    }else{
      // correct parameters
      $response = Car::addCar($body->carname, $body->carpassengers);

      if(!$response){
        // Insert failed
        http_response_code(500);
        header('Content-Type: application/json');
        header('Reason: Failed to insert into DB');
      }else{
        // Insert OK
        http_response_code(201);
        header('Content-Type: application/json');
        echo(json_encode($response));
      }
    }

  }

  private static function deleteCar($carId) {
    if(!isset($carId) || !is_numeric($carId)){
      // Parameter missing
      http_response_code(400);
      header('Content-Type: application/json');
      header('Reason: carId in /cars/{carId} needs to be numeric.');
    }else{
      // Correct parameters

      // Test if car is in DB
      $response = Car::getCar($carId);
      if(!$response) {
        // Did not succeed
        http_response_code(404);
        header('Content-Type: application/json');
        header('Reason: carId not found');
      }else{
        // Delete Car
        $response = Car::deleteCar($carId);
        if(!$response){
          // Did not succeed
          http_response_code(500);
          header('Content-Type: application/json');
          header('Reason: Could not delete car from DB');
        }else{
          // Did succeed
          http_response_code(204);
          header('Content-Type: application/json');

        }
      }
    }
  }
}
