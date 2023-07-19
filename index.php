<?php

ini_set('display_errors', 1);

require_once './vendor/autoload.php';

define('APP_SIGNATURE', 'YOUR_APP_SIGNATURE');

session_start();

// dd($_SESSION);

use App\Database;

$db = Database::getConnection();

if (!isset($_SESSION['is_logged_in'])) {
  header('location: login.php');
}


if (isset($_POST['logout'])) {
  session_destroy();
  header('location: login.php');
}

// get the name of the logged in user
$sql = "SELECT * FROM users WHERE id=" . $_SESSION['user_id'];
$result = $db->query($sql);
$user = $result->fetch_assoc();

// dd($user);
?>


<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Home</title>
</head>

<body>
  <h3>Welcome, <?= $user['name'] ?> </h3>

  <form action="" method="post">
    <input type="submit" name="logout" value="Logout">
  </form>
</body>

</html>