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
      case 'POST':
        self::addTrip($body);
        break;
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
    if(isset($_REQUEST['pickup']) && $_REQUEST['pickup']==1
          && !(isset($_REQUEST['dropoff']) && $_REQUEST['dropoff'] == 1)
          && isset($response->direction) && $response->direction != 'Airport->Hotel' ){
      $response = false;
    }elseif(isset($_REQUEST['dropoff']) && $_REQUEST['dropoff']==1
          && !(isset($_REQUEST['pickup']) && $_REQUEST['pickup'] == 1)
          && isset($response->direction) && $response->direction != 'Hotel->Airport' ){
      $response = false;
    }
    if(!$response){
      http_response_code(404);
      header('Reason: tripId not found');
    }else {
      http_response_code(200);
      echo(json_encode($response));
    }
  }

  //GET
  private static function getAllTrips(){
    $response = Trip::listTrips();
    if(isset($_REQUEST['pickup']) && $_REQUEST['pickup']==1 && !(isset($_REQUEST['dropoff']) && $_REQUEST['dropoff'] == 1) ){
      $response2 = array_filter($response, "self::FilterPickup");
    }elseif(isset($_REQUEST['dropoff']) && $_REQUEST['dropoff']==1 && !(isset($_REQUEST['pickup']) && $_REQUEST['pickup'] == 1) ){
      $response2 = array_filter($response, "self::FilterDropoff");
    }else{
      $response2 = $response;
    }
    http_response_code(200);
    echo(json_encode(array_values($response2)));
  }

  //POST
  private static function addTrip($body){
    $response = "";
    if(!isset($body->direction)
          || !isset($body->date)
          || !isset($body->timestart)
          || !isset($body->timearrival)
          || !isset($body->carid)
          || !isset($body->driverid)
          ){
      // missing parameters
      http_response_code(400);
      header('Reason: Needs JSON object with parameters "direction", "date", "timestart", "timearrival", "carid" and "driverid".');
    }elseif(!Car::getCar($body->carid)){
      // No valid carId
      http_response_code(400);
      header('Reason: Invalid carid');
    }elseif(!Driver::getDriver($body->driverid)) {
      // No valid carId
      http_response_code(400);
      header('Reason: Invalid driverid');
    }else{
      // correct parameters
      $response = Trip::addTrip($body->direction, $body->date, $body->timestart, $body->timearrival, $body->driverid, $body->carid);

      if(!$response){
        // Insert failed
        http_response_code(500);
        header('Reason: Failed to insert into DB');
      }else{
        // Insert OK
        http_response_code(201);
        echo(json_encode($response));
      }
    }
  }

  private static function FilterPickup($obj){
    if($obj->direction == "Airport->Hotel") return true;
    return false;
  }
  private static function FilterDropoff($obj){
    if($obj->direction == "Hotel->Airport") return true;
    return false;
  }


}
