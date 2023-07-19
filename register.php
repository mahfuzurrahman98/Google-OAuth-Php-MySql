<?php

ini_set('display_errors', 1);
require_once './vendor/autoload.php';

define('APP_SIGNATURE', 'YOUR_APP_SIGNATURE');
session_start();
if (isset($_SESSION['is_logged_in'])) {
  header('location: index.php');
}

use App\Database;
use App\GoogleClient;


$db = Database::getConnection();
$client = new GoogleClient();



if (isset($_POST['google'])) {
  $_SESSION['google_register'] = 1;
  $auth_url = $client->getAuthUrl();
  header('location: ' . $auth_url);
}

if (isset($_POST['register'])) {
  $name = $_POST['name'];
  $email = $_POST['email'];
  $password = $_POST['password'];

  // first check if user already exists
  $sql = "SELECT * FROM users WHERE email='$email'";
  $result = $db->query($sql);
  if ($result->num_rows > 0) {
    echo "User already exists";
    exit();
  }

  $sql = "INSERT INTO users (name, email, password) VALUES ('$name', '$email', '$password')";
  $db->query($sql);
  header('location: login.php');
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Register</title>
</head>

<body>
  <form action="" method="post"> <br><br>
    <input type="text" name="name" placeholder="Enter your name" required> <br><br>
    <input type="email" name="email" placeholder="Enter your email" required> <br><br>
    <input type="password" name="password" placeholder="Enter your password" required> <br><br>
    <input type="submit" name="register" value="Register">
  </form>

  <br>

  <form method="POST">
    OR, Registered with <input type="submit" name="google" value="Google">
  </form>

  <p>Already have an account? <a href="/login.php">Login</a> </p>
</body>

</html>