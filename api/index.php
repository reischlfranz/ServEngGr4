<?php
require_once 'model/Car.php';
require_once 'model/Driver.php';
require_once 'model/Guest.php';
require_once 'resource/CarResource.php';
require_once 'resource/DriverResource.php';
require_once 'resource/GuestResource.php';
require_once 'resource/TripResource.php';

// Read the request body (for POST/PUT methods - ignore for others):
$inp = fopen("php://input", "r");
$reqBodyString = "";
while ($data = fread($inp, 1024)) $reqBodyString .= $data;
$body = json_decode($reqBodyString);
fclose($inp);

$method = $_SERVER['REQUEST_METHOD'];
$paths = explode("/", strtolower(ltrim($_SERVER['PATH_INFO'],'/')));
$resource = $paths[0];

// very ghetto crude manual routing
switch ($resource){
  case 'cars':
    CarResource::callCars($paths, $body, $method);
    break;
  case 'drivers':
    DriverResource::callDriver($paths, $body, $method);
    break;
  case 'guests':
    GuestResource::callGuest($paths, $body, $method);
    break;
  case 'trips':
    TripResource::callTrips($paths, $body, $method);
    break;
  default:
    // Route not available
    http_response_code(404);
    header('Reason: resource not available');
}
