<?php

/**
 * Courses Model and functions.
 *
 * Functions relating to the Courses table.
 *
 * @since 1
 */

class Courses extends Entity {

  function __construct() {
    
  }

  /**
   * searchCourses - Powers the course search
   *
   * @return array $result
   */
  function searchCourses() {

    // Wildcard search
    $s = '%'.$_GET['s'].'%';

    $db = Database::getInstance();
    $stmt = $db->connect->prepare("SELECT * FROM {$this->tablename} WHERE name LIKE :searchTerm ");
    $stmt->bindParam(':searchTerm',$s);
    $stmt->execute();
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
    return $result;

  }
  
  /**
   * getCourseStatsByID - gets the course stats for the set id.
   * 
   * @param       $id   Course ID
   *
   * @return array $result
   */
  function getCourseStatsByID($id) {

    $db = Database::getInstance();
    $stmt = $db->connect->prepare("SELECT *, 
    ( SELECT count(*) FROM enrolments WHERE status = 'not started'  AND courseID = :id) as `not started`,
    ( SELECT count(*) FROM enrolments WHERE status = 'in progress' AND courseID = :id) as `in progress`,
    ( SELECT count(*) FROM enrolments WHERE status = 'completed' AND courseID = :id) as `completed`
    FROM {$this->tablename} WHERE ID = :id ");
    $stmt->bindParam(':id',$id);
    $stmt->execute();
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if (count($result) == 1) $result = current($result);

    return $result;

  }

}