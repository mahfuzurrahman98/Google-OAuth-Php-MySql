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

if (isset($_POST['login'])) {
  $email = $_POST['email'];
  $password = $_POST['password'];

  $sql = "SELECT * FROM users WHERE email='$email' AND password='$password' AND google_auth=0";
  $result = $db->query($sql);

  if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $_SESSION['user_id'] = $row['id'];
    $_SESSION['is_logged_in'] = 1;
    header('location: index.php');
  } else {
    echo "Invalid email or password";
    exit();
  }
}

if (isset($_POST['google'])) {
  $_SESSION['google_login'] = 1;

  $auth_url = $client->getAuthUrl();
  header('location: ' . $auth_url);
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login</title>
</head>

<body>
  <form method="post">
    <input type="email" name="email" placeholder="Enter your email" required> <br><br>
    <input type="password" name="password" placeholder="Enter your password" required> <br><br>
    <input type="submit" name="login" value="Login">
  </form>

  <br>

  <form method="POST">
    OR, Login with <input type="submit" name="google" value="Google">
  </form>


  <p>Not registered yet? <a href="/register.php">Register</a> </p>

</body>

</html>