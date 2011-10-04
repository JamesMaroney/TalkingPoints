<?php

require_once('couch.php');

$couch = new couchClient($config->database->connectionString, $config->database->name);
$results = $couch->key(date("m-d-Y"))->getView("views","pointsByDate");


render_header();
if($results->total_rows == 0 || empty($results->rows)){
  include('views/no_points.php');
} else {
  $points = $results->rows;
  include('views/points.php');
}
render_footer();