<?php
$password = md5($_POST['password']);
$points = $_POST['points'];

$user = $_SESSION['user'];

if( $user->password != $password ){
    echo '{"success": false, "message": "Password Incorrect. Please try again."}'; exit(0);
}

require_once('couch.php');
$couch = new couchClient($config->database->connectionString, $config->database->name);
$results = $couch->key(date("m-d-Y"))->getView("views","pointsByDate");

function deleteDoc($doc){
    global $couch;
    $couch->deleteDoc($doc);
}
function updateDoc($doc, $points){
    global $couch;

    $feed = new stdClass();
    $feed->points = $points;
    $feed->handle = $doc->value->handle;
    $feed->date = $doc->value->date;
    $feed->type = "points";
    $feed->_id = $doc->id;

    try {
      $response = $couch->updateDoc("updates", "feed", $feed, $feed->_id);
      if( !empty($response) && $response == "ok" )
        return true;
      else
        echo '{ "success": false, "message": "There was an error while saving. Please try again." }';
        exit(0);
    } catch(Exception $e) {
      echo '{ "success": false, "error": { "message": "'.$e->getMessage().'", "code": "'.$e->getCode().'" } }';
      exit(0);
    }
}

foreach($results->rows as $pointsDoc){
    if( empty($points[$pointsDoc->id]) ) deleteDoc($pointsDoc);

    $pointsFromDb = join('|', $pointsDoc->value->points);
    $pointsFromAdmin = join('|', $points[$pointsDoc->id]);
    if($pointsFromDb != $pointsFromAdmin){
        updateDoc($pointsDoc, $points[$pointsDoc->id]);
    }
}

echo '{"success": true}';