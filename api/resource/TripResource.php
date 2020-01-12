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
    if(isset($_REQUEST['pickup']) && $_REQUEST['pickup']==1 && !(isset($_REQUEST['dropoff']) && $_REQUEST['dropoff'] == 1) ){
      $response2 = array_filter($response, "self::FilterPickup");
    }elseif(isset($_REQUEST['dropoff']) && $_REQUEST['dropoff']==1 && !(isset($_REQUEST['pickup']) && $_REQUEST['pickup'] == 1) ){
      $response2 = array_filter($response, "self::FilterDropoff");
    }else{
      $response2 = $response;
    }
    http_response_code(200);
    header('Content-Type: application/json');
    echo(json_encode(array_values($response2)));
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
