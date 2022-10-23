<?php

/**
 * Database Entity class.
 * 
 * This class is used for common entity functions across all of the tables
 *
 * Database functions.
 *
 * @since 1
 */

class Entity {

  protected $tablename;

  function __construct() {
  }

  public function setTablename($tname) {
    $this->tablename = $tname;
  }

  /**
   * getAllRows - Gets all of the row for the set table
   *
   * @return array $result
   */
  public function getAllRows() {
    $db = Database::getInstance();
    $qry = $db->connect->prepare("SELECT * FROM {$this->tablename}");
    $qry->execute();
    $result = $qry->fetchAll(PDO::FETCH_ASSOC);
    return $result;
  }

  /**
   * getRowByID - Gets the result for the row from the passed ID
   * 
   * @param   int   $id
   *
   * @return  array $result
   */
  public function getRowByID($id) {
    $db = Database::getInstance();
    $stmt = $db->connect->prepare("SELECT * FROM {$this->tablename} WHERE ID = :id");
    $stmt->bindParam(':id',$id);
    $stmt->execute();
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
    return $result;
  }

  /**
   * truncateTable - truncates the set table
   * 
   * @return  void
   */
  public function truncateTable() {
    $db = Database::getInstance();
    $qry = $db->connect->prepare("TRUNCATE TABLE {$this->tablename};");
    $qry->execute();
  }

  /**
   * getCount - Gets the count of the current table
   * 
   * @return string $result[0]
   */
  public function getCount() {
    $db = Database::getInstance();
    $qry = $db->connect->prepare("SELECT count(*) as counter FROM {$this->tablename}");
    $qry->execute();
    $result = $qry->fetch();

    return $result[0];
  }
  
}


