<?php
$handle = preg_replace('/[^a-z0-9]/i',"",$_REQUEST['handle']);

require("php/couch.php");

$couch = new couchClient("http://merdstrembeentsendshoste:DPJAg3RCBVINOGKYuAGapJLV@james-maroney.cloudant.com/", "talkingpoints");
$results = $couch->key(array(date("m-d-Y"), $handle))->getView("views","pointsByDateAndHandle");

if($results->total_rows == 0 || empty($results->rows)){
  echo "[]"; exit();
}

echo json_encode($results->rows[0]->value);