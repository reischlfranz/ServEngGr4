<?php

// Require composer autoloader
require __DIR__ . '/vendor/autoload.php';

use Auth0\SDK\JWTVerifier;

require_once 'model/Car.php';
require_once 'model/Driver.php';
require_once 'model/Guest.php';
require_once 'resource/CarResource.php';
require_once 'resource/DriverResource.php';
require_once 'resource/GuestResource.php';
require_once 'resource/TripResource.php';


// Setting CORS headers, allow API access from everywhere
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers:*");
header("Access-Control-Allow-Methods:OPTIONS,HEAD,GET,PUT,POST,DELETE");
header("Accept:application/json");


// Catch the pre-flight request from browser
if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
//  header("HTTP/1.1 200 ");
  http_response_code(204);
  header("Allow:*");
  exit;
}

$requestHeaders = getallheaders();


// Reading authorization header
// Partly from https://auth0.com/docs/quickstart/backend/php#protect-api-endpoints
$authorizationHeader = isset($requestHeaders['authorization']) ? $requestHeaders['authorization'] : $requestHeaders['Authorization'];
if ($authorizationHeader == null) {
  http_response_code(401);
  header('Content-Type: application/json; charset=utf-8');
  echo json_encode(array("message" => "No authorization header sent."));
  exit();
}

//Extract token
$authorizationHeader = str_replace('bearer ', '', $authorizationHeader);
$token = str_replace('Bearer ', '', $authorizationHeader);

try {
  $verifier = new JWTVerifier([
    'supported_algs' => ['RS256'],
    'valid_audiences' => ['http://hotelserviceservenggr4.azurewebsites.net/api'],
    'authorized_iss' => ['https://dev-xpdi60jr.eu.auth0.com/']
  ]);

  $tokenInfo = $verifier->verifyAndDecode($token);
}catch(\Auth0\SDK\Exception\CoreException $e) {
  // Do not throw, otherwise the response code will be 500 //  throw $e;
  http_response_code(401);
  header("Reason:".$e->getMessage());
  exit();
}


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

