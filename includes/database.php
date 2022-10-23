<?php

/**
 * Main Database class.
 *
 * Database functions.
 *
 * @since 1
 */

class Database {

  private static $instance;
  public $connect;

  function __construct() {

    try {
      $conn = new PDO("mysql:host=".DB_HOST.";dbname=".DB_NAME, DB_USER, DB_PASSWORD);
      // set the PDO error mode to exception
      $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
      $this->connect = $conn;
    } catch(PDOException $e) {
      echo "Connection failed: " . $e->getMessage();
    }

  }

/**
 * getInstance - gets or sets the instance of database
 *
 * @return $instance
 */

  public static function getInstance() {
    if(!isset(self::$instance)) {
        $object = __CLASS__;
        self::$instance = new $object;
      return self::$instance;
    } else {
      return self::$instance;
    }
  }

  function __destruct() {
    $this->connect = null;
  }

}


