<?php
// This endpoint will accept a user id and return the json user details object

require $_SERVER['DOCUMENT_ROOT'].'/config.php'; // Config File.
require $_SERVER['DOCUMENT_ROOT'].'/includes/loader.php'; // Private Loader

$result[0]['user'] = current($_users->getRowByID($_POST['id']));
$result[0]['enrolments'] = $_enrolments->getRowByUserID($_POST['id']);

if (empty($result)) {
  json_encode(array("error"=>"No data found ..."));
}

echo json_encode(current($result));