<?php


class TripResource {
  static function callTrips($pathArray, $body, $queryParam){


    // Ghetto sub routing
    switch ($_SERVER['REQUEST_METHOD']){
      case 'GET':
        if (isset($pathArray[1])){
          // get Single
          self::getSingleTrip($pathArray[1]);
        }else{
          // get All
          self::getAllTrips();
        }
        break;
//      case 'POST':
//        self::addTrip($body);
//        break;
//      case 'DELETE':
//        self::deleteTrip($pathArray[1]);
//        break;
      default:
        // Method not allowed
        http_response_code(405);
        break;
    }



  }

  //GET
  private static function getSingleTrip($tripId){
    $response = Trip::getTrip($tripId);
    if(!$response){
      http_response_code(404);
      header('Content-Type: application/json');
      header('Reason: tripId not found');
    }else {
      http_response_code(200);
      header('Content-Type: application/json');
      echo(json_encode($response));
    }
  }

  //GET
  private static function getAllTrips(){
    $response = Trip::listTrips();
    http_response_code(200);
    header('Content-Type: application/json');
    echo(json_encode($response));
  }

}
