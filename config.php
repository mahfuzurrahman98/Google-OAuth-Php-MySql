<?php
require_once "vendor/autoload.php";

function dd($data) {
  echo "<pre>";
  var_dump($data);
  die;
}

// init configuration
$clientID = '';
$clientSecret = '';
$redirectUri = 'http://127.0.0.1:8000/gauth-callback.php';

// create Client Request to access Google API
$client = new Google_Client();
$client->setClientId($clientID);
$client->setClientSecret($clientSecret);
$client->setRedirectUri($redirectUri);
$client->addScope("email");
$client->addScope("profile");

$db = new mysqli('localhost', 'root', 'pass9859', 'gauth');
