<?php

namespace App;


class Database {
  private static $db = null;

  private function __construct() {
    $env = require("env.php");
    self::$db = new \mysqli($env['db_host'], $env['db_user'], $env['db_pass'], $env['db_name']);
  }

  public static function getConnection() {
    if (self::$db === null) {
      new Database();
    }
    return self::$db;
  }
}
