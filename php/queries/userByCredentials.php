<?php
$username = preg_replace('/[^a-zA-Z0-9._+\-]/i',"",$_REQUEST['username']);
$password = md5($_REQUEST['password']);

require("php/couch.php");

$couch = new couchClient("http://merdstrembeentsendshoste:DPJAg3RCBVINOGKYuAGapJLV@james-maroney.cloudant.com/", "talkingpoints");
//echo json_encode($couch->getAllDocs()); // getDoc("52d0d66843a629f0ca96257e7e7a5a47"));
$results = $couch->key($username)->getView("views","teacherByUsername");

if($results->total_rows != 1){
  echo "{}"; exit();
}

$user = $results->rows[0]->value;

if($user->password != $password){
  echo "{}"; exit();
}

unset($user->password);
echo json_encode($user);