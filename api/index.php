<?php
require_once 'model/Car.php';
require_once 'resource/CarResource.php';

// Read the request body (for POST/PUT methods - ignore for others):
$inp = fopen("php://input", "r");
$reqBodyString = "";
while ($data = fread($inp, 1024)) $reqBodyString .= $data;
$body = json_decode($reqBodyString);
fclose($inp);

$method = $_SERVER['REQUEST_METHOD'];
$paths = explode("/", strtolower(ltrim($_SERVER['PATH_INFO'],'/')));
$resource = $paths[0];

// very crude manual routing
switch ($resource){
  case 'cars':
//    echo "CARS" . $method;
    CarResource::callCars($paths, $body, $method);


    break;
  case 'drivers':

    break;

  case 'guests':

    break;
  case 'trips':

    break;
}
