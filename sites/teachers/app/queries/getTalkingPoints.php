<?php
$handle = preg_replace('/[^a-z0-9]/i',"",$_REQUEST['handle']);

require_once("couch.php");

$couch = new couchClient($config->database->connectionString, $config->database->name);
$results = $couch->key(array(date("m-d-Y"), $handle))->getView("views","pointsByDateAndHandle");

if($results->total_rows == 0 || empty($results->rows)){
  echo "[]"; exit();
}

echo json_encode($results->rows[0]->value);