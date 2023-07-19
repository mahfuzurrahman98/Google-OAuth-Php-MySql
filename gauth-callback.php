<?php

ini_set('display_errors', 1);
require_once './vendor/autoload.php';

define('APP_SIGNATURE', 'YOUR_APP_SIGNATURE');

session_start();
if (!isset($_SESSION['google_register']) && !isset($_SESSION['google_login'])) {
  echo ('No access');
  die;
}

use App\Database;
use App\GoogleClient;
use Google\Service\Oauth2;


$db = Database::getConnection();
$client = new GoogleClient();



if (isset($_GET['code'])) {
  $code = $_GET['code'];

  $token = $client->getAccessToken($_GET['code']);

  $client->setAccessToken($token['access_token']);
  // var_dump($code);
  // die;


  // get profile info
  $google_oauth = new Oauth2($client->getClient());
  $google_account_info = $google_oauth->userinfo->get();

  $email =  $google_account_info->email;
  $name =  $google_account_info->name;

  // dd($_SESSION);

  if (isset($_SESSION['google_login'])) {
    unset($_SESSION['google_login']);
    // do login stuff
    $sql = "SELECT * FROM users WHERE email='$email' AND google_auth=1";
    $result = $db->query($sql);
    if ($result->num_rows > 0) {
      $user = $result->fetch_assoc();
      $_SESSION['user_id'] = $user['id'];
      $_SESSION['is_logged_in'] = 1;
      header('location: index.php');
    } else {
      echo "Invalid email or password";
      exit();
    }
  } else if (isset($_SESSION['google_register'])) {
    unset($_SESSION['google_register']);
    // do register stuff
    $sql = "SELECT * FROM users WHERE email='$email'";
    $result = $db->query($sql);
    if ($result->num_rows > 0) {
      echo "User already exists";
      exit();
    }

    $sql = "INSERT INTO users (name, email, google_auth) VALUES ('$name', '$email', 1)";
    $db->query($sql);
    header('location: login.php');
  } else {
    echo ('do nothing');
    die;
    header('location: login.php');
  }
}
