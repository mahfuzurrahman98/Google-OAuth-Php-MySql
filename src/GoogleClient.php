<?php

namespace App;

use Google\Client;
use Google\Service\Oauth2;

class GoogleClient {
  private $client;
  public function __construct() {
    $env = require "env.php";

    $clientID = $env['clientID'];
    $clientSecret = $env['clientSecret'];
    $redirectUri = 'http://127.0.0.1:8000/gauth-callback.php';

    // create Client Request to access Google API
    $this->client = new Client();
    $this->client->setClientId($clientID);
    $this->client->setClientSecret($clientSecret);
    $this->client->setRedirectUri($redirectUri);
    $this->client->addScope("email");
    $this->client->addScope("profile");

    // echo "<pre>";
    // var_dump($this->client);
    // die;
  }

  public function getClient() {
    return $this->client;
  }

  public function getAuthUrl() {
    return $this->client->createAuthUrl();
  }

  public function getAccessToken($code) {
    return $this->client->fetchAccessTokenWithAuthCode($code);
  }

  public function setAccessToken($token) {
    $this->client->setAccessToken($token);
  }
}


// dd($env);

// init configuration
