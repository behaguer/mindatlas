<?php

/**
 * Enrolments Model and functions.
 *
 * Functions relating to the Enrolments table.
 *
 * @since 1
 */

class Enrolments extends Entity {

  function __construct() {
    
  }

  /**
   * getRowByUserID - gets the enrolment details for the user id.
   * 
   * @param       $id   User ID
   *
   * @return array $result
   */
  public function getRowByUserID($id) {

    $db = Database::getInstance();
    $stmt = $db->connect->prepare("SELECT e.status, e.courseID, c.name FROM {$this->tablename} e LEFT JOIN courses c on e.courseID = c.ID WHERE userID = :id");
    $stmt->bindParam(':id',$id);
    $stmt->execute();
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
    return $result;

  }

  /**
   * getRowsByStatus - gets the enrolment details per the course id and status
   * 
   * @param       $courseID   Course ID
   * @param       $status   One of ['completed','in progress','not started']
   *
   * @return array $result
   */
  public function getRowsByStatus($courseID, $status) {

    $db = Database::getInstance();
    $stmt = $db->connect->prepare("SELECT e.courseID, e.status, u.ID as userID, u.firstname, u.surname FROM {$this->tablename} e LEFT JOIN courses c on e.courseID = c.ID LEFT JOIN users u ON e.userID = u.ID WHERE e.status = :status AND e.courseID = :courseID");
    $stmt->bindParam(':status',$status);
    $stmt->bindParam(':courseID',$courseID);
    $stmt->execute();
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
    return $result;

  }

}