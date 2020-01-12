<?php


class GuestResource {
  static function callGuest($pathArray, $body, $queryParam){


    // Ghetto sub routing
    switch ($_SERVER['REQUEST_METHOD']){
      case 'GET':
        if (isset($pathArray[1])){
          // get Single
          self::getSingleGuest($pathArray[1]);
        }else{
          // get All
          self::getAllGuest();
        }
        break;
      case 'POST':
        if($pathArray[2] == 'pickup'){
          // add Pickup for guest by ID
          self::addGuestPickup($pathArray[1], $body);
        }elseif($pathArray[2] == 'dropoff'){
          // add Dropoff for guest by ID
          self::addGuestDropoff($pathArray[1], $body);
        }else{
          self::addGuest($body);
        }
        break;
      case 'DELETE':
        self::deleteGuest($pathArray[1]);
        break;
      default:
        // Method not allowed
        http_response_code(405);
        break;
    }



  }

  //GET
  private static function getSingleGuest($guestId){
    $response = Guest::getGuest($guestId);
    if(!$response){
      http_response_code(404);
      header('Content-Type: application/json');
      header('Reason: guestId not found');
    }else {
      http_response_code(200);
      header('Content-Type: application/json');
      echo(json_encode($response));
    }
  }

  //GET
  private static function getAllGuest(){
    $response = Guest::listGuests();
    http_response_code(200);
    header('Content-Type: application/json');
    echo(json_encode($response));
  }

  //POST
  private static function addGuest($body){
    $response = "";
    if(!isset($body->guestname)){
      // missing parameters
      http_response_code(400);
      header('Content-Type: application/json');
      header('Reason: Needs "guestname" as parameter.');
    }else{
      // correct parameters
      $response = Guest::addGuest($body->guestname);

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

  private static function addGuestPickup($guestId, $body){
    if(!isset($guestId) || !is_numeric($guestId)){
      // Parameter missing
      http_response_code(400);
      header('Content-Type: application/json');
      header('Reason: guest in /guest/{guestId}/pickup needs to be numeric.');
    }else{
      // Test if guest is in DB
      $response = Guest::getGuest($guestId);
      if(!$response) {
        // Did not succeed
        http_response_code(404);
        header('Content-Type: application/json');
        header('Reason: guestId not found');
      }else{
        // Test if body parameter is correct
        if(!isset($body->tripid) || !is_numeric($body->tripid)){
          // wrong Parameter
          http_response_code(400);
          header('Content-Type: application/json');
          header('Reason: Requires body in form of {"tripid": 12}.');
        }else{
          // check if trip is valid pickup trip
          $tripId = $body->tripid;
          $trip = Trip::getTrip($tripId);
          if(empty($trip)){
            // Trip not available
            http_response_code(400);
            header('Content-Type: application/json');
            header('Reason: Trip ID is not available!');
          }elseif ($trip->direction =='Airport->Hotel'){
            // Trip not available
            http_response_code(400);
            header('Content-Type: application/json');
            header('Reason: Trip is not a pickup Trip!');
          }else{
            // Everything OK
            $response = Guest::addPickup($guestId, $tripId);
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
      }
    }
  }
  private static function addGuestDropoff($guestId, $body){
    if(!isset($guestId) || !is_numeric($guestId)){
      // Parameter missing
      http_response_code(400);
      header('Content-Type: application/json');
      header('Reason: guest in /guest/{guestId}/dropoff needs to be numeric.');
    }else{
      // Test if guest is in DB
      $response = Guest::getGuest($guestId);
      if(!$response) {
        // Did not succeed
        http_response_code(404);
        header('Content-Type: application/json');
        header('Reason: guestId not found');
      }else{
        // Test if body parameter is correct
        if(!isset($body->tripid) || !is_numeric($body->tripid)){
          // wrong Parameter
          http_response_code(400);
          header('Content-Type: application/json');
          header('Reason: Requires body in form of {"tripid": 12}.');
        }else{
          // check if trip is valid dropoff trip
          $tripId = $body->tripid;
          $trip = Trip::getTrip($tripId);
          if(empty($trip)){
            // Trip not available
            http_response_code(400);
            header('Content-Type: application/json');
            header('Reason: Trip ID is not available!');
          }elseif ($trip->direction =='Hotel->Airport'){
            // Trip not available
            http_response_code(400);
            header('Content-Type: application/json');
            header('Reason: Trip is not a dropoff Trip!');
          }else{
            // Everything OK
            $response = Guest::addDropoff($guestId, $tripId);
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
      }
    }
  }


  private static function deleteGuest($guestId) {
    if(!isset($guestId) || !is_numeric($guestId)){
      // Parameter missing
      http_response_code(400);
      header('Content-Type: application/json');
      header('Reason: guest in /guest/{guestId} needs to be numeric.');
    }else{
      // Correct parameters

      // Test if guest is in DB
      $response = Guest::getGuest($guestId);
      if(!$response) {
        // Did not succeed
        http_response_code(404);
        header('Content-Type: application/json');
        header('Reason: guestId not found');
      }else{
        // Delete guest
        $response = Guest::deleteGuest($guestId);
        if(!$response){
          // Did not succeed
          http_response_code(500);
          header('Content-Type: application/json');
          header('Reason: Could not delete guest from DB');
        }else{
          // Did succeed
          http_response_code(204);
          header('Content-Type: application/json');

        }
      }
    }
  }
}
