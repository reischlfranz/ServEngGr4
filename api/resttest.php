<?php

// Read the request body (for POST/PUT methods - ignore for others):
$inp = fopen("php://input", "r");
$reqBodyString = "";
while ($data = fread($inp, 1024)) $reqBodyString .= $data;
$reqBodyData = json_decode($reqBodyString);
fclose($inp);

// Set the content type to JSON
header("Content-Type: application/json");

// Test if HTTP request
if(isset($_SERVER['REQUEST_METHOD'])){
  // HTTP request detected, evaluate HTTP request method
  switch (strtoupper($_SERVER['REQUEST_METHOD'])){
    case 'GET':
      // GET request
      http_response_code(200);
      echo(json_encode($_SERVER));
      break;
    case 'POST':
      // POST request
      http_response_code(201);
      echo(json_encode($reqBodyData));
      break;
    case 'PUT':
      // PUT request
      http_response_code(202);
      echo(json_encode($reqBodyData));
      break;
    case 'DELETE':
      // DELETE request
      http_response_code(204);
      header("What_Deleted: ");
      break;
    default:
      // Non-standard HTTP request
      http_response_code(405);
      die('{"reason":"Not supported"}');
      break;
  }
}



