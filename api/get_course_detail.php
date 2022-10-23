<?php
// This endpoint returns the course stats for the passed id

require $_SERVER['DOCUMENT_ROOT'].'/config.php'; // Config File.
require $_SERVER['DOCUMENT_ROOT'].'/includes/loader.php'; // Private Loader

$result = $_courses->getCourseStatsByID($_POST['id']);

if (empty($result)) {
  json_encode(array("error"=>"No data found ..."));
}
echo json_encode($result);