<?php
$points = empty($_REQUEST['points']) ? array() : $_REQUEST['points'];
$handle = preg_replace('/[^a-z0-9]/i',"",$_REQUEST['handle']);
$date = date("m-d-Y");

require("php/couch.php");

$couch = new couchClient("http://merdstrembeentsendshoste:DPJAg3RCBVINOGKYuAGapJLV@james-maroney.cloudant.com/", "talkingpoints");

$feed = new stdClass();
$feed->points = $points;
$feed->handle = $handle;
$feed->date = $date;
$feed->type = "points";
$feed->_id = "points_".$date."_".$handle;

try {
  $response = $couch->updateDoc("updates", "feed", $feed, $feed->_id);
  if( !empty($response) && $response == "ok" )
    echo '{ "success": true }';
  else
    echo '{ "success": false, "error": { "message": "The database server returned a non-successful result.", "details": '.json_encode($response).' } }';
} catch(Exception $e) {
  echo '{ "success": false, "error": { "message": "'.$e->getMessage().'", "code": "'.$e->getCode().'" } }';
  exit(0);
}
