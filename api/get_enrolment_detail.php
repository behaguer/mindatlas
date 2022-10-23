<?php
// This endpoint returns the enrolment for the passed userid

require $_SERVER['DOCUMENT_ROOT'].'/config.php'; // Config File.
require $_SERVER['DOCUMENT_ROOT'].'/includes/loader.php'; // Private Loader

$result = $_enrolment->getRowByUserID($_POST['id']);

if (empty($result)) {
  json_encode(array("error"=>"No data found ..."));
}
echo json_encode(current($result));