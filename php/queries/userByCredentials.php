<?php
$username = preg_replace('/[^a-zA-Z0-9._+\-]/i',"",$_REQUEST['username']);
$password = md5($_REQUEST['password']);

require("php/couch.php");

$couch = new couchClient("http://merdstrembeentsendshoste:DPJAg3RCBVINOGKYuAGapJLV@james-maroney.cloudant.com/", "talkingpoints");
$results = $couch->key($username)->getView("views","teacherByUsername");

if(empty($results->rows)){
  echo "{}"; exit();
}

$user = $results->rows[0]->value;

if($user->password != $password){
  echo "{}"; exit();
}

unset($user->password);
$user->token = md5("k232jaslkadf90".$user->username);
echo json_encode($user);