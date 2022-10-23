<?php

/**
 * Users Model and functions.
 *
 * Functions relating to the Users table.
 *
 * @since 1
 */

class Users extends Entity {

  function __construct() {
    
  }

  /**
   * searchCourses - Powers the user search
   *
   * @return array $result
   */
  function searchUsers() {

    // Wildcard search
    $s = '%'.$_GET['s'].'%';

    $db = Database::getInstance();
    $stmt = $db->connect->prepare("SELECT * FROM {$this->tablename} WHERE firstname LIKE :searchTerm OR surname LIKE :searchTerm OR email LIKE :searchTerm OR CONCAT(COALESCE(firstname, ''),' ', COALESCE(surname, '')) LIKE :searchTerm");
    $stmt->bindParam(':searchTerm',$s);
    $stmt->execute();
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
    return $result;

  }


}