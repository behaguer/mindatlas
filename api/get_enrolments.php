<?php
// This endpoint returns the enrolments for the status

require $_SERVER['DOCUMENT_ROOT'].'/config.php'; // Config File.
require $_SERVER['DOCUMENT_ROOT'].'/includes/loader.php'; // Private Loader

$result = $_enrolments->getRowsByStatus($_POST['id'],$_POST['status']);

if (empty($result)) {
  json_encode(array("error"=>"No data found ..."));
}
echo json_encode($result);