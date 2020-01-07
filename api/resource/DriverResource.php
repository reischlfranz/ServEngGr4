<?php


class DriverResource {
  static function callDriver($pathArray, $body, $queryParam){


    // Ghetto sub routing
    switch ($_SERVER['REQUEST_METHOD']){
      case 'GET':
        if (isset($pathArray[1])){
          // get Single
          self::getSingleDriver($pathArray[1]);
        }else{
          // get All
          self::getAllDriver();
        }
        break;
      case 'POST':
        self::addDriver($body);
        break;
      case 'DELETE':
        self::deleteDriver($pathArray[1]);
        break;
      default:
        // Method not allowed
        http_response_code(405);
        break;
    }



  }

  //GET
  private static function getSingleDriver($driverId){
    $response = Driver::getDriver($driverId);
    if(!$response){
      http_response_code(404);
      header('Content-Type: application/json');
      header('Reason: driverId not found');
    }else {
      http_response_code(200);
      header('Content-Type: application/json');
      echo(json_encode($response));
    }
  }

  //GET
  private static function getAllDriver(){
    $response = Driver::listDrivers();
    http_response_code(200);
    header('Content-Type: application/json');
    echo(json_encode($response));
  }

  //POST
  private static function addDriver($body){
    $response = "";
    if(!isset($body->drivername)){
      // missing parameters
      http_response_code(400);
      header('Content-Type: application/json');
      header('Reason: Needs "drivername" as parameter.');
    }else{
      // correct parameters
      $response = Driver::addDriver($body->drivername);

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

  private static function deleteDriver($driverId) {
    if(!isset($driverId) || !is_numeric($driverId)){
      // Parameter missing
      http_response_code(400);
      header('Content-Type: application/json');
      header('Reason: driverId in /driver/{driverId} needs to be numeric.');
    }else{
      // Correct parameters

      // Test if driver is in DB
      $response = Driver::getDriver($driverId);
      if(!$response) {
        // Did not succeed
        http_response_code(404);
        header('Content-Type: application/json');
        header('Reason: driverId not found');
      }else{
        // Delete Driver
        $response = Driver::deleteDriver($driverId);
        if(!$response){
          // Did not succeed
          http_response_code(500);
          header('Content-Type: application/json');
          header('Reason: Could not delete driver from DB');
        }else{
          // Did succeed
          http_response_code(204);
          header('Content-Type: application/json');

        }
      }
    }
  }
}
